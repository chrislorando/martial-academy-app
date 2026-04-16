<?php

namespace App\Filament\Resources\Members\Schemas;

use App\Enums\MembershipLevel;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Member Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),

                        DatePicker::make('date_of_birth')
                            ->maxDate(now())
                            ->native(false),

                        Select::make('sport_id')
                            ->relationship('sport', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Select::make('level')
                            ->options(MembershipLevel::class)
                            ->enum(MembershipLevel::class)
                            ->placeholder('Select level')
                            ->searchable(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
