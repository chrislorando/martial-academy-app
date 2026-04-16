<?php

namespace App\Filament\Resources\Members\RelationManagers;

use App\Filament\Resources\SportClasses\Tables\SportClassesTable;
use App\Filament\Resources\Sports\Tables\SportsTable;
use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class ClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'sportClasses';

    protected static ?string $title = 'Enrolled Sport Classes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('sport_class_id')
                    ->relationship('sportClasses', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Sport Class'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    // ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sport.name')
                    ->badge(),
                Tables\Columns\TextColumn::make('coach.name')
                    ->default('Unassigned'),
                Tables\Columns\TextColumn::make('day_of_week')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('start_time')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('end_time')
                    ->time('H:i'),
                // Tables\Columns\TextColumn::make('max_capacity')
                //     ->label('Capacity'),
                // Tables\Columns\TextColumn::make('enrolled_count')
                //     ->label('Enrolled')
                //     ->counts('members'),
                Tables\Columns\TextColumn::make('pivot.enrolled_at')
                    ->label('Enrolled At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->tableSelect(SportClassesTable::class)
                    ->recordSelectSearchColumns(['name'])
                    ->modalWidth(Width::MaxContent)

            ])
            ->recordActions([
                \Filament\Actions\DetachAction::make()
                    ->requiresConfirmation(),
            ]);
    }
}
