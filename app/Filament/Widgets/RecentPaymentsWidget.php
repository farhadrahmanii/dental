<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;

class RecentPaymentsWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Recent Payments';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()->latest('payment_date')->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'card' => 'info',
                        'bank_transfer' => 'primary',
                        'check' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'bank_transfer' => 'Bank Transfer',
                        'check' => 'Check',
                    ]),
            ])
            ->defaultSort('payment_date', 'desc')
            ->paginated(false)
            ->headerActions([
                CreateAction::make()
                    ->label('Add New Payment')
                    ->modalWidth('lg')
                    ->modalHeading('Record New Payment')
                    ->modalSubmitActionLabel('Record Payment')
                    ->modalCancelActionLabel('Cancel')
                    ->form([
                        \Filament\Forms\Components\Select::make('patient_id')
                            ->label('Patient')
                            ->relationship('patient', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        \Filament\Forms\Components\Select::make('service_id')
                            ->label('Service')
                            ->relationship('service', 'name_en')
                            ->searchable()
                            ->preload()
                            ->helperText('Select the dental service performed for this payment'),
                        
                        \Filament\Forms\Components\Select::make('invoice_id')
                            ->label('Invoice')
                            ->relationship('invoice', 'invoice_number')
                            ->searchable()
                            ->preload()
                            ->helperText('Optional: Link to an existing invoice'),
                        
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->prefix('؋')
                            ->required()
                            ->minValue(0.01),
                        
                        \Filament\Forms\Components\Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'bank_transfer' => 'Bank Transfer',
                                'check' => 'Check',
                                'other' => 'Other',
                            ])
                            ->required(),
                        
                        \Filament\Forms\Components\DatePicker::make('payment_date')
                            ->label('Payment Date')
                            ->default(now())
                            ->required(),
                        
                        \Filament\Forms\Components\TextInput::make('reference_number')
                            ->label('Reference Number')
                            ->maxLength(255)
                            ->helperText('Transaction ID, check number, etc.'),
                        
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Additional notes about this payment'),
                    ])
                    ->after(function () {
                        // Refresh the widget after creating a payment
                        $this->dispatch('$refresh');
                    }),
            ]);
    }
}
