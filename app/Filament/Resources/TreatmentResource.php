<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreatmentResource\Pages;
use App\Models\Treatment;
use App\Models\Patient;
use App\Models\Service;
use App\Enums\ToothNumber;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Model;

class TreatmentResource extends Resource
{
    protected static ?string $model = Treatment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationLabel = 'Treatments';

    protected static ?string $modelLabel = 'Treatment';

    protected static ?string $pluralModelLabel = 'Treatments';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Treatment Information')
                    ->columns(2)
                    ->schema([
                        Select::make('patient_id')
                            ->label('Patient')
                            ->relationship('patient', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => (string) ($record->name ?: "Patient #{$record->register_id}"))
                            ->searchable()
                            ->required()
                            ->visible(fn () => !request()->query('patient_id')),
                        TextInput::make('patient_name')
                            ->label('Patient')
                            ->visible(fn () => request()->query('patient_id'))
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function () {
                                if ($patientId = request()->query('patient_id')) {
                                    $patient = Patient::find($patientId);
                                    return $patient ? $patient->name : '';
                                }
                                return '';
                            }),
                        Hidden::make('patient_id')
                            ->visible(fn () => request()->query('patient_id'))
                            ->dehydrated(true),
                        Select::make('service_id')
                            ->label('Service')
                            ->relationship('service', 'name')
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                $name = $record->name;
                                if (empty($name)) {
                                    // Try to get from raw attributes as fallback
                                    $locale = app()->getLocale();
                                    $fallbackLocale = config('app.fallback_locale', 'en');
                                    $name = $record->getRawOriginal("name_{$locale}") 
                                        ?: $record->getRawOriginal("name_{$fallbackLocale}")
                                        ?: $record->getRawOriginal('name')
                                        ?: "Service #{$record->id}";
                                }
                                return (string) $name; // Ensure it's always a string
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        DatePicker::make('treatment_date')
                            ->label('Treatment Date')
                            ->required(),
                        Select::make('tooth_numbers')
                            ->label('Tooth Numbers')
                            ->options(array_combine(ToothNumber::values(), ToothNumber::values()))
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload(),
                        ViewField::make('dental_chart')
                            ->view('filament.forms.components.dental-chart')
                            ->columnSpanFull(),
                        Textarea::make('treatment_description')
                            ->label('Treatment Description')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('treatment_date')
                    ->label('Treatment Date')
                    ->date()
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
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListTreatments::route('/'),
            'create' => Pages\CreateTreatment::route('/create'),
            'edit' => Pages\EditTreatment::route('/{record}/edit'),
            'view' => Pages\ViewTreatment::route('/{record}'),
        ];
    }
}
