<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PatientStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $totalPatients = Patient::count();
        
        // New patients this month
        $newPatientsThisMonth = Patient::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // New patients last month for comparison
        $newPatientsLastMonth = Patient::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        $patientGrowth = $newPatientsLastMonth > 0 
            ? round((($newPatientsThisMonth - $newPatientsLastMonth) / $newPatientsLastMonth) * 100, 1)
            : 0;
        
        // Active patients (had appointment in last 3 months)
        $activePatients = Patient::whereHas('appointments', function($query) {
            $query->where('appointment_date', '>=', now()->subMonths(3))
                  ->where('status', 'completed');
        })->count();
        
        // Patients with appointments today
        $patientsToday = Appointment::today()
            ->distinct('patient_id')
            ->count('patient_id');
        
        // Chart data - new patients last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = Patient::whereDate('created_at', $date)->count();
            $chartData[] = $count;
        }

        return [
            Stat::make('Total Patients', number_format($totalPatients))
                ->description('All registered patients')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart($chartData),

            Stat::make('New This Month', $newPatientsThisMonth)
                ->description($patientGrowth >= 0 ? "+{$patientGrowth}% from last month" : "{$patientGrowth}% from last month")
                ->descriptionIcon('heroicon-m-user-plus')
                ->color($patientGrowth >= 0 ? 'success' : 'warning')
                ->chart(array_slice($chartData, -5)),

            Stat::make('Active Patients', $activePatients)
                ->description('Had appointments in last 3 months')
                ->descriptionIcon('heroicon-m-heart')
                ->color('info')
                ->chart(array_reverse($chartData)),

            Stat::make('Patients Today', $patientsToday)
                ->description('Scheduled for today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color($patientsToday > 0 ? 'success' : 'gray')
                ->chart(array_map(fn() => $patientsToday, range(1, 7))),
        ];
    }
}
