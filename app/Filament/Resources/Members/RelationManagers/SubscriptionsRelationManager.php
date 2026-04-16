<?php

namespace App\Filament\Resources\Members\RelationManagers;

use App\Enums\SubscriptionStatus;
use App\Enums\SubscriptionType;
use Carbon\Carbon;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class SubscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'subscriptions';

    protected static ?string $title = 'Subscriptions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Subscription Details')
                    ->schema([
                        \Filament\Forms\Components\Select::make('subscription_type')
                            ->options(SubscriptionType::class)
                            ->required()
                            ->live()
                            ->label('Type')
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $duration = $state === SubscriptionType::Monthly ? 30 : 10;
                                $set('subscription_value', $duration);

                                $startDate = $get('start_date');
                                if ($startDate && $state === SubscriptionType::Monthly) {
                                    $set('end_date', Carbon::parse($startDate)->addDays((int) $duration)->format('Y-m-d'));
                                }
                            }),

                        \Filament\Forms\Components\TextInput::make('subscription_value')
                            ->required()
                            ->numeric()
                            ->live()
                            ->label(fn($get) => $get('subscription_type') === SubscriptionType::Monthly ? 'Duration (Days)' : 'Sessions')
                            ->default(fn($get) => $get('subscription_type') === SubscriptionType::Monthly ? 30 : 10)
                            ->afterStateUpdated(function ($state, $get, $set) {
                                // Update end_date jika user mengubah angka durasi secara manual
                                $startDate = $get('start_date');
                                $type = $get('subscription_type');

                                if ($startDate && $type === SubscriptionType::Monthly) {
                                    $set('end_date', Carbon::parse($startDate)->addDays((int) $state)->format('Y-m-d'));
                                }
                            }),

                        \Filament\Forms\Components\DatePicker::make('start_date')
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

                        \Filament\Forms\Components\DatePicker::make('end_date')
                            ->required()
                            ->default(fn() => now()->addYear())
                            ->native(false),

                        \Filament\Forms\Components\TextInput::make('sessions_remaining')
                            ->numeric()
                            ->label('Sessions Remaining')
                            ->hidden(fn($get) => $get('subscription_type') === SubscriptionType::Monthly)
                            ->default(fn($get) => $get('subscription_value')),

                        \Filament\Forms\Components\Select::make('status')
                            ->options(SubscriptionStatus::class)
                            ->required()
                            ->default(SubscriptionStatus::Active),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subscription_type')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('subscription_value')
                    ->formatStateUsing(fn($record) => $record->subscription_type === 'Monthly'
                        ? $record->subscription_value . ' days'
                        : $record->subscription_value . ' sessions')
                    ->badge(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('sessions_remaining')
                    ->label('Sessions')
                    ->formatStateUsing(fn($state) => $state ?? 'Unlimited')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('status')
                //     ->options(SubscriptionStatus::class),
            ])
            ->headerActions([
                CreateAction::make()
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->requiresConfirmation()
                    ->modalWidth(Width::Large),
                \Filament\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ]);
    }
}
