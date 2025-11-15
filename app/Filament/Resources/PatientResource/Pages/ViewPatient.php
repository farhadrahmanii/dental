<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Helpers\CurrencyHelper;
use App\Models\Payment;
use App\Enums\ToothNumber;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Storage;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;

    public function form(Schema $schema): Schema
    {
        // Define the form directly, same as PatientResource but with ViewField for diagnosis
        return $schema
            ->schema([
                Section::make(__('filament.patient_information'))
                    ->description(__('filament.basic_patient_information'))
                    ->icon('heroicon-o-user')
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.full_name'))
                            ->disabled(),
                        TextInput::make('father_name')
                            ->label(__('filament.father_name'))
                            ->disabled(),
                        TextInput::make('age')
                            ->label(__('filament.age'))
                            ->disabled(),
                        Select::make('sex')
                            ->label(__('filament.gender'))
                            ->options([
                                'male' => __('filament.male'),
                                'female' => __('filament.female'),
                                'other' => __('filament.other'),
                            ])
                            ->disabled(),
                        TextInput::make('phone_number')
                            ->label(__('filament.phone_number'))
                            ->disabled(),
                        Select::make('marital_status')
                            ->label(__('filament.marital_status'))
                            ->options([
                                'single' => __('filament.single'),
                                'married' => __('filament.married'),
                                'divorced' => __('filament.divorced'),
                                'widowed' => __('filament.widowed'),
                            ])
                            ->disabled(),
                        TextInput::make('occupation')
                            ->label(__('filament.occupation'))
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('filament.address_information'))
                    ->description(__('filament.patient_residential_details'))
                    ->icon('heroicon-o-map-pin')
                    ->columns(2)
                    ->schema([
                        Textarea::make('permanent_address')
                            ->label(__('filament.permanent_address'))
                            ->disabled(),
                        Textarea::make('current_address')
                            ->label(__('filament.current_address'))
                            ->disabled(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make(__('filament.medical_details'))
                    ->description(__('filament.dental_case_information'))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columns(2)
                    ->schema([
                        // Replace CanvasPointerField with ViewField for read-only display
                        ViewField::make('diagnosis')
                            ->label(__('filament.diagnosis'))
                            ->view('filament.forms.components.diagnosis-view')
                            ->columnSpanFull(),
                        Textarea::make('comment')
                            ->label(__('filament.comment'))
                            ->disabled()
                            ->columnSpanFull(),
                        FileUpload::make('images')
                            ->label(__('filament.patient_images_documents'))
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('filament.treatments_history'))
                    ->description(__('filament.record_manage_treatments'))
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('treatments')
                            ->relationship('treatments')
                            ->schema([
                                ViewField::make('service')
                                    ->label(__('filament.service'))
                                    ->view('filament.forms.components.view-service'),
                                ViewField::make('tooth_numbers')
                                    ->label(__('filament.tooth_numbers'))
                                    ->view('filament.forms.components.view-tooth-numbers')
                                    ->columnSpanFull(),
                                DatePicker::make('treatment_date')
                                    ->label(__('filament.treatment_date'))
                                    ->disabled()
                                    ->dehydrated(false),
                                Textarea::make('treatment_description')
                                    ->label(__('filament.treatment_description'))
                                    ->disabled()
                                    ->dehydrated(false),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string =>
                                isset($state['service_id'])
                                    ? \App\Models\Service::find($state['service_id'])?->name ?? __('filament.new_treatment')
                                    : __('filament.new_treatment')
                            )
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make(__('filament.payment_records'))
                    ->description(__('filament.track_manage_payments'))
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('payments')
                            ->relationship('payments')
                            ->schema([
                                TextInput::make('amount')
                                    ->label(__('filament.amount'))
                                    ->disabled(),
                                Select::make('payment_method')
                                    ->label(__('filament.payment_method'))
                                    ->options([
                                        'cash' => __('filament.cash'),
                                        'card' => __('filament.card'),
                                        'bank_transfer' => __('filament.bank_transfer'),
                                        'check' => __('filament.check'),
                                        'other' => __('filament.other'),
                                    ])
                                    ->disabled(),
                                DatePicker::make('payment_date')
                                    ->label(__('filament.payment_date'))
                                    ->disabled(),
                                TextInput::make('reference_number')
                                    ->label(__('filament.reference_number'))
                                    ->disabled(),
                                Textarea::make('notes')
                                    ->label(__('filament.notes'))
                                    ->disabled(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string =>
                                isset($state['amount'])
                                    ? CurrencyHelper::symbol() . number_format($state['amount'], 2) . ' - ' . ucfirst($state['payment_method'] ?? __('filament.new_payment'))
                                    : __('filament.new_payment')
                            )
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

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
