<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Core product information.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Product Name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', Str::slug($state));
                            })
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->required()
                                    ->maxLength(100)
                                    ->unique(ignoreRecord: true),
                            ]),

                        Textarea::make('short_description')
                            ->label('Short Description')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                    ]),



                Section::make('Classification')
                    ->description('Organize this product in your catalog.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('brand_id')
                                    ->label('Brand')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('categories')
                                    ->label('Categories')
                                    ->relationship('categories', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),
                    ]),


                Section::make('Pricing')
                    ->description('Set the product selling price and internal cost.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('regular_price')
                                    ->label('Regular Price')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->prefix('৳'),

                                TextInput::make('sale_price')
                                    ->label('Sale Price')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('৳'),

                                TextInput::make('cost_price')
                                    ->label('Cost Price')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('৳'),
                            ]),
                    ]),


                Section::make('Inventory')
                    ->description('Control current stock and availability.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('stock_quantity')
                                    ->label('Stock Quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->minValue(0),

                                Select::make('stock_status')
                                    ->label('Stock Status')
                                    ->options([
                                        'in_stock' => 'In Stock',
                                        'out_of_stock' => 'Out of Stock',
                                        'on_backorder' => 'On Backorder',
                                    ])
                                    ->required()
                                    ->default('in_stock'),
                            ]),
                    ]),


                Section::make('Product Details')
                    ->description('Information displayed on the product page.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('size')
                                    ->label('Size'),

                                TextInput::make('volume')
                                    ->label('Volume'),

                                TextInput::make('weight')
                                    ->label('Weight'),
                            ]),

                        TextInput::make('skin_type')
                            ->label('Skin Type')
                            ->placeholder('e.g. All Skin Types'),

                        TextInput::make('skin_concerns')
                            ->label('Skin Concerns')
                            ->placeholder('e.g. Acne, Dryness, Dark Spots'),

                        Textarea::make('ingredients')
                            ->label('Ingredients')
                            ->rows(5)
                            ->columnSpanFull(),

                        RichEditor::make('how_to_use')
                            ->label('How to Use')
                            ->columnSpanFull(),
                    ]),


                Section::make('Storefront')
                    ->description('Control how the product appears across the store.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'published' => 'Published',
                                        'draft' => 'Draft',
                                        'archived' => 'Archived',
                                    ])
                                    ->required()
                                    ->default('draft'),

                                Toggle::make('featured')
                                    ->label('Featured')
                                    ->default(false),

                                Toggle::make('new_arrival')
                                    ->label('New Arrival')
                                    ->default(false),

                                Toggle::make('best_seller')
                                    ->label('Best Seller')
                                    ->default(false),
                            ]),
                    ]),


                Section::make('SEO')
                    ->description('Search engine metadata for the product page.')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO Title')
                            ->maxLength(255),

                        Textarea::make('seo_description')
                            ->label('SEO Description')
                            ->rows(3)
                            ->maxLength(500),
                    ]),
            ]);
    }
}
