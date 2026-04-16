<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use App\Enums\SubscriptionType;
use App\Enums\SubscriptionStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subscription Details')
                    ->schema([
                        Select::make('member_id')
                            ->relationship('member', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Member')
                            ->columnSpan(2),

                        Select::make('subscription_type')
                            ->options(SubscriptionType::class)
                            ->required()
                            ->live()
                            ->label('Type')
                            ->default(SubscriptionType::Monthly)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $duration = $state === SubscriptionType::Monthly ? 30 : 10;
                                $set('subscription_value', $duration);

                                $startDate = $get('start_date');
                                if ($startDate && $state === SubscriptionType::Monthly) {
                                    $set('end_date', Carbon::parse($startDate)->addDays((int) $duration)->format('Y-m-d'));
                                }
                            }),

                        TextInput::make('subscription_value')
                            ->required()
                            ->numeric()
                            ->live()
                            ->label(fn($get) => $get('subscription_type') === SubscriptionType::Monthly ? 'Duration (Days)' : 'Sessions')
                            ->default(30)
                            ->helperText(fn($get) => $get('subscription_type') === SubscriptionType::Monthly ? 'Number of days (e.g., 30 for monthly)' : 'Number of sessions (e.g., 10, 20)')
                            ->afterStateUpdated(function ($state, $get, $set) {
                                // Update end_date jika user mengubah angka durasi secara manual
                                $startDate = $get('start_date');
                                $type = $get('subscription_type');

                                if ($startDate && $type === SubscriptionType::Monthly) {
                                    $set('end_date', Carbon::parse($startDate)->addDays((int) $state)->format('Y-m-d'));
                                }
                            }),

                        DatePicker::make('start_date')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                // Update end_date saat tanggal mulai diubah
                                $type = $get('subscription_type');
                                $duration = $get('subscription_value');

                                if ($state && $type === SubscriptionType::Monthly) {
                                    $set('end_date', Carbon::parse($state)->addDays((int) $duration)->format('Y-m-d'));
                                } else {
                                    $set('end_date', $state);
                                }
                            }),

                        DatePicker::make('end_date')
                            ->required()
                            ->native(false)
                            ->dehydrated()
                            ->helperText('Auto-calculated from start_date + duration'),

                        TextInput::make('sessions_remaining')
                            ->numeric()
                            ->label('Sessions Remaining')
                            ->hidden(fn($get) => $get('subscription_type') === SubscriptionType::Monthly)
                            ->default(fn($get) => $get('subscription_value'))
                            ->readOnly(),

                        ToggleButtons::make('status')
                            ->options(SubscriptionStatus::class)
                            ->required()
                            ->inline()
                            ->helperText('Set to Expired/Completed when subscription ends'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
