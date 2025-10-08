<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Service;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
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
                    ->schema([
                        Select::make('patient_id')
                            ->label('Existing Patient (Optional)')
                            ->placeholder('Search by name or phone...')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search): array {
                                return Patient::where('name', 'like', "%{$search}%")
                                    ->orWhere('phone_number', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($patient) {
                                        return [$patient->register_id => $patient->name . ' - ' . ($patient->phone_number ?? 'No Phone')];
                                    })
                                    ->toArray();
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
                            ->columnSpanFull(),
                        
                        Grid::make(3)
                            ->schema([
                                TextInput::make('patient_name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('patient_email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('patient_phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Appointment Details')
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
                                    ->required()
                                    ->native(false)
                                    ->minDate(now()),
                                
                                TimePicker::make('appointment_time')
                                    ->required()
                                    ->seconds(false),
                                
                                Select::make('status')
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
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Textarea::make('notes')
                            ->label('Internal Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
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
                    ->copyable(),
                
                TextColumn::make('patient_name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('patient_phone')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('service_name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('appointment_date')
                    ->date('M d, Y')
                    ->sortable(),
                
                TextColumn::make('appointment_time')
                    ->time('h:i A'),
                
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'gray' => 'no_show',
                    ])
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
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
                    ]),
                
                SelectFilter::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name'),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('appointment_date', 'desc');
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

