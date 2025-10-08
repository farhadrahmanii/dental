<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class AppointmentChartWidget extends ChartWidget
{
    protected static ?int $sort = 7;
    
    protected int | string | array $columnSpan = 'full';

    public function getHeading(): string
    {
        return 'Appointment Trends (Last 30 Days)';
    }

    protected function getData(): array
    {
        // Get data for last 30 days
        $data = [];
        $labels = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
            $data[] = Appointment::whereDate('appointment_date', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Appointments per day',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}

