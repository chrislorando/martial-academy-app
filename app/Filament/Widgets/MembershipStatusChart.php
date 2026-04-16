<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use App\Enums\SubscriptionStatus;
use Filament\Widgets\ChartWidget;

class MembershipStatusChart extends ChartWidget
{
    protected static ?int $sort = 5;
    protected ?string $heading = 'Active vs Expired Memberships';

    protected function getData(): array
    {
        $active = Subscription::where('status', SubscriptionStatus::Active)->count();
        $expired = Subscription::where('status', SubscriptionStatus::Expired)->count();
        $frozen = Subscription::where('status', SubscriptionStatus::Frozen)->count();
        $completed = Subscription::where('status', SubscriptionStatus::Completed)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Memberships',
                    'data' => [$active, $expired, $frozen, $completed],
                    'backgroundColor' => [
                        '#10b981',
                        '#ef4444',
                        '#f59e0b',
                        '#6b7280',
                    ],
                ],
            ],
            'labels' => ['Active', 'Expired', 'Frozen', 'Completed'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
