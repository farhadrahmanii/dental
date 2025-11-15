@php
    $record = $getRecord();
    $service = $record?->service;
@endphp

@if($service)
    <span class="text-sm font-medium text-gray-900">
        {{ $service->name }}
    </span>
@else
    <span class="text-gray-500 text-sm italic">{{ __('filament.no_service') ?? 'No service' }}</span>
@endif

