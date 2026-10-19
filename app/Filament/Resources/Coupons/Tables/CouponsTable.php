<?php

namespace App\Filament\Resources\Coupons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(
                        fn (string $state) => $state === 'percentage'
                            ? 'Percentage'
                            : 'Fixed'
                    )
                    ->badge(),

                TextColumn::make('value')
                    ->label('Discount')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->type === 'percentage'
                            ? $state . '%'
                            : '৳' . number_format((float) $state, 2);
                    }),

                TextColumn::make('minimum_order_amount')
                    ->label('Min. Order')
                    ->money('BDT'),

                TextColumn::make('usage_limit')
                    ->label('Usage Limit')
                    ->placeholder('Unlimited'),

                TextColumn::make('usage_limit_per_user')
                    ->label('Per User')
                    ->placeholder('Unlimited'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'fixed' => 'Fixed Amount',
                        'percentage' => 'Percentage',
                    ]),

                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
