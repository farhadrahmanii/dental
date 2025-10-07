<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Invoice;
use App\Models\Patient;
use App\Helpers\CurrencyHelper;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('patient_id')
                    ->label('Patient')
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('father_name')
                            ->maxLength(255),
                        Select::make('sex')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ])
                            ->required(),
                        TextInput::make('age')
                            ->numeric()
                            ->required(),
                        TextInput::make('diagnosis')
                            ->maxLength(255),
                        Textarea::make('comment'),
                        TextInput::make('treatment')
                            ->maxLength(255),
                        TextInput::make('doctor_name')
                            ->maxLength(255),
                    ]),

                Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText('Select the dental service performed for this payment'),

                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->prefix(CurrencyHelper::prefix())
                    ->required()
                    ->step(0.01),

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
                    ->default('cash'),

                DatePicker::make('payment_date')
                    ->label('Payment Date')
                    ->required()
                    ->default(now()),

                TextInput::make('reference_number')
                    ->label('Reference Number')
                    ->maxLength(255)
                    ->helperText('Transaction ID, check number, etc.'),

                Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
