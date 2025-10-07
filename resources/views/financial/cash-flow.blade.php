@extends('layouts.app')

@section('title', __('financial.cash_flow') . ' - ' . __('dental.app_name'))
@section('description', __('financial.cash_flow_description'))

@section('content')
<!-- Apple-style Page Header -->
<section class="section-apple-sm" style="background: var(--surface);">
    <div class="container-apple">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="display-medium mb-2">{{ __('financial.cash_flow') }}</h1>
                <p class="body-large" style="color: var(--text-secondary);">
                    {{ __('financial.cash_flow_description') }}
                </p>
            </div>
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('financial.dashboard') }}" class="btn-apple-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18"/>
                        <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                    </svg>
                    {{ __('financial.dashboard') }}
                </a>
                <a href="{{ route('financial.reports') }}" class="btn-apple">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18"/>
                        <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                    </svg>
                    {{ __('financial.reports') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Cash Flow Summary Cards -->
<section class="section-apple-sm">
    <div class="container-apple">
        <div class="grid-apple grid-apple-3">
            <!-- Total Cash Payments -->
            <div class="card-apple-elevated">
                <div class="card-apple-header">
                    <div class="card-apple-icon" style="background: var(--success-container); color: var(--success);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 1v6l4-4"/>
                            <path d="M12 7l-4-4"/>
                            <path d="M17 12h6"/>
                            <path d="M17 12l-4 4"/>
                            <path d="M17 12l4 4"/>
                            <path d="M7 12H1"/>
                            <path d="M7 12l4 4"/>
                            <path d="M7 12l-4 4"/>
                            <path d="M12 17v6l4-4"/>
                            <path d="M12 17l-4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="title-medium">{{ __('financial.total_cash_payments') }}</h3>
                        <p class="body-large" style="color: var(--text-secondary);">{{ __('financial.last_30_days') }}</p>
                    </div>
                </div>
                <div class="card-apple-content">
                    <div class="display-large" style="color: var(--success);">
                        {{ \App\Helpers\CurrencyHelper::format($dailyCashFlow->sum('total')) }}
                    </div>
                </div>
            </div>

            <!-- Average Daily Cash -->
            <div class="card-apple-elevated">
                <div class="card-apple-header">
                    <div class="card-apple-icon" style="background: var(--info-container); color: var(--info);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3v18h18"/>
                            <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="title-medium">{{ __('financial.average_daily_cash') }}</h3>
                        <p class="body-large" style="color: var(--text-secondary);">{{ __('financial.last_30_days') }}</p>
                    </div>
                </div>
                <div class="card-apple-content">
                    <div class="display-large" style="color: var(--info);">
                        {{ \App\Helpers\CurrencyHelper::format($dailyCashFlow->avg('total') ?? 0) }}
                    </div>
                </div>
            </div>

            <!-- Cash Payment Count -->
            <div class="card-apple-elevated">
                <div class="card-apple-header">
                    <div class="card-apple-icon" style="background: var(--warning-container); color: var(--warning);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="title-medium">{{ __('financial.cash_transactions') }}</h3>
                        <p class="body-large" style="color: var(--text-secondary);">{{ __('financial.total_count') }}</p>
                    </div>
                </div>
                <div class="card-apple-content">
                    <div class="display-large" style="color: var(--warning);">
                        {{ $cashPayments->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Daily Cash Flow Chart -->
<section class="section-apple-sm">
    <div class="container-apple">
        <div class="card-apple-elevated">
            <div class="card-apple-header">
                <h2 class="headline-small">{{ __('financial.daily_cash_flow') }}</h2>
                <p class="body-large" style="color: var(--text-secondary);">{{ __('financial.last_30_days') }}</p>
            </div>
            <div class="card-apple-content">
                @if($dailyCashFlow->count() > 0)
                    <div style="height: 300px; position: relative;">
                        <canvas id="cashFlowChart"></canvas>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 3v18h18"/>
                                <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                            </svg>
                        </div>
                        <h3 class="title-medium">{{ __('financial.no_cash_data') }}</h3>
                        <p class="body-large" style="color: var(--text-secondary);">{{ __('financial.no_cash_data_description') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Recent Cash Payments -->
<section class="section-apple-sm">
    <div class="container-apple">
        <div class="card-apple-elevated">
            <div class="card-apple-header">
                <h2 class="headline-small">{{ __('financial.recent_cash_payments') }}</h2>
                <p class="body-large" style="color: var(--text-secondary);">{{ __('financial.latest_transactions') }}</p>
            </div>
            <div class="card-apple-content">
                @if($cashPayments->count() > 0)
                    <div class="table-apple">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('financial.patient') }}</th>
                                    <th>{{ __('financial.amount') }}</th>
                                    <th>{{ __('financial.date') }}</th>
                                    <th>{{ __('financial.reference') }}</th>
                                    <th>{{ __('financial.notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cashPayments->take(20) as $payment)
                                <tr>
                                    <td>
                                        <div class="table-cell-content">
                                            <div class="table-cell-primary">{{ $payment->patient->name ?? 'N/A' }}</div>
                                            <div class="table-cell-secondary">{{ $payment->patient->father_name ?? '' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-cell-content">
                                            <div class="table-cell-primary" style="color: var(--success); font-weight: 600;">
                                                {{ \App\Helpers\CurrencyHelper::format($payment->amount) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-cell-content">
                                            <div class="table-cell-primary">{{ $payment->payment_date->format('M d, Y') }}</div>
                                            <div class="table-cell-secondary">{{ $payment->payment_date->format('g:i A') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-cell-content">
                                            <div class="table-cell-primary">{{ $payment->reference_number ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-cell-content">
                                            <div class="table-cell-primary">{{ Str::limit($payment->notes ?? '', 30) }}</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 1v6l4-4"/>
                                <path d="M12 7l-4-4"/>
                                <path d="M17 12h6"/>
                                <path d="M17 12l-4 4"/>
                                <path d="M17 12l4 4"/>
                                <path d="M7 12H1"/>
                                <path d="M7 12l4 4"/>
                                <path d="M7 12l-4 4"/>
                                <path d="M12 17v6l4-4"/>
                                <path d="M12 17l-4-4"/>
                            </svg>
                        </div>
                        <h3 class="title-medium">{{ __('financial.no_cash_payments') }}</h3>
                        <p class="body-large" style="color: var(--text-secondary);">{{ __('financial.no_cash_payments_description') }}</p>
                        <a href="{{ route('payments.create') }}" class="btn-apple">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>
                            </svg>
                            {{ __('financial.record_payment') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@if($dailyCashFlow->count() > 0)
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('cashFlowChart').getContext('2d');
    const cashFlowData = @json($dailyCashFlow->reverse());
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: cashFlowData.map(item => new Date(item.date).toLocaleDateString()),
            datasets: [{
                label: '{{ __("financial.daily_cash_flow") }}',
                data: cashFlowData.map(item => item.total),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '{{ \App\Helpers\CurrencyHelper::symbol() }}' + value.toFixed(2);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
@endif
@endsection
