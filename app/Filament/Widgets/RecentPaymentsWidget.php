<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentPaymentsWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Recent Payments';
    
    protected static ?int $sort = 6;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->latest('payment_date')
                    ->latest('created_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Patient')
                    ->searchable()
                    ->description(fn ($record) => $record->patient?->register_id ? 'ID: ' . $record->patient->register_id : 'N/A'),
                
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->default(fn ($record) => $record->appointment?->service_name ?? 'N/A'),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('usd')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'card' => 'info',
                        'insurance' => 'warning',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('appointment.appointment_number')
                    ->label('Appointment #')
                    ->searchable()
                    ->toggleable(),
            ])
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5)
            ->poll('60s')
            ->emptyStateHeading('No payments recorded')
            ->emptyStateDescription('No payment transactions have been recorded yet.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }
}
