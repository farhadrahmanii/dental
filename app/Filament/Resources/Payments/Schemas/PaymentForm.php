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
                    ->label(__('filament.patient'))
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->placeholder(__('filament.select_patient'))
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.patient_full_name')),
                        TextInput::make('father_name')
                            ->maxLength(255)
                            ->placeholder(__('filament.enter_father_name')),
                        Select::make('sex')
                            ->options([
                                'male' => __('filament.male'),
                                'female' => __('filament.female'),
                            ])
                            ->required()
                            ->placeholder(__('filament.gender')),
                        TextInput::make('age')
                            ->numeric()
                            ->required()
                            ->placeholder(__('filament.enter_age')),
                        TextInput::make('diagnosis')
                            ->maxLength(255)
                            ->placeholder(__('filament.diagnosis')),
                        Textarea::make('comment')
                            ->placeholder(__('filament.additional_comments')),
                        TextInput::make('treatment')
                            ->maxLength(255)
                            ->placeholder(__('filament.treatment_plan')),
                        TextInput::make('doctor_name')
                            ->maxLength(255)
                            ->placeholder(__('filament.doctor_name')),
                    ]),

                Select::make('invoice_id')
                    ->label(__('filament.invoice_optional'))
                    ->relationship('invoice', 'invoice_number')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder(__('filament.select_invoice_optional')),

                Select::make('type')
                    ->label(__('filament.payment_type'))
                    ->options([
                        'treatment' => __('filament.treatment'),
                        'xray' => __('filament.xray'),
                        'other' => __('filament.other'),
                    ])
                    ->required()
                    ->default('treatment')
                    ->native(false)
                    ->preload()
                    ->hint(__('filament.select_payment_for')),

                TextInput::make('amount')
                    ->label(__('filament.payment_amount'))
                    ->numeric()
                    ->prefix(CurrencyHelper::prefix())
                    ->required()
                    ->step(0.01)
                    ->placeholder('0.00'),

                Select::make('payment_method')
                    ->label(__('filament.payment_method'))
                    ->options([
                        'cash' => __('filament.cash'),
                        'card' => __('filament.card'),
                        'bank_transfer' => __('filament.bank_transfer'),
                        'check' => __('filament.check'),
                        'other' => __('filament.other'),
                    ])
                    ->required()
                    ->default('cash')
                    ->placeholder(__('filament.select_payment_method')),

                DatePicker::make('payment_date')
                    ->label(__('filament.payment_date'))
                    ->required()
                    ->default(now())
                    ->displayFormat('M d, Y'),

                TextInput::make('reference_number')
                    ->label(__('filament.reference_number'))
                    ->maxLength(255)
                    ->placeholder(__('filament.reference_number_placeholder')),

                Textarea::make('notes')
                    ->label(__('filament.payment_notes'))
                    ->rows(3)
                    ->placeholder(__('filament.payment_notes_placeholder'))
                    ->columnSpanFull(),
            ]);
    }
}
