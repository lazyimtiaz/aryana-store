<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('order_number')
                                    ->label('Order Number')
                                    ->disabled(),

                                Select::make('user_id')
                                    ->label('Customer Account')
                                    ->relationship(
                                        name: 'user',
                                        titleAttribute: 'name',
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->disabled(),

                                DateTimePicker::make('placed_at')
                                    ->label('Placed At')
                                    ->disabled(),
                            ]),
                    ]),

                Section::make('Customer Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('customer_name')
                                    ->label('Customer Name')
                                    ->required(),

                                TextInput::make('customer_email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),

                                TextInput::make('customer_phone')
                                    ->label('Phone')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Order Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('order_status')
                                    ->label('Order Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'processing' => 'Processing',
                                        'packed' => 'Packed',
                                        'shipped' => 'Shipped',
                                        'delivered' => 'Delivered',
                                        'cancelled' => 'Cancelled',
                                        'returned' => 'Returned',
                                    ])
                                    ->required(),


                                Select::make('payment_status')
                                    ->label('Payment Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'paid' => 'Paid',
                                        'failed' => 'Failed',
                                        'refunded' => 'Refunded',
                                    ])
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->required(),

                                Select::make('payment_method')
                                    ->label('Payment Method')
                                    ->options([
                                        'cod' => 'Cash on Delivery',
                                        'bkash' => 'bKash',
                                        'nagad' => 'Nagad',
                                        'sslcommerz' => 'SSLCommerz',
                                    ])
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->required(),
                            ]),
                    ]),

                Section::make('Shipping Address')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('shipping_address')
                                    ->label('Street Address')
                                    ->columnSpanFull(),

                                TextInput::make('shipping_area')
                                    ->label('Area'),

                                TextInput::make('shipping_city')
                                    ->label('City'),

                                TextInput::make('shipping_district')
                                    ->label('District'),

                                TextInput::make('shipping_postal_code')
                                    ->label('Postal Code'),
                            ]),
                    ]),

                Section::make('Order Totals')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('৳')
                                    ->disabled(),

                                TextInput::make('discount')
                                    ->label('Discount')
                                    ->numeric()
                                    ->prefix('৳')
                                    ->disabled(),

                                TextInput::make('shipping_cost')
                                    ->label('Shipping')
                                    ->numeric()
                                    ->prefix('৳')
                                    ->disabled(),

                                TextInput::make('total')
                                    ->label('Total')
                                    ->numeric()
                                    ->prefix('৳')
                                    ->disabled(),
                            ]),
                    ]),

                Section::make('Manual Payment Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('payment_reference')
                                    ->label('Transaction ID')
                                    ->maxLength(255)
                                    ->disabled(),

                                TextInput::make('payment_payer_number')
                                    ->label('Payer Phone Number')
                                    ->maxLength(30)
                                    ->disabled(),
                            ]),
                    ]),

                Section::make('Customer Notes')
                    ->schema([
                        Textarea::make('customer_notes')
                            ->label('Notes from Customer')
                            ->rows(4)
                            ->maxLength(2000),
                    ]),
            ]);
    }
}
