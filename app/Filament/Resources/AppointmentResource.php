<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Helpers\CurrencyHelper;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Service;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\Action;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = null;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.appointments');
    }
    
    public static function getModelLabel(): string
    {
        return __('filament.appointment');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('filament.appointments');
    }

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'appointment_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('filament.patient_information'))
                    ->description(__('filament.search_existing_patient'))
                    ->schema([
                        Select::make('patient_id')
                            ->label(__('filament.existing_patient_optional'))
                            ->placeholder(__('filament.search_by_name_phone'))
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('filament.patient_full_name')),
                                TextInput::make('phone_number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('filament.patient_phone_number')),
                                TextInput::make('doctor_name')
                                    ->label(__('filament.doctor_name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('filament.doctor_name')),
                            ])
                            ->createOptionUsing(function (array $data): string {
                                $patient = Patient::create([
                                    'name' => $data['name'],
                                    'phone_number' => $data['phone_number'],
                                    'doctor_name' => $data['doctor_name'],
                                ]);
                                return $patient->register_id;
                            })
                            ->getSearchResultsUsing(function (string $search): array {
                                return Patient::where('name', 'like', "%{$search}%")
                                    ->orWhere('phone_number', 'like', "%{$search}%")
                                    ->orWhere('register_id', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($patient) {
                                        return [$patient->register_id => $patient->name . ' - ' . ($patient->phone_number ?? 'No Phone') . ' (ID: ' . $patient->register_id . ')'];
                                    })
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value): string {
                                if (!$value) {
                                    return '';
                                }
                                $patient = Patient::find($value);
                                return $patient ? $patient->name . ' - ' . ($patient->phone_number ?? 'No Phone') : '';
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $patient = Patient::find($state);
                                    if ($patient) {
                                        $set('patient_name', $patient->name);
                                        $set('patient_phone', $patient->phone_number ?? '');
                                        $latestAppointment = $patient->appointments()->latest()->first();
                                        $set('patient_email', $latestAppointment?->patient_email ?? '');
                                    }
                                }
                            })
                            ->autofocus(fn ($get) => $get('patient_id') !== null)
                            ->helperText(__('filament.select_existing_patient_auto_fill'))
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('patient_name')
                                    ->label(__('filament.patient'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('patient_email')
                                    ->label(__('filament.email'))
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('patient_phone')
                                    ->label(__('filament.phone_number'))
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('filament.appointment_details'))
                    ->description(__('filament.select_service_date_time'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('service_id')
                                    ->label(__('filament.service'))
                                    ->options(function () {
                                        return Service::active()
                                            ->get()
                                            ->mapWithKeys(function ($service) {
                                                $name = $service->name;
                                                // Ensure name is never null or empty
                                                if (empty($name)) {
                                                    $name = "Service #{$service->id}";
                                                }
                                                return [$service->id => (string) $name];
                                            })
                                            ->filter()
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $service = Service::find($state);
                                            if ($service) {
                                                $name = $service->name;
                                                $set('service_name', $name ?: "Service #{$service->id}");
                                            }
                                        }
                                    }),

                                Hidden::make('service_name'),

                                DatePicker::make('appointment_date')
                                    ->label(__('filament.appointment_date'))
                                    ->required()
                                    ->native(false)
                                    ->minDate(now())
                                    ->displayFormat('M d, Y'),

                                TimePicker::make('appointment_time')
                                    ->label(__('filament.appointment_time'))
                                    ->required()
                                    ->seconds(false)
                                    ->minutesStep(30),

                                Select::make('status')
                                    ->label(__('filament.status'))
                                    ->options([
                                        'pending' => __('filament.pending'),
                                        'confirmed' => __('filament.confirmed'),
                                        'completed' => __('filament.completed'),
                                        'cancelled' => __('filament.cancelled'),
                                        'no_show' => __('filament.no_show'),
                                    ])
                                    ->default('pending')
                                    ->required()
                                    ->native(false),
                            ]),

                        Textarea::make('message')
                            ->label(__('filament.appointment_notes'))
                            ->placeholder(__('filament.appointment_notes_placeholder'))
                            ->rows(3)
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label(__('filament.internal_notes'))
                            ->placeholder(__('filament.internal_notes_placeholder'))
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText(__('filament.internal_notes_helper')),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('appointment_number')
                    ->label(__('filament.appt'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('patient_name')
                    ->label(__('filament.patient'))
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => $record->patient?->register_id ? __('filament.id') . ': ' . $record->patient->register_id : __('filament.patient')),

                TextColumn::make('patient_phone')
                    ->label(__('filament.phone'))
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('service_name')
                    ->label(__('filament.service'))
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => $record->service ? CurrencyHelper::format($record->service->price) : ''),

                TextColumn::make('appointment_date')
                    ->label(__('filament.date'))
                    ->date('M d, Y')
                    ->sortable()
                    ->description(fn($record) => $record->formatted_time),

                BadgeColumn::make('status')
                    ->label(__('filament.status'))
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'gray' => 'no_show',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => __('filament.pending'),
                        'confirmed' => __('filament.confirmed'),
                        'completed' => __('filament.completed'),
                        'cancelled' => __('filament.cancelled'),
                        'no_show' => __('filament.no_show'),
                        default => ucfirst($state),
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.status'))
                    ->options([
                        'pending' => __('filament.pending'),
                        'confirmed' => __('filament.confirmed'),
                        'completed' => __('filament.completed'),
                        'cancelled' => __('filament.cancelled'),
                        'no_show' => __('filament.no_show'),
                    ])
                    ->multiple(),

                SelectFilter::make('service_id')
                    ->label(__('filament.service'))
                    ->relationship('service', 'name')
                    ->searchable()
                    ->multiple(),

                Filter::make('appointment_date')
                    ->form([
                        DatePicker::make('from')
                            ->label(__('filament.from_date')),
                        DatePicker::make('until')
                            ->label(__('filament.until_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('appointment_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('appointment_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Action::make('confirm')
                    ->label(__('filament.confirmed'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->update(['status' => 'confirmed']))
                    ->visible(fn($record) => $record->status === 'pending'),
                Action::make('complete')
                    ->label(__('filament.completed'))
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->update(['status' => 'completed']))
                    ->visible(fn($record) => in_array($record->status, ['pending', 'confirmed'])),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('appointment_date', 'desc')
            ->poll('30s');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
