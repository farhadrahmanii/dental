<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;

class AppointmentStatusChartWidget extends ChartWidget
{
    protected ?string $heading = 'Appointment Status Distribution';
    
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $statuses = ['scheduled', 'confirmed', 'completed', 'cancelled', 'no-show'];
        $data = [];
        
        foreach ($statuses as $status) {
            $count = Appointment::where('status', $status)->count();
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Appointments by Status',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(59, 130, 246)', // Blue for scheduled
                        'rgb(16, 185, 129)', // Green for confirmed
                        'rgb(34, 197, 94)',  // Green for completed
                        'rgb(239, 68, 68)',  // Red for cancelled
                        'rgb(245, 158, 11)', // Orange for no-show
                    ],
                    'borderColor' => [
                        'rgb(37, 99, 235)',
                        'rgb(5, 150, 105)',
                        'rgb(22, 163, 74)',
                        'rgb(220, 38, 38)',
                        'rgb(217, 119, 6)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Scheduled', 'Confirmed', 'Completed', 'Cancelled', 'No Show'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                    ],
                ],
            ],
        ];
    }
}
