<?php

namespace App\Filament\Resources\Subscriptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.name')
                    ->searchable()
                    ->sortable()
                    ->label('Member'),
                TextColumn::make('subscription_type')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'Monthly' => 'primary',
                        'Session Based' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('subscription_value')
                    ->label('Value')
                    ->formatStateUsing(fn ($record) => $record->subscription_type === 'Monthly' 
                        ? $record->subscription_value . ' days' 
                        : $record->subscription_value . ' sessions')
                    ->badge(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->color('danger')
                    ->description(fn ($record): ?string => $record->end_date->isPast() ? 'Expired' : null),
                TextColumn::make('sessions_remaining')
                    ->label('Sessions')
                    ->formatStateUsing(fn ($state) => $state ?? 'Unlimited')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state === null => 'success',
                        $state <= 3 => 'danger',
                        $state <= 10 => 'warning',
                        default => 'success',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'Active' => 'success',
                        'Expired' => 'danger',
                        'Frozen' => 'warning',
                        'Completed' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Expired' => 'Expired',
                        'Frozen' => 'Frozen',
                        'Completed' => 'Completed',
                    ]),
                SelectFilter::make('subscription_type')
                    ->options([
                        'Monthly' => 'Monthly',
                        'Session Based' => 'Session Based',
                    ]),
            ])
            ->recordActions([
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
