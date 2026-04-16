<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\SportClass;
use App\Models\Subscription;
use App\Enums\SubscriptionStatus;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $totalMembers = Member::count();
        $activeSubscriptions = Subscription::where('status', SubscriptionStatus::Active)->count();
        $totalAttendances = Attendance::count();
        $presentAttendances = Attendance::where('status', 'Present')->count();
        $attendanceRate = $totalAttendances > 0 ? round(($presentAttendances / $totalAttendances) * 100) : 0;
        $classesToday = SportClass::where('day_of_week', strtolower(Carbon::now()->englishDayOfWeek))->count();
        $expiringSoon = Subscription::where('status', SubscriptionStatus::Active)
            ->where('end_date', '<=', Carbon::now()->addDays(7))
            ->count();

        return [
            Stat::make('Total Members', number_format($totalMembers))
                ->description('All registered members')
                ->icon('heroicon-o-users'),
            Stat::make('Active Subscriptions', number_format($activeSubscriptions))
                ->description($expiringSoon > 0 ? "{$expiringSoon} expiring soon" : 'All memberships active')
                ->descriptionColor($expiringSoon > 0 ? 'warning' : 'gray')
                ->icon('heroicon-o-credit-card'),
            Stat::make('Attendance Rate', "{$attendanceRate}%")
                ->description("{$presentAttendances} of {$totalAttendances} attended")
                ->icon('heroicon-o-check-circle'),
            Stat::make('Classes Today', number_format($classesToday))
                ->description('Scheduled for today')
                ->icon('heroicon-o-calendar'),
        ];
    }
}
