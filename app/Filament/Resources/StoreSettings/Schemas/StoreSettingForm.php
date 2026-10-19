<?php

namespace App\Filament\Resources\StoreSettings\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StoreSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('store_name')
                ->required()
                ->maxLength(255)
                ->default('ARANYA Skin & Beauty'),

            TextInput::make('contact_email')
                ->label('Contact email')
                ->email()
                ->maxLength(255),

            TextInput::make('contact_phone')
                ->label('Contact phone')
                ->tel()
                ->maxLength(50),

            TextInput::make('whatsapp_number')
                ->label('WhatsApp number')
                ->tel()
                ->maxLength(50)
                ->helperText('Use the number format suitable for your WhatsApp link.'),

            Textarea::make('address')
                ->rows(3)
                ->columnSpanFull(),

            Textarea::make('business_hours')
                ->label('Business hours')
                ->rows(3)
                ->helperText('Example: Saturday–Thursday, 10 AM–8 PM.'),

            TextInput::make('facebook_url')
                ->url()
                ->maxLength(255),

            TextInput::make('instagram_url')
                ->url()
                ->maxLength(255),

            TextInput::make('tiktok_url')
                ->url()
                ->maxLength(255),

            Textarea::make('contact_intro')
                ->label('Contact page introduction')
                ->rows(4)
                ->columnSpanFull(),
        ]);
    }
}
