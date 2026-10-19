<?php

namespace App\Filament\Resources\StoreSettings\Tables;


use Filament\Actions\EditAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;

class StoreSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('store_name')
                    ->searchable(),

                TextColumn::make('contact_email')
                    ->searchable()
                    ->placeholder('Not set'),

                TextColumn::make('contact_phone')
                    ->placeholder('Not set'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', 'asc')
            ->recordActions([
                EditAction::make(),
            ]);

    }
}

