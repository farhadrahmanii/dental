<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FinancialReport extends Model
{
    public static function getDailyRevenue($date = null)
    {
        $date = $date ?: now()->toDateString();
        
        return Payment::whereDate('payment_date', $date)
            ->sum('amount');
    }

    public static function getMonthlyRevenue($year = null, $month = null)
    {
        $year = $year ?: now()->year;
        $month = $month ?: now()->month;
        
        return Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->sum('amount');
    }

    public static function getOutstandingInvoices()
    {
        return Invoice::where('status', '!=', 'paid')
            ->with('patient')
            ->get()
            ->map(function ($invoice) {
                return [
                    'invoice' => $invoice,
                    'balance' => $invoice->balance,
                    'days_overdue' => $invoice->due_date < now() ? now()->diffInDays($invoice->due_date) : 0,
                ];
            });
    }

    public static function getPaymentMethodsSummary($startDate = null, $endDate = null)
    {
        $query = Payment::query();
        
        if ($startDate && $endDate) {
            $query->whereBetween('payment_date', [$startDate, $endDate]);
        }
        
        return $query->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();
    }

    public static function getTopServices($limit = 10, $startDate = null, $endDate = null)
    {
        $query = InvoiceItem::join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('services', 'invoice_items.service_id', '=', 'services.id');
        
        if ($startDate && $endDate) {
            $query->whereBetween('invoices.invoice_date', [$startDate, $endDate]);
        }
        
        return $query->select('services.name', DB::raw('SUM(invoice_items.quantity) as total_quantity'), DB::raw('SUM(invoice_items.total_price) as total_revenue'))
            ->groupBy('services.id', 'services.name')
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get();
    }
}
