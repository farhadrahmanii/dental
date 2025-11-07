<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Helpers\CurrencyHelper;
use App\Models\Payment;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil')
                ->color('warning'),
            
            Actions\Action::make('collectPayment')
                ->label('Collect Payment')
                ->icon('heroicon-o-currency-dollar')
                ->color('success')
                ->form([
                    TextInput::make('amount')
                        ->label('Payment Amount')
                        ->numeric()
                        ->prefix(CurrencyHelper::symbol())
                        ->required()
                        ->minValue(0.01)
                        ->placeholder('0.00')
                        ->autofocus(),
                    
                    Select::make('payment_method')
                        ->label('Payment Method')
                        ->options([
                            'cash' => 'Cash',
                            'card' => 'Card',
                            'bank_transfer' => 'Bank Transfer',
                            'check' => 'Check',
                            'other' => 'Other',
                        ])
                        ->required()
                        ->default('cash')
                        ->native(false),
                    
                    DatePicker::make('payment_date')
                        ->label('Payment Date')
                        ->required()
                        ->default(now())
                        ->native(false),
                    
                    TextInput::make('reference_number')
                        ->label('Reference Number')
                        ->maxLength(255)
                        ->placeholder('Optional reference number'),
                    
                    Textarea::make('notes')
                        ->label('Notes')
                        ->placeholder('Add any notes about this payment...')
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    Payment::create([
                        'patient_id' => $this->record->register_id,
                        'amount' => $data['amount'],
                        'payment_method' => $data['payment_method'],
                        'payment_date' => $data['payment_date'],
                        'reference_number' => $data['reference_number'] ?? null,
                        'notes' => $data['notes'] ?? null,
                    ]);

                    Notification::make()
                        ->title('Payment Recorded Successfully')
                        ->success()
                        ->body('Payment of ' . CurrencyHelper::symbol() . number_format($data['amount'], 2) . ' has been recorded for ' . $this->record->name)
                        ->send();
                })
                ->modalHeading('Collect Payment from Patient')
                ->modalDescription('Record a new payment for this patient')
                ->modalSubmitActionLabel('Record Payment')
                ->modalWidth('lg'),
            
            Actions\Action::make('viewFinancials')
                ->label('View Financials')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->url(fn () => PatientResource::getUrl('view', ['record' => $this->record->register_id]) . '#payments')
                ->outlined(),
            
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
