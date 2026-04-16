<?php

namespace App\Filament\Resources\SportClasses\Pages;

use App\Filament\Resources\SportClasses\SportClassResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Attendances\AttendanceResource;

class EditSportClass extends EditRecord
{
    protected static string $resource = SportClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markAttendance')
                ->label('View & Mark Attendance')
                ->icon('heroicon-o-clipboard-document-check')
                ->url(fn () => AttendanceResource::getUrl('index', ['tableFilters' => ['sport_class' => $this->record->id]]))
                ->color('success'),
            DeleteAction::make(),
        ];
    }
}
