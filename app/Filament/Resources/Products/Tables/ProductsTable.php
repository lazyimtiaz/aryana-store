<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('regular_price')
                    ->label('Price')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label('Sale Price')
                    ->money('BDT')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('stock_status')
                    ->label('Stock Status')
                    ->badge()
                    ->sortable(),

                ToggleColumn::make('featured')
                    ->label('Featured'),

                ToggleColumn::make('new_arrival')
                    ->label('New'),

                ToggleColumn::make('best_seller')
                    ->label('Best Seller'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
