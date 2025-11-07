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
                    ->label(__('filament.patient'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->tooltip(__('filament.patient')),

                TextColumn::make('type')
                    ->label(__('filament.type'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'treatment' => 'success',
                        'xray' => 'info',
                        'other' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'treatment' => __('filament.treatment'),
                        'xray' => __('filament.xray'),
                        'other' => __('filament.other'),
                        default => ucfirst($state),
                    })
                    ->sortable(),

                TextColumn::make('amount')
                    ->label(__('filament.amount'))
                    ->formatStateUsing(fn($state) => CurrencyHelper::format($state))
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd(),

                TextColumn::make('payment_method')
                    ->label(__('filament.method'))
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
                        'cash' => __('filament.cash'),
                        'card' => __('filament.card'),
                        'bank_transfer' => __('filament.bank_transfer'),
                        'check' => __('filament.check'),
                        'other' => __('filament.other'),
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->sortable(),

                TextColumn::make('payment_date')
                    ->label(__('filament.payment_date'))
                    ->date('M d, Y')
                    ->sortable()
                    ->color('primary'),

                TextColumn::make('reference_number')
                    ->label(__('filament.reference'))
                    ->searchable()
                    ->placeholder(__('filament.no_reference'))
                    ->copyable()
                    ->tooltip(__('filament.reference_number')),

                TextColumn::make('notes')
                    ->label(__('filament.notes'))
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->placeholder(__('filament.no_notes')),

                TextColumn::make('createdBy.name')
                    ->label(__('filament.created_by'))
                    ->searchable()
                    ->sortable()
                    ->placeholder(__('filament.system')),

                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime('M d, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('payment_method')
                    ->label(__('filament.payment_method'))
                    ->options([
                        'cash' => __('filament.cash'),
                        'card' => __('filament.card'),
                        'bank_transfer' => __('filament.bank_transfer'),
                        'check' => __('filament.check'),
                        'other' => __('filament.other'),
                    ])
                    ->multiple(),

                SelectFilter::make('type')
                    ->label(__('filament.type'))
                    ->options([
                        'treatment' => __('filament.treatment'),
                        'xray' => __('filament.xray'),
                        'other' => __('filament.other'),
                    ])
                    ->multiple(),

                Filter::make('payment_date')
                    ->form([
                        DatePicker::make('payment_from')
                            ->label(__('filament.from_date')),
                        DatePicker::make('payment_until')
                            ->label(__('filament.until_date')),
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
                    ->label(__('filament.amount_range'))
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
                    ->label(__('filament.view_details')),
                EditAction::make()
                    ->label(__('filament.edit_payment')),
                DeleteAction::make()
                    ->label(__('filament.delete_payment'))
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('filament.delete_selected'))
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('payment_date', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
