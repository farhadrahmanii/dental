@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Create Invoice</h1>
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Invoices
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Invoice Details</h5>
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
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                    <input type="date" name="invoice_date" id="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', now()->toDateString()) }}" required>
                                    @error('invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                    <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', now()->addDays(30)->toDateString()) }}" required>
                                    @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Items -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Invoice Items</h5>
                    </div>
                    <div class="card-body">
                        <div id="invoice-items">
                            <div class="invoice-item row mb-3">
                                <div class="col-md-5">
                                    <label class="form-label">Service <span class="text-danger">*</span></label>
                                    <select name="items[0][service_id]" class="form-select service-select" required>
                                        <option value="">Select Service</option>
                                        @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                            {{ $service->name }} - {{ \App\Helpers\CurrencyHelper::format($service->price) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="items[0][quantity]" class="form-control quantity-input" step="0.01" min="0.01" value="1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                                    <input type="number" name="items[0][unit_price]" class="form-control unit-price-input" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Total</label>
                                    <input type="text" class="form-control total-price" readonly>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm remove-item" style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="add-item" class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                </div>

                <!-- Notes -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notes</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Invoice Summary -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Invoice Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="subtotal" class="form-label">Subtotal</label>
                            <input type="text" id="subtotal" class="form-control" readonly value="{{ \App\Helpers\CurrencyHelper::format(0) }}">
                        </div>
                        <div class="mb-3">
                            <label for="tax_amount" class="form-label">Tax Amount</label>
                            <input type="number" name="tax_amount" id="tax_amount" class="form-control" step="0.01" min="0" value="{{ old('tax_amount', 0) }}">
                        </div>
                        <div class="mb-3">
                            <label for="discount_amount" class="form-label">Discount Amount</label>
                            <input type="number" name="discount_amount" id="discount_amount" class="form-control" step="0.01" min="0" value="{{ old('discount_amount', 0) }}">
                        </div>
                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input type="text" id="total_amount" class="form-control" readonly value="{{ \App\Helpers\CurrencyHelper::format(0) }}">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card mt-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Create Invoice
                        </button>
                        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary btn-block">
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
    let itemIndex = 0;
    
    // Add item functionality
    document.getElementById('add-item').addEventListener('click', function() {
        itemIndex++;
        const itemsContainer = document.getElementById('invoice-items');
        const newItem = document.querySelector('.invoice-item').cloneNode(true);
        
        // Update input names and clear values
        newItem.querySelectorAll('input, select').forEach(input => {
            const name = input.name;
            if (name) {
                input.name = name.replace('[0]', '[' + itemIndex + ']');
            }
            if (input.type !== 'hidden') {
                input.value = '';
            }
        });
        
        // Show remove button
        newItem.querySelector('.remove-item').style.display = 'block';
        
        itemsContainer.appendChild(newItem);
        
        // Add remove functionality
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            newItem.remove();
            calculateTotal();
        });
        
        // Add event listeners for calculations
        addCalculationListeners(newItem);
    });
    
    // Add calculation listeners to initial item
    addCalculationListeners(document.querySelector('.invoice-item'));
    
    // Add listeners for tax and discount
    document.getElementById('tax_amount').addEventListener('input', calculateTotal);
    document.getElementById('discount_amount').addEventListener('input', calculateTotal);
    
    function addCalculationListeners(item) {
        const serviceSelect = item.querySelector('.service-select');
        const quantityInput = item.querySelector('.quantity-input');
        const unitPriceInput = item.querySelector('.unit-price-input');
        
        serviceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.dataset.price) {
                unitPriceInput.value = selectedOption.dataset.price;
                calculateItemTotal(item);
                calculateTotal();
            }
        });
        
        quantityInput.addEventListener('input', function() {
            calculateItemTotal(item);
            calculateTotal();
        });
        
        unitPriceInput.addEventListener('input', function() {
            calculateItemTotal(item);
            calculateTotal();
        });
    }
    
    function calculateItemTotal(item) {
        const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
        const unitPrice = parseFloat(item.querySelector('.unit-price-input').value) || 0;
        const total = quantity * unitPrice;
        item.querySelector('.total-price').value = '{{ \App\Helpers\CurrencyHelper::symbol() }}' + total.toFixed(2);
    }
    
    function calculateTotal() {
        let subtotal = 0;
        document.querySelectorAll('.invoice-item').forEach(item => {
            const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseFloat(item.querySelector('.unit-price-input').value) || 0;
            subtotal += quantity * unitPrice;
        });
        
        const taxAmount = parseFloat(document.getElementById('tax_amount').value) || 0;
        const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
        const totalAmount = subtotal + taxAmount - discountAmount;
        
        document.getElementById('subtotal').value = '{{ \App\Helpers\CurrencyHelper::symbol() }}' + subtotal.toFixed(2);
        document.getElementById('total_amount').value = '{{ \App\Helpers\CurrencyHelper::symbol() }}' + totalAmount.toFixed(2);
    }
});
</script>
@endsection
