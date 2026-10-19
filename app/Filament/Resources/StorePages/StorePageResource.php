<?php

namespace App\Filament\Resources\StorePages;

use App\Filament\Resources\StorePages\Pages\CreateStorePage;
use App\Filament\Resources\StorePages\Pages\EditStorePage;
use App\Filament\Resources\StorePages\Pages\ListStorePages;
use App\Filament\Resources\StorePages\Schemas\StorePageForm;
use App\Filament\Resources\StorePages\Tables\StorePagesTable;
use App\Models\StorePage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StorePageResource extends Resource
{
    protected static ?string $model = StorePage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';
    protected static string|null|\UnitEnum $navigationGroup = 'Content';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return StorePageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StorePagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStorePages::route('/'),
            'create' => CreateStorePage::route('/create'),
            'edit' => EditStorePage::route('/{record}/edit'),
        ];
    }
}
