@php
    // Get the record - ViewField provides getRecord() method
    $record = $getRecord();
    $diagnosis = $record?->diagnosis ?? $getState() ?? null;
    $imageUrl = null;
    
    if ($diagnosis) {
        // Check if it's a full URL
        if (str_starts_with($diagnosis, 'http://') || str_starts_with($diagnosis, 'https://')) {
            $imageUrl = $diagnosis;
        } 
        // Check if it exists in storage (path might be 'patients/diagnosis/filename.jpg')
        elseif (\Storage::disk('public')->exists($diagnosis)) {
            $imageUrl = \Storage::disk('public')->url($diagnosis);
        } 
        // Try different path variations
        else {
            // Try as direct storage path
            $imageUrl = asset('storage/' . ltrim($diagnosis, '/'));
        }
    }
@endphp

@if($imageUrl)
    <div class="filament-diagnosis-view" style="max-width: 500px; margin: 0 auto;">
        <img
            src="{{ $imageUrl }}"
            alt="Diagnosis"
            class="w-full rounded-lg border border-gray-200 shadow-sm cursor-pointer hover:opacity-90 transition-opacity"
            style="max-width: 100%; height: auto; object-fit: contain; max-height: 500px;"
            onclick="window.open('{{ $imageUrl }}', '_blank')"
            title="Click to view full size"
        >
        <p class="text-xs text-gray-500 mt-2 text-center">Click image to view full size</p>
    </div>
@else
    <div class="text-gray-500 text-sm italic">
        {{ __('filament.no_diagnosis_image') }}
    </div>
@endif

