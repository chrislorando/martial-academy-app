<?php

namespace App\Filament\Resources\SportClasses\Schemas;

use App\Enums\DayOfWeek;
use App\Enums\UserRole;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class SportClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sport Class Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Select::make('sport_id')
                            ->relationship('sport', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),



                        Select::make('coach_id')
                            ->relationship('coach', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn() => auth()->id())
                            ->label('Coach')
                            ->disabled(auth()->user()?->isCoach())
                    ])
                    ->columns(1),

                Section::make('Schedule')
                    ->schema([
                        Select::make('day_of_week')
                            ->options(DayOfWeek::getOptions())
                            ->required()
                            ->native(false)
                            ->enum(DayOfWeek::class),

                        TextInput::make('max_capacity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(20),

                        TimePicker::make('start_time')
                            ->required()
                            ->seconds(false),

                        TimePicker::make('end_time')
                            ->required()
                            ->seconds(false)
                            ->after('start_time'),


                    ])
                    ->columns(2),
            ]);
    }
}
