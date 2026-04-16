<?php

namespace App\Filament\Resources\SportClasses\Pages;

use App\Filament\Resources\SportClasses\SportClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSportClasses extends ListRecords
{
    protected static string $resource = SportClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
