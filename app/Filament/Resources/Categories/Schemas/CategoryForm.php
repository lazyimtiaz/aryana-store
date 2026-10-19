<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Information')
                    ->description('Basic category information and hierarchy.')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Category Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('slug', Str::slug($state));
                                    }),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                Select::make('parent_id')
                                    ->label('Parent Category')
                                    ->options(function (?Category $record) {
                                        return Category::query()
                                            ->when(
                                                $record,
                                                fn ($query) => $query->whereKeyNot($record->getKey())
                                            )
                                            ->orderBy('name')
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->placeholder('No parent — top-level category'),

                                TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                            ]),

                        Textarea::make('description')
                            ->rows(4)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Section::make('Category Image')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Category Image')
                            ->image()
                            ->disk('public')
                            ->directory('categories')
                            ->imageEditor()
                            ->maxSize(4096),
                    ]),

                Section::make('Visibility')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->default(false),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }
}
