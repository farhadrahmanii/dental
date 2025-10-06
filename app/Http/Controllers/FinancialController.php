<?php

namespace App\Http\Controllers;

use App\Models\FinancialReport;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function dashboard()
    {
        // Get revenue data with fallback values
        $todayRevenue = FinancialReport::getDailyRevenue() ?? 0;
        $monthlyRevenue = FinancialReport::getMonthlyRevenue() ?? 0;
        
        // Get outstanding invoices total amount (numeric value)
        $outstandingInvoicesTotal = Invoice::where('status', '!=', 'paid')
            ->sum('total_amount') ?? 0;
        
        // Get outstanding invoices collection for display
        $outstandingInvoices = Invoice::where('status', '!=', 'paid')
            ->with('patient')
            ->get();
        
        // Get payment methods summary with fallback
        $paymentMethodsSummary = FinancialReport::getPaymentMethodsSummary() ?? collect();
        
        // Get top services with fallback
        $topServices = FinancialReport::getTopServices(5) ?? collect();
        
        // Get recent transactions
        $recentPayments = Payment::with(['patient', 'invoice'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get overdue invoices
        $overdueInvoices = Invoice::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->with('patient')
            ->get();
        
        return view('financial.dashboard', compact(
            'todayRevenue',
            'monthlyRevenue',
            'outstandingInvoicesTotal',
            'outstandingInvoices',
            'paymentMethodsSummary',
            'topServices',
            'recentPayments',
            'overdueInvoices'
        ));
    }

    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        
        $revenueData = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->select(DB::raw('DATE(payment_date) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $serviceRevenue = FinancialReport::getTopServices(10, $startDate, $endDate);
        $paymentMethods = FinancialReport::getPaymentMethodsSummary($startDate, $endDate);
        
        return view('financial.reports', compact(
            'revenueData',
            'serviceRevenue',
            'paymentMethods',
            'startDate',
            'endDate'
        ));
    }

    public function cashFlow()
    {
        $cashPayments = Payment::byMethod('cash')
            ->with(['patient', 'invoice'])
            ->orderBy('payment_date', 'desc')
            ->get();
        
        $dailyCashFlow = Payment::byMethod('cash')
            ->select(DB::raw('DATE(payment_date) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();
        
        return view('financial.cash-flow', compact('cashPayments', 'dailyCashFlow'));
    }
}