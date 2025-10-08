<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        // Total revenue
        $totalRevenue = Payment::sum('amount');
        
        // This month revenue
        $monthlyRevenue = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');
        
        // Last month revenue for comparison
        $lastMonthRevenue = Payment::whereMonth('payment_date', now()->subMonth()->month)
            ->whereYear('payment_date', now()->subMonth()->year)
            ->sum('amount');
        
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;
        
        // Today's revenue
        $todayRevenue = Payment::whereDate('payment_date', today())->sum('amount');
        
        // Average transaction
        $averageTransaction = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->avg('amount');
        
        // Expected revenue from upcoming appointments
        $expectedRevenue = Appointment::where('status', '!=', 'cancelled')
            ->where('appointment_date', '>=', now())
            ->whereHas('service')
            ->get()
            ->sum(function($appointment) {
                return $appointment->service->price ?? 0;
            });
        
        // Chart data - revenue last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $amount = Payment::whereDate('payment_date', $date)->sum('amount');
            $chartData[] = $amount;
        }

        return [
            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('All time earnings')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart($chartData),

            Stat::make('Monthly Revenue', '$' . number_format($monthlyRevenue, 2))
                ->description($revenueGrowth >= 0 ? "+{$revenueGrowth}% from last month" : "{$revenueGrowth}% from last month")
                ->descriptionIcon($revenueGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueGrowth >= 0 ? 'success' : 'danger')
                ->chart(array_slice($chartData, -5)),

            Stat::make('Today\'s Revenue', '$' . number_format($todayRevenue, 2))
                ->description('Collected today')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary')
                ->chart(array_reverse($chartData)),

            Stat::make('Expected Revenue', '$' . number_format($expectedRevenue, 2))
                ->description('From upcoming appointments')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info')
                ->chart(array_map(fn($v) => $v * 0.8, $chartData)),
        ];
    }
}
