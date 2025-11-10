<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use App\Enums\ToothNumber;
use App\Models\Service;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use RuelLuna\CanvasPointer\Forms\Components\CanvasPointerField;
use Illuminate\Support\Facades\Log;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';

    protected static ?string $title = 'Treatments';

    protected static ?string $modelLabel = 'Treatment';

    protected static ?string $pluralModelLabel = 'Treatments';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('service_id')
                    ->label('Service')
                    ->options(function () {
                        return Service::where('is_active', true)
                            ->orderBy('name_en')
                            ->orderBy('name')
                            ->get()
                            ->mapWithKeys(function ($service) {
                                $name = $service->name;
                                if (empty($name)) {
                                    $locale = app()->getLocale();
                                    $fallbackLocale = config('app.fallback_locale', 'en');
                                    $name = $service->getRawOriginal("name_{$locale}") 
                                        ?: $service->getRawOriginal("name_{$fallbackLocale}")
                                        ?: $service->getRawOriginal('name')
                                        ?: "Service #{$service->id}";
                                }
                                return [(int) $service->id => (string) $name];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->required()
                    ->native(false)
                    ->dehydrated(true)
                    ->default(null)
                    ->placeholder('Select a service...')
                    ->helperText('Please select a service for this treatment.')
                    ->validationMessages([
                        'required' => 'The service field is required. Please select a service.',
                    ])
                    ->rules([
                        'required',
                        'integer',
                        'exists:services,id',
                    ]),
                CanvasPointerField::make('tooth_selection_visual')
                    ->label('Select Teeth on Chart (Visual)')
                    ->imageUrl('/images/dental-chart.jpg')
                    ->width(480)
                    ->height(640)
                    ->pointRadius(15)
                    ->storageDisk('public')
                    ->storageDirectory('treatments-tooth-selection')
                    ->dehydrated()
                    ->columnSpanFull()
                    ->helperText('Click on the teeth to visually mark which teeth are being treated'),
                Select::make('tooth_numbers')
                    ->label('Tooth Numbers (Manual Selection)')
                    ->options(array_combine(ToothNumber::values(), ToothNumber::values()))
                    ->multiple()
                    ->required()
                    ->searchable()
                    ->preload(),
                DatePicker::make('treatment_date')
                    ->label('Treatment Date')
                    ->required(),
                Textarea::make('treatment_description')
                    ->label('Treatment Description')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('service.name')
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tooth_numbers')
                    ->label('Tooth Numbers')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return implode(', ', $state);
                        }
                        return $state;
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('treatment_date')
                    ->label('Treatment Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('treatment_description')
                    ->label('Description')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->beforeFormFilled(function () {
                        // Check if any services exist
                        $servicesCount = Service::where('is_active', true)->count();
                        if ($servicesCount === 0) {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title('No Services Available')
                                ->body('Please create at least one active service before adding treatments.')
                                ->persistent()
                                ->send();
                        }
                    })
                    ->action(function (array $data) {
                        // Get patient
                        $patient = $this->getOwnerRecord();
                        if (!$patient) {
                            throw new \Exception('Patient record not found');
                        }
                        
                        // Log what we receive
                        Log::info('Treatment CreateAction action() - received data', [
                            'data_keys' => array_keys($data),
                            'has_service_id' => isset($data['service_id']),
                            'service_id' => $data['service_id'] ?? 'MISSING',
                            'full_data' => $data,
                        ]);
                        
                        // CRITICAL: Validate and ensure service_id is set
                        if (empty($data['service_id']) || !is_numeric($data['service_id'])) {
                            Log::error('Treatment creation - service_id missing', [
                                'data' => $data,
                                'patient_id' => $patient->register_id,
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Service Required')
                                ->body('Please select a service for this treatment.')
                                ->send();
                            
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'service_id' => ['The service field is required.'],
                            ]);
                        }
                        
                        // Ensure service_id is integer
                        $data['service_id'] = (int) $data['service_id'];
                        
                        // Verify service exists
                        $service = Service::find($data['service_id']);
                        if (!$service || !$service->is_active) {
                            Log::error('Treatment creation - invalid service', [
                                'service_id' => $data['service_id'],
                            ]);
                            
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'service_id' => ['The selected service is not available.'],
                            ]);
                        }
                        
                        // Ensure patient_id is set
                        $data['patient_id'] = $patient->register_id;
                        
                        // Log before creation
                        Log::info('Treatment creation - creating with data', [
                            'service_id' => $data['service_id'],
                            'patient_id' => $data['patient_id'],
                            'all_keys' => array_keys($data),
                        ]);
                        
                        // Create treatment
                        $treatment = \App\Models\Treatment::create($data);
                        
                        Log::info('Treatment created successfully', [
                            'treatment_id' => $treatment->id,
                            'service_id' => $treatment->service_id,
                        ]);
                        
                        return $treatment;
                    }),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
