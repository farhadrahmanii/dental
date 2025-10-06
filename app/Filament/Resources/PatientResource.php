<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RuelLuna\CanvasPointer\Forms\Components\CanvasPointerField;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user';

   

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Patient Information')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('father_name')
                            ->label('Father Name')
                            ->maxLength(255),
                        Forms\Components\Select::make('sex')
                            ->label('Sex')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ]),
                        Forms\Components\TextInput::make('age')
                            ->label('Age')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120),
                    ]),

                Section::make('Case Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('x_ray_id')
                            ->label('X-ray ID')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('doctor_name')
                            ->label('Doctor Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('treatment')
                            ->label('Treatment')
                            ->columnSpanFull(),
                        CanvasPointerField::make('diagnosis')
                            ->label('Diagnosis (image with points)')
                            ->storageDisk('public')
                            ->storageDirectory('canvas-pointer')
                            ->pointRadius(6)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('comment')
                            ->label('Comment')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('images')
                            ->label('Images')
                            ->disk('public')
                            ->directory('patients')
                            ->multiple()
                            ->image()
                            ->reorderable()
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                    ]),
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
                    ->color(fn ($state) => match ($state) {
                        'male' => 'info',
                        'female' => 'pink',
                        'other' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('age')
                    ->sortable(),
                Tables\Columns\TextColumn::make('x_ray_id')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('doctor_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Total Spent')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('outstanding_balance')
                    ->label('Outstanding Balance')
                    ->money('USD')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->filters([
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

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\PatientResource\RelationManagers\InvoicesRelationManager::class,
            \App\Filament\Resources\PatientResource\RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
            'view' => Pages\ViewPatient::route('/{record}'),
        ];
    }
}


