<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $title = 'Addresses';

    public function form(Schema $schema): Schema
    {
        return $schema;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Full Name'),

                TextColumn::make('phone')
                    ->label('Phone'),

                TextColumn::make('city')
                    ->label('City'),
                TextColumn::make('district')
                    ->label('District'),
                TextColumn::make('postal_code')
                    ->label('Postal Code'),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M d, Y'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
