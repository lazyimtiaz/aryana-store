<?php

namespace App\Filament\Resources\StoreSettings\Pages;

use App\Filament\Resources\StoreSettings\StoreSettingResource;
use App\Models\StoreSetting;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStoreSettings extends ListRecords
{
    protected static string $resource = StoreSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => StoreSetting::query()->doesntExist()),
        ];
    }
}
