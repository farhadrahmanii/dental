<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TodayAppointmentsWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Today\'s Appointments';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Appointment::query()->whereDate('appointment_date', today())
            )
            ->columns([
                Tables\Columns\TextColumn::make('appointment_number')
                    ->label('Appointment #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient_name')
                    ->label('Patient Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient_phone')
                    ->label('Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_name')
                    ->label('Service')
                    ->searchable(),
                Tables\Columns\TextColumn::make('appointment_time')
                    ->label('Time')
                    ->time('g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'info',
                        'confirmed' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'no-show' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'no-show' => 'No Show',
                    ]),
            ])
            ->defaultSort('appointment_time')
            ->paginated(false);
    }
}
