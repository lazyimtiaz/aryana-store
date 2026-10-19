<?php

namespace App\Filament\Resources\NewsletterSubscribers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NewsletterSubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            Select::make('status')
                ->options([
                    'subscribed' => 'Subscribed',
                    'unsubscribed' => 'Unsubscribed',
                ])
                ->required()
                ->default('subscribed'),

            TextInput::make('source')
                ->required()
                ->default('footer')
                ->maxLength(255),

            DateTimePicker::make('consented_at')
                ->label('Consent date')
                ->seconds(false),

            DateTimePicker::make('subscribed_at')
                ->seconds(false),

            DateTimePicker::make('unsubscribed_at')
                ->seconds(false),
        ]);
    }
}
