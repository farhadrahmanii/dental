<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\AppointmentStatsWidget;
use App\Filament\Widgets\PatientStatsWidget;
use App\Filament\Widgets\RevenueStatsWidget;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            QuickActionsWidget::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            QuickActionsWidget::class,
            AppointmentStatsWidget::class,
            PatientStatsWidget::class,
            RevenueStatsWidget::class,
        ];
    }
}
