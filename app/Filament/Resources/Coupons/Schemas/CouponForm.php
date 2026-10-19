<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Coupon Information')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->extraInputAttributes(['onChange' => 'this.value = this.value.toUpperCase()'])
                            ->maxLength(255),

                        Select::make('type')
                            ->label('Discount Type')
                            ->options([
                                'fixed' => 'Fixed Amount',
                                'percentage' => 'Percentage',
                            ])
                            ->required()
                            ->default('fixed'),

                        TextInput::make('value')
                            ->numeric()
                            ->required()
                            ->minValue(0),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Usage Rules')
                    ->schema([
                        TextInput::make('minimum_order_amount')
                            ->label('Minimum Order Amount')
                            ->numeric()
                            ->minValue(0),

                        TextInput::make('maximum_discount_amount')
                            ->label('Maximum Discount Amount')
                            ->numeric()
                            ->minValue(0),

                        TextInput::make('usage_limit')
                            ->label('Total Usage Limit')
                            ->numeric()
                            ->minValue(1),

                        TextInput::make('usage_limit_per_user')
                            ->label('Usage Limit Per User')
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->columns(2),

                Section::make('Validity')
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Starts At'),

                        DateTimePicker::make('expires_at')
                            ->label('Expires At')
                            ->after('starts_at'),
                    ])
                    ->columns(2),
            ]);
    }
}
