<?php

namespace App\Filament\Resources\SportClasses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class SportClassesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sport.name')
                    ->sortable()
                    ->badge(),
                TextColumn::make('coach.name')
                    ->sortable()
                    ->default('Unassigned'),
                TextColumn::make('day_of_week')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('start_time')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('enrolled_count')
                    ->label('Enrolled')
                    ->counts('members')
                    ->color('success')
                    ->badge()
                    ->sortable(),
                TextColumn::make('available_slots')
                    ->label('Available')
                    ->getStateUsing(fn($record) => $record ? $record->available_slots : 0)
                    ->color('gray')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('sport')
                    ->relationship('sport', 'name'),
                SelectFilter::make('day_of_week')
                    ->options(\App\Enums\DayOfWeek::getOptions()),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('viewAttendances')
                    ->label('View & Mark Attendance')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->url(fn($record) => \App\Filament\Resources\Attendances\AttendanceResource::getUrl('index', ['tableFilters' => ['sport_class' => $record->id]]))
                    ->color('success')
                    ->openUrlInNewTab(false),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     \Filament\Actions\DeleteAction::make()
                //         ->requiresConfirmation(),
                // ]),
            ]);
    }
}
