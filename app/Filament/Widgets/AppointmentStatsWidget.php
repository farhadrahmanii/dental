<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AppointmentStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Today's appointments
        $todayAppointments = Appointment::today()->count();
        $todayCompleted = Appointment::today()->where('status', 'completed')->count();
        
        // Upcoming appointments
        $upcomingAppointments = Appointment::upcoming()->count();
        
        // This week stats
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $completedThisWeek = Appointment::whereBetween('appointment_date', [$weekStart, $weekEnd])
            ->where('status', 'completed')->count();
        
        // This month stats
        $monthStart = now()->startOfMonth();
        $completedThisMonth = Appointment::whereBetween('appointment_date', [$monthStart, now()])
            ->where('status', 'completed')->count();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();
        $completedLastMonth = Appointment::whereBetween('appointment_date', [$lastMonthStart, $lastMonthEnd])
            ->where('status', 'completed')->count();
        
        // Calculate growth
        $monthlyGrowth = $completedLastMonth > 0 
            ? round((($completedThisMonth - $completedLastMonth) / $completedLastMonth) * 100, 1)
            : 0;
        
        // Pending confirmations
        $pendingConfirmations = Appointment::where('status', 'pending')
            ->where('appointment_date', '>=', now())
            ->count();
        
        // Get chart data for last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = Appointment::whereDate('appointment_date', $date)->count();
            $chartData[] = $count;
        }

        return [
            Stat::make('Today\'s Appointments', $todayAppointments)
                ->description($todayCompleted > 0 ? "{$todayCompleted} completed today" : 'No completed yet')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->chart($chartData),

            Stat::make('Upcoming Appointments', $upcomingAppointments)
                ->description('Scheduled for future')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success')
                ->chart(array_reverse($chartData)),

            Stat::make('Completed This Month', $completedThisMonth)
                ->description($monthlyGrowth >= 0 ? "+{$monthlyGrowth}% from last month" : "{$monthlyGrowth}% from last month")
                ->descriptionIcon($monthlyGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthlyGrowth >= 0 ? 'success' : 'danger')
                ->chart(array_slice($chartData, -5)),

            Stat::make('Pending Confirmations', $pendingConfirmations)
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color($pendingConfirmations > 5 ? 'warning' : 'info')
                ->chart(array_map(fn() => $pendingConfirmations, range(1, 7))),
        ];
    }
}
