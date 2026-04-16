<?php

namespace App\Filament\Resources\Members\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->description(function ($record) {
                        if (!$record->date_of_birth) {
                            return 'Age unknown';
                        }

                        return \Carbon\Carbon::parse($record->date_of_birth)->age . ' years old';
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('sport.name')
                    ->description(fn($record) => $record->level->value)
                    ->sortable(),

                TextColumn::make('activeSubscription.status')
                    ->label('Subscription Status')
                    ->formatStateUsing(fn($state) => $state ?? 'None')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        'Active' => 'success',
                        'Expired' => 'danger',
                        'Frozen' => 'warning',
                        'Completed' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('sport')
                    ->relationship('sport', 'name'),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make()
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
