<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Brand Information')
                    ->description('Basic information about the brand.')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Brand Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set(
                                            'slug',
                                            \Illuminate\Support\Str::slug($state)
                                        );
                                    }),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),

                        Textarea::make('description')
                            ->rows(4)
                            ->maxLength(2000)
                            ->columnSpanFull(),

                        TextInput::make('country')
                            ->label('Country / Region')
                            ->maxLength(100),
                    ])
                    ->columns(2),

                Section::make('Brand Images')
                    ->description('Upload images used throughout the storefront.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('logo')
                                    ->label('Logo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('brands/logos')
                                    ->imageEditor()
                                    ->maxSize(2048),

                                FileUpload::make('banner')
                                    ->label('Banner')
                                    ->image()
                                    ->disk('public')
                                    ->directory('brands/banners')
                                    ->imageEditor()
                                    ->maxSize(4096),
                            ]),
                    ]),

                Section::make('Visibility & Ordering')
                    ->description('Control how this brand appears in the storefront.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->default(false),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),

                                TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                            ]),
                    ]),
            ]);
    }
}
