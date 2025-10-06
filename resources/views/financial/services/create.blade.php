@extends('layouts.app')

@section('title', 'Add Service')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Add Service</h1>
                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Services
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('services.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Service Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" placeholder="e.g., General, Restorative, Surgery">
                                    @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" step="0.01" min="0" value="{{ old('price') }}" required>
                                    </div>
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">
                                            Active Service
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Service description...">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Service Preview -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Service Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Name:</strong> <span id="preview-name">-</span>
                        </div>
                        <div class="mb-2">
                            <strong>Category:</strong> <span id="preview-category">-</span>
                        </div>
                        <div class="mb-2">
                            <strong>Price:</strong> $<span id="preview-price">0.00</span>
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong> <span id="preview-status" class="badge bg-success">Active</span>
                        </div>
                        <div class="mb-2">
                            <strong>Description:</strong>
                            <div id="preview-description" class="text-muted">-</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card mt-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Create Service
                        </button>
                        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary btn-block">
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
    const nameInput = document.getElementById('name');
    const categoryInput = document.getElementById('category');
    const priceInput = document.getElementById('price');
    const descriptionInput = document.getElementById('description');
    const isActiveInput = document.getElementById('is_active');
    
    function updatePreview() {
        document.getElementById('preview-name').textContent = nameInput.value || '-';
        document.getElementById('preview-category').textContent = categoryInput.value || '-';
        document.getElementById('preview-price').textContent = priceInput.value ? parseFloat(priceInput.value).toFixed(2) : '0.00';
        document.getElementById('preview-description').textContent = descriptionInput.value || '-';
        
        const statusBadge = document.getElementById('preview-status');
        if (isActiveInput.checked) {
            statusBadge.textContent = 'Active';
            statusBadge.className = 'badge bg-success';
        } else {
            statusBadge.textContent = 'Inactive';
            statusBadge.className = 'badge bg-danger';
        }
    }
    
    nameInput.addEventListener('input', updatePreview);
    categoryInput.addEventListener('input', updatePreview);
    priceInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    isActiveInput.addEventListener('change', updatePreview);
    
    // Initial preview update
    updatePreview();
});
</script>
@endsection
