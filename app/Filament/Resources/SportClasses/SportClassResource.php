<?php

namespace App\Filament\Resources\SportClasses;

use App\Filament\Resources\SportClasses\Pages\CreateSportClass;
use App\Filament\Resources\SportClasses\Pages\EditSportClass;
use App\Filament\Resources\SportClasses\Pages\ListSportClasses;
use App\Filament\Resources\SportClasses\Schemas\SportClassForm;
use App\Filament\Resources\SportClasses\Tables\SportClassesTable;
use App\Models\SportClass;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SportClassResource extends Resource
{
    protected static ?string $model = SportClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'Programs';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return SportClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SportClassesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                auth()->user()?->isCoach(),
                fn (Builder $query) => $query->where('coach_id', auth()->id())
            );
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttendancesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSportClasses::route('/'),
            'create' => CreateSportClass::route('/create'),
            'edit' => EditSportClass::route('/{record}/edit'),
        ];
    }
}
