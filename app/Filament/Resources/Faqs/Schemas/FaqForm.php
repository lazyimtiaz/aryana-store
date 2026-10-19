<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('question')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            TextInput::make('category')
                ->maxLength(255)
                ->placeholder('Example: Orders, Shipping, Returns'),

            TextInput::make('sort_order')
                ->numeric()
                ->integer()
                ->minValue(0)
                ->default(0)
                ->required(),

            RichEditor::make('answer')
                ->required()
                ->columnSpanFull(),

            Toggle::make('is_published')
                ->label('Published')
                ->default(false),
        ]);
    }
}
