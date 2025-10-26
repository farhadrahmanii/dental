<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use App\Helpers\CurrencyHelper;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->tooltip('Click to copy patient name'),

                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable()
                    ->placeholder('No service')
                    ->color('info'),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn($state) => CurrencyHelper::format($state))
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd(),

                TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'cash' => 'success',
                        'card' => 'info',
                        'bank_transfer' => 'warning',
                        'check' => 'gray',
                        'other' => 'secondary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'bank_transfer' => 'Bank Transfer',
                        'check' => 'Check',
                        'other' => 'Other',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->sortable(),

                TextColumn::make('payment_date')
                    ->label('Payment Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->color('primary'),

                TextColumn::make('reference_number')
                    ->label('Reference')
                    ->searchable()
                    ->placeholder('No reference')
                    ->copyable()
                    ->tooltip('Click to copy reference number'),

                TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->placeholder('No notes'),

                TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->placeholder('System'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'bank_transfer' => 'Bank Transfer',
                        'check' => 'Check',
                        'other' => 'Other',
                    ])
                    ->multiple(),

                SelectFilter::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Filter::make('payment_date')
                    ->form([
                        DatePicker::make('payment_from')
                            ->label('From Date'),
                        DatePicker::make('payment_until')
                            ->label('Until Date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['payment_from'],
                                fn($query, $date) => $query->whereDate('payment_date', '>=', $date)
                            )
                            ->when(
                                $data['payment_until'],
                                fn($query, $date) => $query->whereDate('payment_date', '<=', $date)
                            );
                    }),

                SelectFilter::make('amount_range')
                    ->label('Amount Range')
                    ->options([
                        '0-100' => '؋0 - ؋100',
                        '100-500' => '؋100 - ؋500',
                        '500-1000' => '؋500 - ؋1,000',
                        '1000+' => '؋1,000+',
                    ])
                    ->query(function ($query, array $data) {
                        if (!$data['value']) return;

                        return match ($data['value']) {
                            '0-100' => $query->whereBetween('amount', [0, 100]),
                            '100-500' => $query->whereBetween('amount', [100, 500]),
                            '500-1000' => $query->whereBetween('amount', [500, 1000]),
                            '1000+' => $query->where('amount', '>', 1000),
                        };
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('View Details'),
                EditAction::make()
                    ->label('Edit Payment'),
                DeleteAction::make()
                    ->label('Delete Payment')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('payment_date', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
