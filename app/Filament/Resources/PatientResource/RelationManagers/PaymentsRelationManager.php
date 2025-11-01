<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Payments';

    protected static ?string $modelLabel = 'Payment';

    protected static ?string $pluralModelLabel = 'Payments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'treatment' => 'Treatment',
                        'xray' => 'X-ray',
                        'other' => 'Other',
                    ])
                    ->required()
                    ->default('treatment'),
                TextInput::make('amount')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                Select::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'bank_transfer' => 'Bank Transfer',
                        'check' => 'Check',
                        'other' => 'Other',
                    ])
                    ->required(),
                DatePicker::make('payment_date')
                    ->required(),
                TextInput::make('reference_number')
                    ->maxLength(255),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'treatment' => 'success',
                        'xray' => 'info',
                        'other' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'treatment' => 'Treatment',
                        'xray' => 'X-ray',
                        'other' => 'Other',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'cash' => 'success',
                        'card' => 'primary',
                        'bank_transfer' => 'info',
                        'check' => 'warning',
                        'other' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice.invoice_number')
                    ->label('Invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'bank_transfer' => 'Bank Transfer',
                        'check' => 'Check',
                        'other' => 'Other',
                    ]),
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
