<?php

namespace App\Filament\Widgets;

use App\Helpers\CurrencyHelper;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServiceStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 8;

    protected function getStats(): array
    {
        $totalServices = Service::count();
        $activeServices = Service::active()->count();
        
        // Average service price
        $averageServicePrice = Service::active()->avg('price');
        $averagePriceValue = $averageServicePrice ?? 0;
        
        // Most popular service this month
        $mostPopularService = Service::withCount(['appointments' => function ($query) {
                $query->whereMonth('appointment_date', now()->month)
                      ->whereYear('appointment_date', now()->year);
            }])
            ->orderBy('appointments_count', 'desc')
            ->first();
        
        // Total revenue from services
        $serviceRevenue = Payment::whereNotNull('service_id')
            ->whereMonth('payment_date', now()->month)
            ->sum('amount');
        $serviceRevenueValue = $serviceRevenue ?? 0;

        return [
            Stat::make('Total Services', $totalServices)
                ->description("{$activeServices} active services")
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('primary')
                ->chart([5, 3, 7, 4, 8, 6, $totalServices]),

            Stat::make('Average Price', CurrencyHelper::symbol() . number_format($averagePriceValue, 2))
                ->description('Per service')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info')
                ->chart([
                    $averagePriceValue * 0.8,
                    $averagePriceValue * 0.9,
                    $averagePriceValue,
                    $averagePriceValue * 1.1,
                    $averagePriceValue * 1.05,
                    $averagePriceValue * 1.15,
                    $averagePriceValue,
                ]),

            Stat::make('Most Booked', $mostPopularService?->name ?? 'N/A')
                ->description(($mostPopularService?->appointments_count ?? 0) . ' bookings this month')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->chart([2, 3, 1, 4, 2, 5, ($mostPopularService?->appointments_count ?? 0)]),

            Stat::make('Service Revenue', CurrencyHelper::symbol() . number_format($serviceRevenueValue, 2))
                ->description('This month from services')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart(array_fill(0, 7, $serviceRevenueValue / 7)),
        ];
    }
}
