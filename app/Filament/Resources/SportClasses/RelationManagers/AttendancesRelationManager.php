<?php

namespace App\Filament\Resources\SportClasses\RelationManagers;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    protected static ?string $title = 'Class Attendance';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Forms\Components\Select::make('member_id')
                    // ->relationship('sportClass.members', 'name')
                    ->options(function (RelationManager $livewire) {
                        // Mengambil data dari relasi owner record
                        return $livewire->getOwnerRecord()
                            ->members()
                            ->with('activeSubscription')
                            ->pluck('members.name', 'members.id');
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Member')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $member = \App\Models\Member::find($state);
                        if ($member && $member->activeSubscription) {
                            $set('subscription_id', $member->activeSubscription->id);
                        }
                    }),

                \Filament\Forms\Components\Hidden::make('subscription_id'),
                \Filament\Forms\Components\Hidden::make('marked_by')->default(auth()->id()),


                DateTimePicker::make('date')
                    ->required()
                    ->default(now())
                    ->native(false),

                // TimePicker::make('attendance_time')
                //     ->label('Attendance Time')
                //     ->default(now())
                //     ->seconds(false)
                //     ->native(false),

                \Filament\Forms\Components\Select::make('status')
                    ->options(AttendanceStatus::getOptions())
                    ->required()
                    ->default(AttendanceStatus::Present->value)
                    ->live()
                    ->helperText('Present/Late deducts sessions for Session-based subscriptions')
                    ->inlineLabel(false)
                    ->native(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('member.name')
                    ->sortable()
                    ->searchable()
                    ->label('Member')
                    ->description(fn($record) => $record->member->level->value ?? null)
                    ->grow(false)
                    ->alignStart(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options(AttendanceStatus::getOptions())
                    ->afterStateUpdated(function ($record, $state) {
                        $this->updateAttendanceStatus($record, $state);
                    })
                    ->alignStart()
                    ->grow(false),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable()
                    ->grow(false)
                    ->description(fn($state) => $state->format('H:i:s')),
                // Tables\Columns\TextColumn::make('attendance_time')
                //     ->time('H:i')
                //     ->label('Time')
                //     ->description(fn($record): ?string => $record->attendance_time ? null : 'Not recorded'),
                // Tables\Columns\TextColumn::make('markedBy.name')
                //     ->sortable()
                //     ->label('Marked By')
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        DatePicker::make('date')
                            // ->native(false)
                            ->label('Filter by Date')
                            ->default(now()->format('Y-m-d')),
                    ])
                    ->query(function ($query, array $data) {
                        if (isset($data['date']) && $data['date']) {
                            $query->whereDate('date', $data['date']);
                        }
                    })
                    ->indicateUsing(function (array $data) {
                        return isset($data['date']) && $data['date'] ? 'Date: ' . $data['date'] : null;
                    }),
                Tables\Filters\SelectFilter::make('status')
                    ->options(AttendanceStatus::getOptions())
                    ->placeholder('All statuses'),

            ])
            ->defaultSort('member.name', 'asc')
            ->headerActions([
                \Filament\Actions\Action::make('generateDailyAttendance')
                    ->label('Generate Attendance')
                    ->icon('heroicon-o-calendar')
                    ->form([
                        DatePicker::make('date')
                            ->label('Attendance Date')
                            ->default(now()->format('Y-m-d'))
                            ->required()
                            ->native(false)
                            ->time(false),
                    ])
                    ->action(function (array $data) {
                        $this->generateDailyAttendance($data['date']);
                    })
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Attendance for All Members')
                    ->modalDescription('This will create attendance records for all enrolled members who do not have one for this date. Existing records will not be modified.'),
                \Filament\Actions\CreateAction::make()
                    ->label('Mark Single Attendance')
                    ->before(function (\Filament\Actions\CreateAction $action, $data) {

                        $attendance = Attendance::where('member_id', $data['member_id'])
                            ->where('sport_class_id', $this->ownerRecord->id)
                            ->exists();
                        if ($attendance) {
                            Notification::make()
                                ->warning()
                                ->title('Error message')
                                ->body('Member is already marked.')
                                ->send();

                            $action->halt();
                        }

                    })
            ])
            ->defaultPaginationPageOption(25)
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                // \Filament\Actions\BulkActionGroup::make([
                //     \Filament\Actions\DeleteBulkAction::make()
                //         ->requiresConfirmation(),
                //     \Filament\Actions\BulkAction::make('markPresent')
                //         ->label('Mark as Present')
                //         ->icon('heroicon-o-check-circle')
                //         ->color('success')
                //         ->action(function ($records) {
                //             foreach ($records as $record) {
                //                 $this->updateAttendanceStatus($record, AttendanceStatus::Present->value);
                //             }
                //             Notification::make()
                //                 ->title('Attendance Updated')
                //                 ->body(count($records) . ' records marked as Present')
                //                 ->success()
                //                 ->send();
                //         })
                //         ->requiresConfirmation()
                //         ->modalHeading('Mark as Present')
                //         ->modalDescription('This will mark all selected attendance records as Present. Sessions will be deducted for Session-based subscriptions.'),
                //     \Filament\Actions\BulkAction::make('markAbsent')
                //         ->label('Mark as Absent')
                //         ->icon('heroicon-o-x-circle')
                //         ->color('danger')
                //         ->action(function ($records) {
                //             foreach ($records as $record) {
                //                 $this->updateAttendanceStatus($record, AttendanceStatus::Absent->value);
                //             }
                //             Notification::make()
                //                 ->title('Attendance Updated')
                //                 ->body(count($records) . ' records marked as Absent')
                //                 ->success()
                //                 ->send();
                //         })
                //         ->requiresConfirmation(),
                //     \Filament\Actions\BulkAction::make('markLate')
                //         ->label('Mark as Late')
                //         ->icon('heroicon-o-clock')
                //         ->color('warning')
                //         ->action(function ($records) {
                //             foreach ($records as $record) {
                //                 $this->updateAttendanceStatus($record, AttendanceStatus::Late->value);
                //             }
                //             Notification::make()
                //                 ->title('Attendance Updated')
                //                 ->body(count($records) . ' records marked as Late')
                //                 ->success()
                //                 ->send();
                //         })
                //         ->requiresConfirmation()
                //         ->modalHeading('Mark as Late')
                //         ->modalDescription('This will mark all selected attendance records as Late. Sessions will be deducted for Session-based subscriptions.'),
                // ]),
            ]);
    }

    protected function generateDailyAttendance(string $date): void
    {
        $sportClass = $this->getOwnerRecord();
        $members = $sportClass->members()->with('activeSubscription')->get();

        $createdCount = 0;
        $existingCount = 0;

        foreach ($members as $member) {
            $existing = Attendance::where('sport_class_id', $sportClass->id)
                ->where('member_id', $member->id)
                ->whereDate('date', $date)
                ->first();

            if ($existing) {
                $existingCount++;
            } else {
                Attendance::create([
                    'sport_class_id' => $sportClass->id,
                    'member_id' => $member->id,
                    'subscription_id' => $member->activeSubscription?->id,
                    'date' => $date,
                    'status' => null,
                    // 'marked_by' => auth()->id(),
                ]);
                $createdCount++;
            }
        }

        Notification::make()
            ->title('Daily Attendance Generated')
            ->body("Created: {$createdCount} new records | Existing: {$existingCount} records (skipped)")
            ->success()
            ->send();
    }

    protected function updateAttendanceStatus(Attendance $attendance, string $status): void
    {
        $attendance->update([
            'status' => $status,
            'date' => now(),
            'marked_by' => auth()->id(),
        ]);

        Notification::make()
            ->title('Attendance Status Updated')
            ->body("Member: {$attendance->member->name} - Status: {$status} at " . now()->format('H:i'))
            ->success()
            ->send();
    }
}
