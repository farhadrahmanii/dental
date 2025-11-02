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
}

