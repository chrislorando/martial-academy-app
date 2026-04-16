<?php

namespace App\Filament\Widgets;

use App\Models\SportClass;
use Filament\Widgets\ChartWidget;

class ClassUtilizationChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'Class Utilization Report';

    protected function getData(): array
    {
        $classes = SportClass::withCount('members')->get();

        $labels = $classes->pluck('name')->toArray();
        $enrolledCounts = $classes->pluck('members_count')->toArray();
        $capacityCounts = $classes->pluck('max_capacity')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Enrolled',
                    'data' => $enrolledCounts,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Available Capacity',
                    'data' => array_map(fn($a, $b) => $b - $a, $enrolledCounts, $capacityCounts),
                    'backgroundColor' => '#e5e7eb',
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
