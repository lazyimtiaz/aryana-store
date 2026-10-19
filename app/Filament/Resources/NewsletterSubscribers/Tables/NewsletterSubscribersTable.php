<?php

namespace App\Filament\Resources\NewsletterSubscribers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class NewsletterSubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'subscribed' => 'success',
                        'unsubscribed' => 'gray',
                        default => 'warning',
                    }),

                TextColumn::make('source')
                    ->searchable(),

                TextColumn::make('consented_at')
                    ->label('Consent date')
                    ->dateTime()
                    ->placeholder('Not recorded'),

                TextColumn::make('subscribed_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Not recorded'),

                TextColumn::make('unsubscribed_at')
                    ->dateTime()
                    ->placeholder('Not recorded')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'subscribed' => 'Subscribed',
                        'unsubscribed' => 'Unsubscribed',
                    ]),

                SelectFilter::make('source')
                    ->options([
                        'footer' => 'Footer',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

