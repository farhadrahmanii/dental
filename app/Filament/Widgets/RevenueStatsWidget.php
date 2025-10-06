<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Payment::sum('amount');
        $monthlyRevenue = Payment::whereMonth('payment_date', now()->month)->sum('amount');
        $weeklyRevenue = Payment::whereBetween('payment_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount');
        $outstandingInvoices = Invoice::where('status', '!=', 'paid')->sum('total_amount');

        return [
            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('All time revenue')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([1000, 1200, 1100, 1500, 1300, 1600, 1800]),

            Stat::make('Monthly Revenue', '$' . number_format($monthlyRevenue, 2))
                ->description('This month\'s revenue')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary')
                ->chart([200, 300, 250, 400, 350, 450, 500]),

            Stat::make('Weekly Revenue', '$' . number_format($weeklyRevenue, 2))
                ->description('This week\'s revenue')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info')
                ->chart([50, 75, 60, 100, 80, 120, 150]),

            Stat::make('Outstanding Invoices', '$' . number_format($outstandingInvoices, 2))
                ->description('Pending payments')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning')
                ->chart([500, 400, 600, 300, 700, 200, 800]),
        ];
    }
}
