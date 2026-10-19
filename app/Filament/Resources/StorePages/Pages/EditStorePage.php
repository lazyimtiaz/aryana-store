<?php

namespace App\Filament\Resources\StorePages\Pages;

use App\Filament\Resources\StorePages\StorePageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStorePage extends EditRecord
{
    protected static string $resource = StorePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
