@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Record Payment</h1>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Payments
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Payment Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                                    <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                        <option value="">Select Patient</option>
                                        @foreach($patients as $patient)
                                        <option value="{{ $patient->register_id }}" {{ old('patient_id') == $patient->register_id ? 'selected' : '' }}>
                                            {{ $patient->name }} ({{ $patient->register_id }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="invoice_id" class="form-label">Invoice <span class="text-danger">*</span></label>
                                    <select name="invoice_id" id="invoice_id" class="form-select @error('invoice_id') is-invalid @enderror" required>
                                        <option value="">Select Invoice</option>
                                        @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}" data-balance="{{ $invoice->balance }}">
                                            {{ $invoice->invoice_number }} - ${{ number_format($invoice->balance, 2) }} remaining
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('invoice_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" step="0.01" min="0.01" required>
                                    @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                        <option value="">Select Method</option>
                                        <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                                        <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="check" {{ old('payment_method') === 'check' ? 'selected' : '' }}>Check</option>
                                        <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', now()->toDateString()) }}" required>
                                    @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reference_number" class="form-label">Reference Number</label>
                                    <input type="text" name="reference_number" id="reference_number" class="form-control @error('reference_number') is-invalid @enderror" value="{{ old('reference_number') }}">
                                    @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Invoice Summary -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Invoice Summary</h5>
                    </div>
                    <div class="card-body" id="invoice-summary" style="display: none;">
                        <div class="mb-2">
                            <strong>Invoice Number:</strong> <span id="invoice-number"></span>
                        </div>
                        <div class="mb-2">
                            <strong>Total Amount:</strong> $<span id="invoice-total"></span>
                        </div>
                        <div class="mb-2">
                            <strong>Paid Amount:</strong> $<span id="invoice-paid"></span>
                        </div>
                        <div class="mb-2">
                            <strong>Remaining Balance:</strong> $<span id="invoice-balance"></span>
                        </div>
                        <hr>
                        <div class="alert alert-info">
                            <small>Maximum payment amount: $<span id="max-payment"></span></small>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card mt-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-money-bill-wave"></i> Record Payment
                        </button>
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-block">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const patientSelect = document.getElementById('patient_id');
    const invoiceSelect = document.getElementById('invoice_id');
    const amountInput = document.getElementById('amount');
    const invoiceSummary = document.getElementById('invoice-summary');
    
    // Load invoices when patient is selected
    patientSelect.addEventListener('change', function() {
        const patientId = this.value;
        if (patientId) {
            fetch(`/api/patient-invoices?patient_id=${patientId}`)
                .then(response => response.json())
                .then(invoices => {
                    invoiceSelect.innerHTML = '<option value="">Select Invoice</option>';
                    invoices.forEach(invoice => {
                        const option = document.createElement('option');
                        option.value = invoice.id;
                        option.textContent = `${invoice.invoice_number} - $${parseFloat(invoice.balance).toFixed(2)} remaining`;
                        option.dataset.balance = invoice.balance;
                        invoiceSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading invoices:', error));
        } else {
            invoiceSelect.innerHTML = '<option value="">Select Invoice</option>';
            invoiceSummary.style.display = 'none';
        }
    });
    
    // Show invoice summary when invoice is selected
    invoiceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const balance = parseFloat(selectedOption.dataset.balance);
            document.getElementById('max-payment').textContent = balance.toFixed(2);
            amountInput.max = balance;
            invoiceSummary.style.display = 'block';
        } else {
            invoiceSummary.style.display = 'none';
        }
    });
    
    // Validate amount
    amountInput.addEventListener('input', function() {
        const maxAmount = parseFloat(this.max);
        const currentAmount = parseFloat(this.value);
        
        if (currentAmount > maxAmount) {
            this.setCustomValidity(`Amount cannot exceed $${maxAmount.toFixed(2)}`);
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endsection
