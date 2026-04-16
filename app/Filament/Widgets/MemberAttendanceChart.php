<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MemberAttendanceChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected ?string $heading = 'Member Attendance Report';

    protected function getData(): array
    {
        $labels = [];
        $presentData = [];
        $absentData = [];
        $lateData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');

            $presentData[] = Attendance::whereDate('date', $date->toDateString())
                ->where('status', 'Present')
                ->count();
            $absentData[] = Attendance::whereDate('date', $date->toDateString())
                ->where('status', 'Absent')
                ->count();
            $lateData[] = Attendance::whereDate('date', $date->toDateString())
                ->where('status', 'Late')
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $presentData,
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label' => 'Absent',
                    'data' => $absentData,
                    'backgroundColor' => '#ef4444',
                ],
                [
                    'label' => 'Late',
                    'data' => $lateData,
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
