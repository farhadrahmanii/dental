<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Invoice;
use App\Models\Patient;
use App\Helpers\CurrencyHelper;
use Filament\Schemas\Components\DatePicker;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('patient_id')
                    ->label('Patient')
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->placeholder('Select a patient')
                    ->helperText('Choose the patient who made the payment')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Patient full name'),
                        TextInput::make('father_name')
                            ->maxLength(255)
                            ->placeholder('Father\'s name'),
                        Select::make('sex')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ])
                            ->required()
                            ->placeholder('Select gender'),
                        TextInput::make('age')
                            ->numeric()
                            ->required()
                            ->placeholder('Age'),
                        TextInput::make('diagnosis')
                            ->maxLength(255)
                            ->placeholder('Medical diagnosis'),
                        Textarea::make('comment')
                            ->placeholder('Additional comments'),
                        TextInput::make('treatment')
                            ->maxLength(255)
                            ->placeholder('Treatment plan'),
                        TextInput::make('doctor_name')
                            ->maxLength(255)
                            ->placeholder('Doctor\'s name'),
                    ]),

                Select::make('invoice_id')
                    ->label('Invoice (Optional)')
                    ->relationship('invoice', 'invoice_number')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder('Select invoice (optional)')
                    ->helperText('Link this payment to a specific invoice'),

                Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder('Select service (optional)')
                    ->helperText('Select the dental service performed for this payment'),

                TextInput::make('amount')
                    ->label('Payment Amount')
                    ->numeric()
                    ->prefix(CurrencyHelper::prefix())
                    ->required()
                    ->step(0.01)
                    ->placeholder('0.00')
                    ->helperText('Enter the payment amount in Afghani'),

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
                    ->placeholder('Select payment method')
                    ->helperText('Choose how the payment was made'),

                DatePicker::make('payment_date')
                    ->label('Payment Date')
                    ->required()
                    ->default(now())
                    ->displayFormat('M d, Y')
                    ->helperText('Date when the payment was received'),

                TextInput::make('reference_number')
                    ->label('Reference Number')
                    ->maxLength(255)
                    ->placeholder('Transaction ID, check number, etc.')
                    ->helperText('Enter transaction reference if available'),

                Textarea::make('notes')
                    ->label('Payment Notes')
                    ->rows(3)
                    ->placeholder('Add any additional notes about this payment')
                    ->helperText('Optional notes about the payment')
                    ->columnSpanFull(),
            ]);
    }
}
