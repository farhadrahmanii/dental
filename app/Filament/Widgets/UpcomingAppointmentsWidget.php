<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Widgets\TableWidget;

class UpcomingAppointmentsWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Upcoming Appointments (Next 7 Days)';
    
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Appointment::query()
                    ->where('appointment_date', '>', today())
                    ->where('appointment_date', '<=', today()->addDays(7))
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('appointment_date')
                    ->orderBy('appointment_time')
            )
            ->columns([
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Date')
                    ->date('M d, Y (D)')
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('appointment_time')
                    ->label('Time')
                    ->time('g:i A')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('patient_name')
                    ->label('Patient')
                    ->searchable()
                    ->description(fn ($record) => $record->patient_phone),
                
                Tables\Columns\TextColumn::make('service_name')
                    ->label('Service')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'success' => 'completed',
                    ]),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.appointments.view', $record)),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->poll('60s')
            ->emptyStateHeading('No upcoming appointments')
            ->emptyStateDescription('No appointments scheduled for the next 7 days.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }
}

