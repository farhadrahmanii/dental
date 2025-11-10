<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Helpers\CurrencyHelper;
use App\Models\Patient;
use App\Enums\DentalTreatment;
use App\Enums\ToothNumber;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use RuelLuna\CanvasPointer\Forms\Components\CanvasPointerField;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.patients');
    }
    
    public static function getModelLabel(): string
    {
        return __('filament.patient');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('filament.patients');
    }

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('filament.patient_information'))
                    ->description(__('filament.basic_patient_information'))
                    ->icon('heroicon-o-user')
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament.full_name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.enter_patient_full_name'))
                            ->prefixIcon('heroicon-o-user'),
                        TextInput::make('father_name')
                            ->label(__('filament.father_name'))
                            ->maxLength(255)
                            ->placeholder(__('filament.enter_father_name'))
                            ->prefixIcon('heroicon-o-user-group'),
                        TextInput::make('age')
                            ->label(__('filament.age'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120)
                            ->placeholder(__('filament.enter_age'))
                            ->suffix(__('filament.years'))
                            ->prefixIcon('heroicon-o-calendar'),
                        Select::make('sex')
                            ->label(__('filament.gender'))
                            ->options([
                                'male' => __('filament.male'),
                                'female' => __('filament.female'),
                                'other' => __('filament.other'),
                            ])
                            ->native(false)
                            ->prefixIcon('heroicon-o-user'),
                        TextInput::make('phone_number')
                            ->label(__('filament.phone_number'))
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('+93 XXX XXX XXX')
                            ->prefixIcon('heroicon-o-phone'),
                        Select::make('marital_status')
                            ->label(__('filament.marital_status'))
                            ->options([
                                'single' => __('filament.single'),
                                'married' => __('filament.married'),
                                'divorced' => __('filament.divorced'),
                                'widowed' => __('filament.widowed'),
                            ])
                            ->native(false)
                            ->nullable()
                            ->prefixIcon('heroicon-o-heart'),
                        TextInput::make('occupation')
                            ->label(__('filament.occupation'))
                            ->maxLength(255)
                            ->placeholder(__('filament.enter_occupation'))
                            ->prefixIcon('heroicon-o-briefcase')
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
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder(__('filament.enter_permanent_address')),
                        Textarea::make('current_address')
                            ->label(__('filament.current_address'))
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder(__('filament.enter_current_address')),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make(__('filament.medical_details'))
                    ->description(__('filament.dental_case_information'))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('images')
                            ->label(__('filament.patient_images_documents'))
                            ->disk('public')
                            ->directory('patients')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->reorderable()
                            ->downloadable()
                            ->openable()
                            ->maxFiles(10)
                            ->columnSpanFull()
                            ->helperText(__('filament.upload_xrays_photos')),
                    ])
                    ->collapsible(),

                Section::make(__('filament.treatments_history'))
                    ->description(__('filament.record_manage_treatments'))
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->schema([
                        Repeater::make('treatments')
                            ->relationship('treatments')
                            ->schema([
                                Select::make('service_id')
                                    ->label(__('filament.service'))
                                    ->relationship('service', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('tooth_numbers')
                                    ->label(__('filament.tooth_numbers'))
                                    ->options(array_combine(ToothNumber::values(), ToothNumber::values()))
                                    ->multiple()
                                    ->required(),
                                DatePicker::make('treatment_date')
                                    ->label(__('filament.treatment_date'))
                                    ->required()
                                    ->default(now()),
                                Textarea::make('treatment_description')
                                    ->label(__('filament.treatment_description'))
                                    ->rows(2),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string =>
                                isset($state['service_id']) && $state['service_id']
                                    ? \App\Models\Service::find($state['service_id'])?->name ?? __('filament.new_treatment')
                                    : __('filament.new_treatment')
                            )
                            ->addActionLabel(__('filament.add_treatment'))
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make(__('filament.payment_records'))
                    ->description(__('filament.track_manage_payments'))
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Repeater::make('payments')
                            ->relationship('payments')
                            ->schema([
                                TextInput::make('amount')
                                    ->label(__('filament.amount'))
                                    ->numeric()
                                    ->prefix(CurrencyHelper::symbol())
                                    ->required(),
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
                                    ->default('cash'),
                                DatePicker::make('payment_date')
                                    ->label(__('filament.payment_date'))
                                    ->required()
                                    ->default(now()),
                                TextInput::make('reference_number')
                                    ->label(__('filament.reference_number'))
                                    ->maxLength(255),
                                Textarea::make('notes')
                                    ->label(__('filament.notes'))
                                    ->rows(2),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string =>
                                isset($state['amount'])
                                    ? CurrencyHelper::symbol() . number_format($state['amount'], 2) . ' - ' . ucfirst($state['payment_method'] ?? __('filament.new_payment'))
                                    : __('filament.new_payment')
                            )
                            ->addActionLabel(__('filament.add_payment'))
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('register_id')
                    ->label(__('filament.register_id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('father_name')
                    ->label(__('filament.father_name'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sex')
                    ->label(__('filament.gender'))
                    ->badge()
                    ->formatStateUsing(fn($state) => match($state) {
                        'male' => __('filament.male'),
                        'female' => __('filament.female'),
                        'other' => __('filament.other'),
                        default => $state,
                    })
                    ->color(fn($state) => match ($state) {
                        'male' => 'info',
                        'female' => 'pink',
                        'other' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('age')
                    ->label(__('filament.age'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label(__('filament.phone'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('occupation')
                    ->label(__('filament.occupation'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('marital_status')
                    ->label(__('filament.marital_status'))
                    ->badge()
                    ->formatStateUsing(fn($state) => match($state) {
                        'single' => __('filament.single'),
                        'married' => __('filament.married'),
                        'divorced' => __('filament.divorced'),
                        'widowed' => __('filament.widowed'),
                        default => $state,
                    })
                    ->color(fn($state) => match ($state) {
                        'single' => 'info',
                        'married' => 'success',
                        'divorced' => 'warning',
                        'widowed' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('total_spent')
                    ->label(__('filament.total_spent'))
                    ->money('AFN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('outstanding_balance')
                    ->label(__('filament.outstanding_balance'))
                    ->money('AFN')
                    ->color(fn($state) => $state > 0 ? 'danger' : 'success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                Action::make('add_treatment')
                    ->label(__('filament.add_treatment'))
                    ->icon('heroicon-o-beaker')
                    ->visible(fn ($record) => $record !== null)
                    ->url(fn($record) => $record ? '/admin/treatments/create?patient_id=' . $record->register_id : '#'),
                Action::make('add_xray')
                    ->label(__('filament.add_xray'))
                    ->icon('heroicon-o-photo')
                    ->visible(fn ($record) => $record !== null)
                    ->url(fn($record) => $record ? url('/admin/patients/' . $record->register_id . '/xrays/create') : '#'),
                Action::make('add_transcription')
                    ->label(__('filament.add_transcription'))
                    ->icon('heroicon-o-document-text')
                    ->modalHeading('Add Transcription')
                    ->visible(fn ($record) => $record !== null)
                    ->form([
                        \Filament\Forms\Components\Textarea::make('transcription_text')
                            ->label(__('filament.transcription_text'))
                            ->required()
                            ->rows(8)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\TextInput::make('recorded_by')
                            ->label(__('filament.recorded_by'))
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\DatePicker::make('date')
                            ->label(__('filament.date'))
                            ->default(now()),
                    ])
                    ->action(function ($record, array $data): void {
                        // Ensure record exists
                        if (!$record || !$record->register_id) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Error')
                                ->body('Patient record not found.')
                                ->send();
                            return;
                        }

                        $last = \App\Models\Transcription::orderBy('id', 'desc')->first();
                        $nextNumber = 1;
                        if ($last && preg_match('/AFG-(\d+)/', $last->transcription_id, $m)) {
                            $nextNumber = ((int) $m[1]) + 1;
                        }
                        $transcriptionId = 'AFG-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                        \App\Models\Transcription::create([
                            'transcription_id' => $transcriptionId,
                            'patient_id' => $record->register_id,
                            'transcription_text' => $data['transcription_text'],
                            'recorded_by' => $data['recorded_by'],
                            'date' => $data['date'] ?? now(),
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Transcription added')
                            ->body('The transcription has been added successfully.')
                            ->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\PatientResource\RelationManagers\InvoicesRelationManager::class,
            \App\Filament\Resources\PatientResource\RelationManagers\PaymentsRelationManager::class,
            \App\Filament\Resources\PatientResource\RelationManagers\TreatmentsRelationManager::class,
            \App\Filament\Resources\PatientResource\RelationManagers\XraysRelationManager::class,
            \App\Filament\Resources\PatientResource\RelationManagers\TranscriptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
            'view' => Pages\ViewPatient::route('/{record}'),
            'create-xray' => Pages\CreateXray::route('/{record}/xrays/create'),
            'create-transcription' => Pages\CreateTranscription::route('/{record}/transcriptions/create'),
        ];
    }
}
