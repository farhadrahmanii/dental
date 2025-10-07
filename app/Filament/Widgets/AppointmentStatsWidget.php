<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppointmentStatsWidget extends StatsOverviewWidget
{

    protected function getStats(): array
    {
        $todayAppointments = Appointment::today()->count();
        $upcomingAppointments = Appointment::upcoming()->count();
        $completedThisWeek = Appointment::whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'completed')->count();
        $cancelledThisWeek = Appointment::whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'cancelled')->count();

        return [
            Stat::make('Today\'s Appointments', $todayAppointments)
                ->description('Scheduled for today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary')
                ->chart([2, 3, 1, 4, 2, 3, 2]),

            Stat::make('Upcoming Appointments', $upcomingAppointments)
                ->description('Scheduled for future dates')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success')
                ->chart([5, 7, 3, 8, 6, 9, 7]),

            Stat::make('Completed This Week', $completedThisWeek)
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([3, 4, 2, 5, 3, 4, 3]),

            Stat::make('Cancelled This Week', $cancelledThisWeek)
                ->description('Cancelled appointments')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->chart([1, 0, 2, 1, 0, 1, 0]),
        ];
    }
}
