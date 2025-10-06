<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['patient', 'invoice']);
        
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        if ($request->has('date_from')) {
            $query->where('payment_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->where('payment_date', '<=', $request->date_to);
        }
        
        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);
        $patients = Patient::all();
        
        return view('financial.payments.index', compact('payments', 'patients'));
    }

    public function create(Request $request)
    {
        $patients = Patient::all();
        $invoices = collect();
        
        if ($request->has('patient_id')) {
            $invoices = Invoice::where('patient_id', $request->patient_id)
                ->where('status', '!=', 'paid')
                ->get();
        }
        
        return view('financial.payments.create', compact('patients', 'invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'patient_id' => 'required|exists:patients,register_id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank_transfer,check,other',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        
        // Check if payment amount exceeds invoice balance
        $currentPaidAmount = $invoice->payments()->sum('amount');
        $remainingBalance = $invoice->total_amount - $currentPaidAmount;
        
        if ($request->amount > $remainingBalance) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed invoice balance.'])
                ->withInput();
        }

        $payment = Payment::create([
            'invoice_id' => $request->invoice_id,
            'patient_id' => $request->patient_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        // Update invoice status if fully paid
        $newPaidAmount = $currentPaidAmount + $request->amount;
        if ($newPaidAmount >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        } else {
            $invoice->update(['status' => 'sent']);
        }

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['patient', 'invoice', 'createdBy']);
        
        return view('financial.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $patients = Patient::all();
        $invoices = Invoice::where('patient_id', $payment->patient_id)
            ->where('status', '!=', 'paid')
            ->get();
        
        return view('financial.payments.edit', compact('payment', 'patients', 'invoices'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'patient_id' => 'required|exists:patients,register_id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank_transfer,check,other',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        
        // Calculate remaining balance excluding current payment
        $otherPayments = $invoice->payments()->where('id', '!=', $payment->id)->sum('amount');
        $remainingBalance = $invoice->total_amount - $otherPayments;
        
        if ($request->amount > $remainingBalance) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed invoice balance.'])
                ->withInput();
        }

        $payment->update([
            'invoice_id' => $request->invoice_id,
            'patient_id' => $request->patient_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
        ]);

        // Update invoice status
        $totalPaidAmount = $invoice->payments()->sum('amount');
        if ($totalPaidAmount >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        } else {
            $invoice->update(['status' => 'sent']);
        }

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;
        $payment->delete();
        
        // Update invoice status
        $totalPaidAmount = $invoice->payments()->sum('amount');
        if ($totalPaidAmount >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        } else {
            $invoice->update(['status' => 'sent']);
        }
        
        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    public function getPatientInvoices(Request $request)
    {
        $invoices = Invoice::where('patient_id', $request->patient_id)
            ->where('status', '!=', 'paid')
            ->get();
        
        return response()->json($invoices);
    }
}