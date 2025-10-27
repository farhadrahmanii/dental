<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use App\Enums\DentalTreatment;
use App\Enums\ToothNumber;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;

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
                    ->required(),
                Textarea::make('treatment_description')
                    ->label('Treatment Description')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('treatment_types')
            ->columns([
                Tables\Columns\TextColumn::make('treatment_types')
                    ->label('Treatment Types')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return implode(', ', $state);
                        }
                        return $state;
                    })
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
                Actions\CreateAction::make(),
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
