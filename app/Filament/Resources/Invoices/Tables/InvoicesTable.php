<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DatePicker;
use App\Helpers\CurrencyHelper;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->tooltip('Click to copy invoice number'),
                
                TextColumn::make('patient.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->tooltip('Click to copy patient name'),
                
                TextColumn::make('invoice_date')
                    ->label('Invoice Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->color('primary'),
                
                TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->color(fn ($record) => $record->due_date < now() ? 'danger' : 'success'),
                
                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->formatStateUsing(fn ($state) => CurrencyHelper::format($state))
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd(),
                
                TextColumn::make('balance')
                    ->label('Balance')
                    ->formatStateUsing(fn ($state) => CurrencyHelper::format($state))
                    ->sortable()
                    ->weight('bold')
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success')
                    ->alignEnd(),
                
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'pending',
                        'danger' => 'overdue',
                        'secondary' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid' => 'Paid',
                        'pending' => 'Pending',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M d, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'paid' => 'Paid',
                        'pending' => 'Pending',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),
                
                Filter::make('invoice_date')
                    ->label('Invoice Date')
                    ->form([
                        DatePicker::make('value')
                            ->label('Invoice Date')
                            ->displayFormat('M d, Y'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn ($query, $date) => $query->whereDate('invoice_date', $date),
                        );
                    }),
                
                Filter::make('due_date')
                    ->label('Due Date')
                    ->form([
                        DatePicker::make('value')
                            ->label('Due Date')
                            ->displayFormat('M d, Y'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn ($query, $date) => $query->whereDate('due_date', $date),
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
                            '0-100' => $query->whereBetween('total_amount', [0, 100]),
                            '100-500' => $query->whereBetween('total_amount', [100, 500]),
                            '500-1000' => $query->whereBetween('total_amount', [500, 1000]),
                            '1000+' => $query->where('total_amount', '>', 1000),
                        };
                    }),
                
                TernaryFilter::make('overdue')
                    ->label('Overdue')
                    ->placeholder('All invoices')
                    ->trueLabel('Overdue only')
                    ->falseLabel('Not overdue')
                    ->query(function ($query, array $data) {
                        if ($data['value'] === true) {
                            return $query->where('due_date', '<', now())->where('status', '!=', 'paid');
                        } elseif ($data['value'] === false) {
                            return $query->where(function ($q) {
                                $q->where('due_date', '>=', now())->orWhere('status', 'paid');
                            });
                        }
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('View Invoice'),
                EditAction::make()
                    ->label('Edit Invoice'),
                DeleteAction::make()
                    ->label('Delete Invoice')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('invoice_date', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
