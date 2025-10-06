<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PatientStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalPatients = Patient::count();
        $newPatientsThisMonth = Patient::whereMonth('created_at', now()->month)->count();
        $newPatientsThisWeek = Patient::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $patientsWithOutstandingBalance = Patient::whereHas('invoices', function($query) {
            $query->where('status', '!=', 'paid');
        })->count();

        return [
            Stat::make('Total Patients', $totalPatients)
                ->description('All registered patients')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('New This Month', $newPatientsThisMonth)
                ->description('Patients registered this month')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success')
                ->chart([3, 5, 2, 8, 4, 6, 9]),

            Stat::make('New This Week', $newPatientsThisWeek)
                ->description('Patients registered this week')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info')
                ->chart([1, 2, 1, 3, 2, 1, 2]),

            Stat::make('Outstanding Balance', $patientsWithOutstandingBalance)
                ->description('Patients with pending payments')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning')
                ->chart([2, 1, 3, 2, 1, 2, 1]),
        ];
    }
}
