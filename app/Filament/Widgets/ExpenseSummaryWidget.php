<?php

namespace App\Filament\Widgets;

use App\Helpers\CurrencyHelper;
use App\Models\Expense;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ExpenseSummaryWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        // Total expenses (all time)
        $totalExpenses = Expense::sum('amount');
        
        // This month expenses
        $monthlyExpenses = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');
        
        // Last month expenses for comparison
        $lastMonthExpenses = Expense::whereMonth('expense_date', now()->subMonth()->month)
            ->whereYear('expense_date', now()->subMonth()->year)
            ->sum('amount');
        
        $expenseGrowth = $lastMonthExpenses > 0 
            ? round((($monthlyExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100, 1)
            : ($monthlyExpenses > 0 ? 100 : 0);
        
        // Today's expenses
        $todayExpenses = Expense::whereDate('expense_date', today())->sum('amount');
        
        // Average expense per transaction this month
        $averageExpense = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->avg('amount');
        
        // Chart data - expenses last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $amount = Expense::whereDate('expense_date', $date)->sum('amount');
            $chartData[] = $amount;
        }

        $currencySymbol = CurrencyHelper::symbol();

        return [
            Stat::make('Monthly Expenses', $currencySymbol . number_format($monthlyExpenses, 2))
                ->description($expenseGrowth >= 0 ? "+{$expenseGrowth}% from last month" : "{$expenseGrowth}% from last month")
                ->descriptionIcon($expenseGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($expenseGrowth >= 0 ? 'danger' : 'warning')
                ->chart($chartData),

            Stat::make('Total Expenses', $currencySymbol . number_format($totalExpenses, 2))
                ->description('All time expenses')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger')
                ->chart(array_slice($chartData, -5)),

            Stat::make('Today\'s Expenses', $currencySymbol . number_format($todayExpenses, 2))
                ->description('Spent today')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning')
                ->chart(array_reverse($chartData)),

            Stat::make('Avg Expense (This Month)', $currencySymbol . number_format($averageExpense ?? 0, 2))
                ->description('Per transaction average')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info')
                ->chart(array_map(fn($v) => $v * 0.8, $chartData)),
        ];
    }
}

