<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('father_name')
                            ->label('Father Name')
                            ->maxLength(255),
                        Select::make('sex')
                            ->label('Sex')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ]),
                        TextInput::make('age')
                            ->label('Age')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120),
                    ]),

                Section::make('Case Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('x_ray_id')
                            ->label('X-ray ID')
                            ->maxLength(255),
                        TextInput::make('doctor_name')
                            ->label('Doctor Name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('treatment')
                            ->label('Treatment')
                            ->columnSpanFull(),
                        CanvasPointerField::make('diagnosis')
                            ->label('Diagnosis (image with points)')
                            ->storageDisk('public')
                            ->storageDirectory('canvas-pointer')
                            ->pointRadius(6)
                            ->columnSpanFull(),
                        RichEditor::make('comment')
                            ->label('Comment')
                            ->columnSpanFull(),
                        FileUpload::make('images')
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
            ->recordActions([
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
            // Temporarily disabled due to Filament v4 compatibility issues
            // \App\Filament\Resources\PatientResource\RelationManagers\InvoicesRelationManager::class,
            // \App\Filament\Resources\PatientResource\RelationManagers\PaymentsRelationManager::class,
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


