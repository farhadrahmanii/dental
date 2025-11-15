@php
    $toothNumbers = $getState();
    
    if (is_string($toothNumbers)) {
        // Try to decode JSON if it's a string
        $decoded = json_decode($toothNumbers, true);
        $toothNumbers = is_array($decoded) ? $decoded : [];
    }
    
    if (!is_array($toothNumbers)) {
        $toothNumbers = [];
    }
@endphp

@if(count($toothNumbers) > 0)
    <div class="flex flex-wrap gap-2">
        @foreach($toothNumbers as $tooth)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                {{ $tooth }}
            </span>
        @endforeach
    </div>
@else
    <span class="text-gray-500 text-sm italic">{{ __('filament.no_tooth_numbers') ?? 'No tooth numbers selected' }}</span>
@endif

