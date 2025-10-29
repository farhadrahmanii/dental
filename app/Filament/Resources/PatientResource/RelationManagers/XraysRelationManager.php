<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class XraysRelationManager extends RelationManager
{
    protected static string $relationship = 'xrays';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\FileUpload::make('xray_image')
                    ->label('X-ray Image')
                    ->image()
                    ->directory('xrays')
                    ->disk('public')
                    ->imageEditor()
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('treatment')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('doctor_name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('comment')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('treatment')
            ->columns([
                Tables\Columns\ImageColumn::make('xray_image')
                    ->label('X-ray')
                    ->disk('public')
                    ->height(100)
                    ->width(100),
                Tables\Columns\TextColumn::make('treatment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('doctor_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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