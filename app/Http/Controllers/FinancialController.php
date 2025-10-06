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
        $todayRevenue = FinancialReport::getDailyRevenue();
        $monthlyRevenue = FinancialReport::getMonthlyRevenue();
        $outstandingInvoices = FinancialReport::getOutstandingInvoices();
        $paymentMethodsSummary = FinancialReport::getPaymentMethodsSummary();
        $topServices = FinancialReport::getTopServices(5);
        
        // Get recent transactions
        $recentPayments = Payment::with(['patient', 'invoice'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get overdue invoices
        $overdueInvoices = Invoice::overdue()
            ->with('patient')
            ->get();
        
        return view('financial.dashboard', compact(
            'todayRevenue',
            'monthlyRevenue',
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