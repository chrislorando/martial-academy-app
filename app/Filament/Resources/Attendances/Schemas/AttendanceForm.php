<?php

namespace App\Filament\Resources\Attendances\Schemas;

use App\Enums\AttendanceStatus;
use App\Enums\UserRole;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attendance Information')
                    ->schema([
                        Select::make('sport_class_id')
                            ->relationship('sportClass', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Sport Class'),

                        Select::make('member_id')
                            ->relationship('member', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->label('Member')
                            ->afterStateUpdated(function ($state, callable $set) {
                                $member = \App\Models\Member::find($state);
                                if ($member && $member->activeSubscription) {
                                    $set('subscription_id', $member->activeSubscription->id);
                                }
                            }),

                        Hidden::make('subscription_id'),

                        DatePicker::make('date')
                            ->required()
                            ->default(now()->format('Y-m-d'))
                            ->native(false)
                            ->time(false),

                        // TimePicker::make('attendance_time')
                        //     ->label('Attendance Time')
                        //     ->default(now())
                        //     ->seconds(false)
                        //     ->native(false),

                        Select::make('status')
                            ->options(AttendanceStatus::class)
                            ->enum(AttendanceStatus::class)
                            ->required()
                            ->default(AttendanceStatus::Present)
                            ->live()
                            ->helperText('Present/Late deducts sessions for Session-based subscriptions'),
                        Select::make('marked_by')
                            ->relationship('markedBy', 'name')
                            ->default(fn() => auth()->id())
                            ->label('Marked By')
                            ->helperText('Automatically set to current user')
                            ->disabled(auth()->user()?->isCoach()),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),


            ]);
    }
}
