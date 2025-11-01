<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
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

    protected static ?string $navigationLabel = 'Patients';

    protected static ?string $modelLabel = 'Patient';

    protected static ?string $pluralModelLabel = 'Patients';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Patient Information')
                    ->description('Basic patient information and demographics')
                    ->icon('heroicon-o-user')
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter patient full name')
                            ->prefixIcon('heroicon-o-user'),
                        TextInput::make('father_name')
                            ->label('Father Name')
                            ->maxLength(255)
                            ->placeholder('Enter father name')
                            ->prefixIcon('heroicon-o-user-group'),
                        TextInput::make('age')
                            ->label('Age')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120)
                            ->placeholder('Enter age')
                            ->suffix('years')
                            ->prefixIcon('heroicon-o-calendar'),
                        Select::make('sex')
                            ->label('Gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ])
                            ->native(false)
                            ->prefixIcon('heroicon-o-user'),
                        TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('+93 XXX XXX XXX')
                            ->prefixIcon('heroicon-o-phone'),
                        Select::make('marital_status')
                            ->label('Marital Status')
                            ->options([
                                'single' => 'Single',
                                'married' => 'Married',
                                'divorced' => 'Divorced',
                                'widowed' => 'Widowed',
                            ])
                            ->native(false)
                            ->nullable()
                            ->prefixIcon('heroicon-o-heart'),
                        TextInput::make('occupation')
                            ->label('Occupation')
                            ->maxLength(255)
                            ->placeholder('Enter occupation or job title')
                            ->prefixIcon('heroicon-o-briefcase')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Address Information')
                    ->description('Patient residential details')
                    ->icon('heroicon-o-map-pin')
                    ->columns(2)
                    ->schema([
                        Textarea::make('permanent_address')
                            ->label('Permanent Address')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Enter permanent residence address'),
                        Textarea::make('current_address')
                            ->label('Current Address')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Enter current residence address'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Medical Details')
                    ->description('Dental case information and diagnosis')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('images')
                            ->label('Patient Images & Documents')
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
                            ->helperText('Upload X-rays, photos, or other medical documents'),
                    ])
                    ->collapsible(),

                Section::make('Treatments History')
                    ->description('Record and manage patient treatments')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->schema([
                        Repeater::make('treatments')
                            ->relationship('treatments')
                            ->schema([
                                Select::make('treatment_types')
                                    ->label('Treatment Types')
                                    ->options(array_combine(DentalTreatment::values(), DentalTreatment::values()))
                                    ->multiple()
                                    ->required(),
                                Select::make('tooth_numbers')
                                    ->label('Tooth Numbers')
                                    ->options(array_combine(ToothNumber::values(), ToothNumber::values()))
                                    ->multiple()
                                    ->required(),
                                DatePicker::make('treatment_date')
                                    ->label('Treatment Date')
                                    ->required()
                                    ->default(now()),
                                Textarea::make('treatment_description')
                                    ->label('Treatment Description')
                                    ->rows(2),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => 
                                isset($state['treatment_types']) && is_array($state['treatment_types']) 
                                    ? implode(', ', $state['treatment_types']) 
                                    : 'New Treatment'
                            )
                            ->addActionLabel('Add Treatment')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Payment Records')
                    ->description('Track and manage patient payments')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Repeater::make('payments')
                            ->relationship('payments')
                            ->schema([
                                TextInput::make('amount')
                                    ->label('Amount')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
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
                                    ->maxLength(255),
                                Textarea::make('notes')
                                    ->label('Notes')
                                    ->rows(2),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => 
                                isset($state['amount']) 
                                    ? '$' . number_format($state['amount'], 2) . ' - ' . ucfirst($state['payment_method'] ?? 'New Payment')
                                    : 'New Payment'
                            )
                            ->addActionLabel('Add Payment')
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
                    ->label('Register ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('father_name')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sex')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'male' => 'info',
                        'female' => 'pink',
                        'other' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('age')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('occupation')
                    ->label('Occupation')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('marital_status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'single' => 'info',
                        'married' => 'success',
                        'divorced' => 'warning',
                        'widowed' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Total Spent')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('outstanding_balance')
                    ->label('Outstanding Balance')
                    ->money('USD')
                    ->color(fn($state) => $state > 0 ? 'danger' : 'success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                Action::make('add_treatment')
                    ->label('Add Treatment')
                    ->icon('heroicon-o-beaker')
                    ->url(fn($record) => '/admin/treatments/create?patient_id=' . $record->register_id),
                Action::make('add_xray')
                    ->label('Add X-ray')
                    ->icon('heroicon-o-photo')
                    ->url(fn($record) => url('/admin/patients/' . $record->register_id . '/xrays/create')),
                Action::make('add_transcription')
                    ->label('Add Transcription')
                    ->icon('heroicon-o-document-text')
                    ->modalHeading('Add Transcription')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('transcription_text')
                            ->label('Transcription Text')
                            ->required()
                            ->rows(8)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\TextInput::make('recorded_by')
                            ->label('Recorded By')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\DatePicker::make('date')
                            ->label('Date')
                            ->default(now()),
                    ])
                    ->action(function ($record, array $data): void {
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
