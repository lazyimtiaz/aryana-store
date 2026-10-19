<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Product Variants';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Variant Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Variant Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->required()
                                    ->maxLength(100)
                                    ->unique(ignoreRecord: true),

                                TextInput::make('price')
                                    ->label('Variant Price')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('৳'),

                                TextInput::make('stock_quantity')
                                    ->label('Stock Quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->minValue(0),

                                Toggle::make('active')
                                    ->label('Active')
                                    ->default(true),
                            ]),

                        KeyValue::make('attributes')
                            ->label('Variant Attributes')
                            ->keyLabel('Attribute')
                            ->valueLabel('Value')
                            ->addActionLabel('Add Attribute')
                            ->keyPlaceholder('e.g. size')
                            ->valuePlaceholder('e.g. 50ml')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Variant')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('attributes')
                    ->label('Attributes')
                    ->formatStateUsing(function ($state) {
                        if (! is_array($state)) {
                            return '—';
                        }

                        return collect($state)
                            ->map(fn ($value, $key) => "{$key}: {$value}")
                            ->implode(', ');
                    }),

                TextColumn::make('price')
                    ->label('Price')
                    ->money('BDT')
                    ->sortable()
                    ->placeholder('Product price'),

                TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),

                ToggleColumn::make('active')
                    ->label('Active'),
            ])
            ->defaultSort('name', 'asc')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
