<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use App\Models\Transcription;
use Illuminate\Support\Str;

class TranscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transcriptions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Textarea::make('transcription_text')
                    ->label('Transcription Text')
                    ->required()
                    ->rows(10)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('recorded_by')
                    ->label('Recorded By')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->default(now())
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('transcription_id')
            ->columns([
                Tables\Columns\TextColumn::make('transcription_id')
                    ->label('Transcription ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transcription_text')
                    ->label('Transcription')
                    ->limit(100)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('recorded_by')
                    ->label('Recorded By')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Auto-generate transcription_id in AFG-0001 format
                        $lastTranscription = Transcription::orderBy('id', 'desc')->first();
                        $nextNumber = 1;
                        
                        if ($lastTranscription && preg_match('/AFG-(\d+)/', $lastTranscription->transcription_id, $matches)) {
                            $nextNumber = (int)$matches[1] + 1;
                        }
                        
                        $data['transcription_id'] = 'AFG-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                        return $data;
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
