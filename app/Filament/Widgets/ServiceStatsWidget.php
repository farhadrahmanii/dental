<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServiceStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalServices = Service::count();
        $activeServices = Service::active()->count();
        $averageServicePrice = Service::active()->avg('price');
        $mostPopularService = Service::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->first();

        return [
            Stat::make('Total Services', $totalServices)
                ->description('All available services')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('primary')
                ->chart([5, 3, 7, 4, 8, 6, 9]),

            Stat::make('Active Services', $activeServices)
                ->description('Currently available')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([3, 2, 4, 3, 5, 4, 6]),

            Stat::make('Average Price', '$' . number_format($averageServicePrice ?? 0, 2))
                ->description('Per service')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info')
                ->chart([100, 120, 110, 130, 125, 140, 135]),

            Stat::make('Most Popular', $mostPopularService?->name ?? 'N/A')
                ->description('Most booked service')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->chart([2, 3, 1, 4, 2, 3, 2]),
        ];
    }
}
