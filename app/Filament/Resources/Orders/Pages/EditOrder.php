<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Product;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    /**
     * Update the order and handle inventory restoration atomically
     * when an order is cancelled.
     */

    protected function getHeaderActions(): array
    {
        return [
            Action::make('verifyPayment')
                ->label('Verify Payment')
                ->icon('heroicon-o-shield-check')
                ->color('success')
                ->visible(function (): bool {
                    $order = $this->record;

                    return $order instanceof Order
                        && in_array(
                            $order->payment_method,
                            ['bkash', 'nagad'],
                            true
                        )
                        && $order->payment_status === 'pending';
                })
                ->modalHeading('Verify Manual Payment')
                ->modalDescription(
                    'Compare the transaction ID and payer phone number '
                    . 'with your official merchant account before recording '
                    . 'a verification result.'
                )
                ->modalSubmitActionLabel('Record Verification')
                ->schema([
                    Select::make('verification_result')
                        ->label('Verification Result')
                        ->options([
                            'paid' => 'Payment Confirmed',
                            'failed' => 'Payment Not Confirmed',
                        ])
                        ->required()
                        ->native(false),

                    Textarea::make('payment_verification_notes')
                        ->label('Verification Notes')
                        ->helperText(
                            'Record what you checked and why you reached this result.'
                        )
                        ->required()
                        ->minLength(5)
                        ->maxLength(2000),

                    Checkbox::make('transaction_confirmed')
                        ->label(
                            'I checked this transaction against the official '
                            . 'bKash or Nagad merchant account.'
                        )
                        ->accepted()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $record = $this->record;

                    abort_unless($record instanceof Order, 404);

                    DB::transaction(function () use ($record, $data): void {
                        /** @var Order $order */
                        $order = Order::query()
                            ->whereKey($record->getKey())
                            ->lockForUpdate()
                            ->firstOrFail();

                        if (! in_array(
                            $order->payment_method,
                            ['bkash', 'nagad'],
                            true
                        )) {
                            throw ValidationException::withMessages([
                                'verification_result' =>
                                    'Only bKash and Nagad orders can use '
                                    . 'this verification workflow.',
                            ]);
                        }

                        if (in_array(
                            $order->payment_status,
                            ['pending', 'failed'],
                            true
                        )) {
                            throw ValidationException::withMessages([
                                'verification_result' =>
                                    'This payment is no longer pending. '
                                    . 'Refresh the order and review its status.',
                            ]);
                        }

                        if (
                            $data['verification_result'] === 'paid'
                            && (
                                blank($order->payment_reference)
                                || blank($order->payment_payer_number)
                            )
                        ) {
                            throw ValidationException::withMessages([
                                'verification_result' =>
                                    'A transaction ID and payer phone number '
                                    . 'are required before confirming payment.',
                            ]);
                        }

                        $order->forceFill([
                            'payment_status' => $data['verification_result'],
                            'payment_verified_at' => now(),
                            'payment_verified_by' => auth()->id(),
                            'payment_verification_notes' => trim(
                                $data['payment_verification_notes']
                            ),
                        ])->save();
                    });

                    $this->record->refresh();
                })
                ->successNotificationTitle(
                    'Payment verification result recorded'
                ),
        ];
    }
    protected function handleRecordUpdate(
        Model $record,
        array $data
    ): Model {
        return DB::transaction(function () use ($record, $data): Model {
            /** @var Order $order */
            $order = Order::query()
                ->whereKey($record->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $previousStatus = $order->order_status;
            $newStatus = $data['order_status'] ?? $previousStatus;

            // A cancelled order cannot be reopened through this form.
            if (
                $previousStatus === 'cancelled'
                && $newStatus !== 'cancelled'
            ) {
                throw ValidationException::withMessages([
                    'data.order_status' =>
                        'A cancelled order cannot be reopened. '
                        . 'Create a separate workflow if the order needs to be reinstated.',
                ]);
            }

            // Do not allow shipped, delivered, or returned orders
            // to be cancelled through this simple restoration workflow.
            if (
                $newStatus === 'cancelled'
                && in_array(
                    $previousStatus,
                    ['shipped', 'delivered', 'returned'],
                    true
                )
            ) {
                throw ValidationException::withMessages([
                    'data.order_status' =>
                        'This order has already been shipped, delivered, '
                        . 'or returned. Use a separate return/refund workflow.',
                ]);
            }

            $isNewCancellation =
                $previousStatus !== 'cancelled'
                && $newStatus === 'cancelled';

            if ($isNewCancellation) {
                // Protect against duplicate restoration if inventory
                // history already records a cancellation for this order.
                $alreadyRestored = InventoryTransaction::query()
                    ->where('reference_type', 'order')
                    ->where('reference_id', $order->id)
                    ->where('type', 'cancellation')
                    ->exists();

                if ($alreadyRestored) {
                    throw ValidationException::withMessages([
                        'data.order_status' =>
                            'Inventory restoration has already been recorded '
                            . 'for this order. Please review its inventory history.',
                    ]);
                }

                foreach ($order->items()->get() as $item) {
                    // A deleted product cannot be safely restocked.
                    if (! $item->product_id) {
                        throw ValidationException::withMessages([
                            'data.order_status' =>
                                'A product in this order no longer exists. '
                                . 'Resolve its inventory record before cancelling.',
                        ]);
                    }

                    /** @var Product|null $product */
                    $product = Product::query()
                        ->whereKey($item->product_id)
                        ->lockForUpdate()
                        ->first();

                    if (! $product) {
                        throw ValidationException::withMessages([
                            'data.order_status' =>
                                'A product in this order no longer exists. '
                                . 'Resolve its inventory record before cancelling.',
                        ]);
                    }

                    $quantityToRestore = (int) $item->quantity;

                    if ($quantityToRestore <= 0) {
                        throw ValidationException::withMessages([
                            'data.order_status' =>
                                'An order item has an invalid quantity. '
                                . 'Inventory was not restored.',
                        ]);
                    }

                    $stockBefore = (int) $product->stock_quantity;
                    $stockAfter = $stockBefore + $quantityToRestore;

                    $product->increment(
                        'stock_quantity',
                        $quantityToRestore
                    );

                    InventoryTransaction::create([
                        'product_id' => $product->id,
                        'product_variant_id' => $item->product_variant_id,
                        'type' => 'cancellation',
                        'quantity' => $quantityToRestore,
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                        'notes' =>
                            "Stock restored after cancellation of order "
                            . $order->order_number . '.',
                    ]);
                }
            }

            $order->fill($data);
            $order->save();

            return $order;
        });
    }
}
