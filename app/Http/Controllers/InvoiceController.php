<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['patient', 'items.service']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        
        $invoices = $query->orderBy('created_at', 'desc')->paginate(15);
        $patients = Patient::all();
        
        return view('financial.invoices.index', compact('invoices', 'patients'));
    }

    public function create()
    {
        $patients = Patient::all();
        $services = Service::active()->get();
        
        return view('financial.invoices.create', compact('patients', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,register_id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::create([
            'patient_id' => $request->patient_id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'subtotal' => 0,
            'tax_amount' => $request->tax_amount ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'total_amount' => 0,
            'status' => 'draft',
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $totalPrice = $item['quantity'] * $item['unit_price'];
            $subtotal += $totalPrice;
            
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'service_id' => $item['service_id'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $totalPrice,
            ]);
        }

        $totalAmount = $subtotal + $invoice->tax_amount - $invoice->discount_amount;
        
        $invoice->update([
            'subtotal' => $subtotal,
            'total_amount' => $totalAmount,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'items.service', 'payments']);
        
        return view('financial.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Cannot edit paid invoice.');
        }
        
        $patients = Patient::all();
        $services = Service::active()->get();
        $invoice->load('items');
        
        return view('financial.invoices.edit', compact('invoice', 'patients', 'services'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Cannot edit paid invoice.');
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,register_id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $invoice->update([
            'patient_id' => $request->patient_id,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'tax_amount' => $request->tax_amount ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'notes' => $request->notes,
        ]);

        // Delete existing items
        $invoice->items()->delete();

        // Create new items
        $subtotal = 0;
        foreach ($request->items as $item) {
            $totalPrice = $item['quantity'] * $item['unit_price'];
            $subtotal += $totalPrice;
            
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'service_id' => $item['service_id'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $totalPrice,
            ]);
        }

        $totalAmount = $subtotal + $invoice->tax_amount - $invoice->discount_amount;
        
        $invoice->update([
            'subtotal' => $subtotal,
            'total_amount' => $totalAmount,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.index')
                ->with('error', 'Cannot delete paid invoice.');
        }
        
        $invoice->delete();
        
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function markAsSent(Invoice $invoice)
    {
        $invoice->update(['status' => 'sent']);
        
        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice marked as sent.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['patient', 'items.service']);
        
        return view('financial.invoices.print', compact('invoice'));
    }
}