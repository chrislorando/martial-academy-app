<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->label('Date'),
                TextColumn::make('sportClass.name')
                    ->sortable()
                    ->searchable()
                    ->label('Sport Class')
                    ->description(fn ($record): ?string => $record->sportClass->day_of_week),
                TextColumn::make('member.name')
                    ->sortable()
                    ->searchable()
                    ->label('Member'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'Present' => 'success',
                        'Absent' => 'danger',
                        'Late' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('attendance_time')
                    ->time('H:i')
                    ->label('Time'),
                TextColumn::make('subscription.subscription_type')
                    ->label('Subscription Type')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('markedBy.name')
                    ->sortable()
                    ->label('Marked By')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Present' => 'Present',
                        'Absent' => 'Absent',
                        'Late' => 'Late',
                    ]),
                SelectFilter::make('sport_class')
                    ->relationship('sportClass', 'name'),
                SelectFilter::make('member')
                    ->relationship('member', 'name'),
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
