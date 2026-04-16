<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\Attendance;
use App\Models\SportClass;
use App\Models\User;
use Filament\Widgets\ChartWidget;

class CoachPerformanceChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected ?string $heading = 'Coach Performance';

    protected function getData(): array
    {
        $coaches = User::where('role', UserRole::Coach)->with(['coachedClasses', 'coachedClasses.attendances'])->get();

        $labels = $coaches->pluck('name')->toArray();
        $classesCount = $coaches->map(fn($coach) => $coach->coachedClasses->count())->toArray();
        $attendanceCount = $coaches->map(fn($coach) => $coach->coachedClasses->sum(fn($c) => $c->attendances->count()))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Classes',
                    'data' => $classesCount,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Attendance Records',
                    'data' => $attendanceCount,
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function getColumnSpan(): int|string|array
    {
        return 1;
    }
}
