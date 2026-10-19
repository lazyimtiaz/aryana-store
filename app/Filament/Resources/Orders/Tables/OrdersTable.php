<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'cod' => 'Cash on Delivery',
                        'bkash' => 'bKash',
                        'nagad' => 'Nagad',
                        'sslcommerz' => 'SSLCommerz',
                        default => ucfirst((string) $state),
                    }),

                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'paid' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('order_status')
                    ->label('Order Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'delivered' => 'success',
                        'cancelled', 'returned' => 'danger',
                        'shipped' => 'info',
                        'processing', 'packed' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('placed_at')
                    ->label('Placed')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->defaultSort('placed_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
