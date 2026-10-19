<?php

namespace App\Filament\Resources\StorePages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StorePageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->required()
                ->maxLength(255),

            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->helperText('Example: about, shipping, privacy-policy'),

            Textarea::make('excerpt')
                ->rows(3)
                ->maxLength(500)
                ->columnSpanFull(),

            RichEditor::make('content')
                ->required()
                ->columnSpanFull(),

            TextInput::make('meta_title')
                ->maxLength(255),

            Textarea::make('meta_description')
                ->rows(3)
                ->maxLength(500)
                ->columnSpanFull(),

            Toggle::make('is_published')
                ->label('Published')
                ->default(false),

            DateTimePicker::make('published_at')
                ->label('Publication date')
                ->seconds(false),
        ]);
    }
}
