<?php

namespace App\Filament\Widgets;

use App\Helpers\CurrencyHelper;
use App\Models\Expense;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Top Expense Categories (This Month)';
    
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Expense::whereMonth('expense_date', now()->month)
                    ->whereYear('expense_date', now()->year)
                    ->select('expense_type', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
                    ->groupBy('expense_type')
                    ->orderByDesc('total_amount')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('expense_type')
                    ->label('Category')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('count')
                    ->label('Transactions')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('AFN')
                    ->sortable()
                    ->weight('bold')
                    ->color('danger'),
            ])
            ->paginated(false)
            ->poll('60s')
            ->emptyStateHeading('No expenses this month')
            ->emptyStateDescription('No expenses have been recorded for the current month yet.')
            ->emptyStateIcon('heroicon-o-receipt-percent');
    }

    /**
     * Override to handle aggregate query results that don't have model IDs
     */
    public function getTableRecordKey($record): string
    {
        // For aggregated results (stdClass from GROUP BY), use expense_type as the key
        if (is_object($record)) {
            if (isset($record->expense_type)) {
                return (string) $record->expense_type;
            }
            // Try to get ID if it's a model
            if (method_exists($record, 'getKey')) {
                $key = $record->getKey();
                if ($key !== null) {
                    return (string) $key;
                }
            }
        }
        
        // Handle arrays
        if (is_array($record)) {
            if (isset($record['expense_type'])) {
                return (string) $record['expense_type'];
            }
            if (isset($record['id'])) {
                return (string) $record['id'];
            }
        }
        
        // Fallback to parent method for actual model instances
        try {
            return parent::getTableRecordKey($record);
        } catch (\Throwable $e) {
            // Last resort: use a hash or index
            return md5(serialize($record));
        }
    }
}

