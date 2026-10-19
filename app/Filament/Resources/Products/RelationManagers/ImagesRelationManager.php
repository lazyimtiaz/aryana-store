<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Product Images';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Image')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Product Image')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->required()
                            ->imageEditor()
                            ->maxSize(4096),

                        TextInput::make('alt_text')
                            ->label('Alt Text')
                            ->maxLength(255),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),

                                Toggle::make('is_primary')
                                    ->label('Primary Image')
                                    ->default(false),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path')
            ->columns([
                ImageColumn::make('path')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->size(64),

                TextColumn::make('alt_text')
                    ->label('Alt Text')
                    ->limit(40)
                    ->placeholder('—'),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                ToggleColumn::make('is_primary')
                    ->label('Primary'),

                TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
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
