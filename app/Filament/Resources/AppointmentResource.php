<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Service;
use Filament\Schemas\Components\DatePicker;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Hidden;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Appointments';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'appointment_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Patient Information')
                    ->description('Search for existing patient or enter new patient details')
                    ->schema([
                        Select::make('patient_id')
                            ->label('Existing Patient (Optional)')
                            ->placeholder('Search by name or phone...')
                            ->searchable()
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
                            ->getOptionLabelUsing(function ($value): ?string {
                                $patient = Patient::find($value);
                                return $patient ? $patient->name . ' - ' . ($patient->phone_number ?? 'No Phone') : null;
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
                            ->helperText('Select an existing patient to auto-fill their details')
                            ->columnSpanFull(),
                        
                        Grid::make(3)
                            ->schema([
                                TextInput::make('patient_name')
                                    ->label('Patient Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('patient_email')
                                    ->label('Email Address')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('patient_phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Appointment Details')
                    ->description('Select service, date, and time for the appointment')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('service_id')
                                    ->label('Service')
                                    ->options(Service::active()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $service = Service::find($state);
                                            if ($service) {
                                                $set('service_name', $service->name);
                                            }
                                        }
                                    }),
                                
                                Hidden::make('service_name'),
                                
                                DatePicker::make('appointment_date')
                                    ->label('Appointment Date')
                                    ->required()
                                    ->native(false)
                                    ->minDate(now())
                                    ->displayFormat('M d, Y'),
                                
                                TimePicker::make('appointment_time')
                                    ->label('Appointment Time')
                                    ->required()
                                    ->seconds(false)
                                    ->minutesStep(30),
                                
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                        'no_show' => 'No Show',
                                    ])
                                    ->default('pending')
                                    ->required()
                                    ->native(false),
                            ]),
                        
                        Textarea::make('message')
                            ->label('Patient Message')
                            ->placeholder('Any message or special requests from the patient...')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Textarea::make('notes')
                            ->label('Internal Notes (Staff Only)')
                            ->placeholder('Internal notes visible only to staff...')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('These notes are only visible to staff members'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('appointment_number')
                    ->label('Appt #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),
                
                TextColumn::make('patient_name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->patient?->register_id ? 'ID: ' . $record->patient->register_id : 'New Patient'),
                
                TextColumn::make('patient_phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('service_name')
                    ->label('Service')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->service ? '$' . number_format($record->service->price, 2) : ''),
                
                TextColumn::make('appointment_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->description(fn ($record) => $record->formatted_time),
                
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'gray' => 'no_show',
                    ])
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'no_show' => 'No Show',
                    ])
                    ->multiple(),
                
                SelectFilter::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->multiple(),
                
                Filter::make('appointment_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('From Date'),
                        DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('appointment_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('appointment_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['status' => 'confirmed']))
                    ->visible(fn ($record) => $record->status === 'pending'),
                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['status' => 'completed']))
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'confirmed'])),
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
