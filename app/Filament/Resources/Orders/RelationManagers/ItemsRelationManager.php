<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Order Items';

    public function form(Schema $schema): Schema
    {
        return $schema;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_name')
                    ->label('Product')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('sku')
                    ->label('SKU'),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric(),

                TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->money('BDT'),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('BDT'),
            ])
            ->paginated(false);
    }
}
