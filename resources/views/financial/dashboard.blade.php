@extends('layouts.app')

@section('title', __('financial.dashboard') . ' - ' . __('dental.app_name'))
@section('description', __('financial.comprehensive_overview'))

@section('content')
<!-- Apple-style Page Header -->
<section class="section-apple-sm" style="background: var(--surface);">
    <div class="container-apple">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="display-medium mb-2">{{ __('financial.dashboard') }}</h1>
                <p class="body-large" style="color: var(--text-secondary);">
                    {{ __('financial.comprehensive_overview') }}
                </p>
            </div>
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('financial.reports') }}" class="btn-apple-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18"/>
                        <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                    </svg>
                    {{ __('financial.reports') }}
                </a>
                <a href="{{ route('financial.cash-flow') }}" class="btn-apple">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                    {{ __('financial.cash_flow') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Revenue Overview Cards -->
<section class="section-apple-sm">
    <div class="container-apple">
        <div class="grid-apple grid-apple-4">
            <!-- Today's Revenue -->
            <div class="card-apple-elevated">
                <div style="padding: var(--space-xl);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-lg);">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #34C759 0%, #30A46C 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                        <div style="text-align: right;">
                            <div class="stat-number" style="font-size: 1.5rem; color: #34C759;">${{ number_format($todayRevenue, 2) }}</div>
                            <div class="stat-label">{{ __('financial.today_revenue') }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: var(--space-sm);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #34C759;">
                            <polyline points="23,6 13.5,15.5 8.5,10.5 1,18"/>
                        </svg>
                        <span style="color: #34C759; font-size: 0.875rem; font-weight: 500;">+12.5% from yesterday</span>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="card-apple-elevated">
                <div style="padding: var(--space-xl);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-lg);">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div style="text-align: right;">
                            <div class="stat-number" style="font-size: 1.5rem; color: var(--primary);">${{ number_format($monthlyRevenue, 2) }}</div>
                            <div class="stat-label">{{ __('financial.monthly_revenue') }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: var(--space-sm);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary);">
                            <polyline points="23,6 13.5,15.5 8.5,10.5 1,18"/>
                        </svg>
                        <span style="color: var(--primary); font-size: 0.875rem; font-weight: 500;">+8.2% from last month</span>
                    </div>
                </div>
            </div>

            <!-- Outstanding Invoices -->
            <div class="card-apple-elevated">
                <div style="padding: var(--space-xl);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-lg);">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FF9500 0%, #FF8C00 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14,2 14,8 20,8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10,9 9,9 8,9"/>
                            </svg>
                        </div>
                        <div style="text-align: right;">
                            <div class="stat-number" style="font-size: 1.5rem; color: #FF9500;">${{ number_format($outstandingInvoicesTotal, 2) }}</div>
                            <div class="stat-label">{{ __('financial.outstanding') }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: var(--space-sm);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #FF9500;">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12,6 12,12 16,14"/>
                        </svg>
                        <span style="color: #FF9500; font-size: 0.875rem; font-weight: 500;">{{ $overdueInvoices->count() }} overdue</span>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="card-apple-elevated">
                <div style="padding: var(--space-xl);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-lg);">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #5856D6 0%, #4A4A9C 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                <line x1="1" y1="10" x2="23" y2="10"/>
                            </svg>
                        </div>
                        <div style="text-align: right;">
                            <div class="stat-number" style="font-size: 1.5rem; color: #5856D6;">{{ $paymentMethodsSummary->count() }}</div>
                            <div class="stat-label">{{ __('financial.payment_methods') }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: var(--space-sm);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #5856D6;">
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                        <span style="color: #5856D6; font-size: 0.875rem; font-weight: 500;">Active methods</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Dashboard Content -->
<section class="section-apple" style="background: var(--background);">
    <div class="container-apple">
        <div class="grid-apple" style="grid-template-columns: 2fr 1fr; gap: var(--space-2xl);">
            <!-- Recent Payments Table -->
            <div class="card-apple-elevated">
                <div style="padding: var(--space-xl);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
                        <h2 class="headline-medium">{{ __('financial.recent_payments') }}</h2>
                        <a href="{{ route('payments.index') }}" class="btn-apple-outline">
                            {{ __('common.view_all') }}
                        </a>
                    </div>
                    
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-light);">
                                    <th style="padding: var(--space-md); text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">{{ __('financial.patient') }}</th>
                                    <th style="padding: var(--space-md); text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">{{ __('financial.amount') }}</th>
                                    <th style="padding: var(--space-md); text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">{{ __('financial.method') }}</th>
                                    <th style="padding: var(--space-md); text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">{{ __('financial.date') }}</th>
                                    <th style="padding: var(--space-md); text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">{{ __('financial.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                <tr style="border-bottom: 1px solid var(--border-light); transition: all 0.2s ease;">
                                    <td style="padding: var(--space-md);">
                                        <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; color: var(--apple-white); font-size: 0.75rem; font-weight: 600;">
                                                {{ strtoupper(substr($payment->patient->name ?? 'N/A', 0, 2)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 500; color: var(--text-primary);">{{ $payment->patient->name ?? 'Unknown Patient' }}</div>
                                                <div style="font-size: 0.75rem; color: var(--text-tertiary);">ID: {{ $payment->patient->register_id ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: var(--space-md);">
                                        <div style="font-weight: 600; color: var(--text-primary);">${{ number_format($payment->amount, 2) }}</div>
                                    </td>
                                    <td style="padding: var(--space-md);">
                                        <span class="badge-apple badge-primary">{{ $payment->payment_method }}</span>
                                    </td>
                                    <td style="padding: var(--space-md);">
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">{{ $payment->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td style="padding: var(--space-md);">
                                        <span class="badge-apple badge-success">{{ __('financial.completed') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="padding: var(--space-2xl); text-align: center; color: var(--text-tertiary);">
                                        {{ __('financial.no_recent_payments') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Overdue Invoices -->
                <div class="card-apple-elevated" style="margin-bottom: var(--space-lg);">
                    <div style="padding: var(--space-xl);">
                        <h3 class="title-large mb-4">{{ __('financial.overdue_invoices') }}</h3>
                        @forelse($overdueInvoices->take(5) as $invoice)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--space-md) 0; border-bottom: 1px solid var(--border-light);">
                            <div>
                                <div style="font-weight: 500; color: var(--text-primary);">{{ $invoice->patient->name ?? 'N/A' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-tertiary);">Invoice #{{ $invoice->id }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 600; color: #FF3B30;">${{ number_format($invoice->total_amount, 2) }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-tertiary);">{{ $invoice->due_date->diffForHumans() }}</div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align: center; padding: var(--space-xl); color: var(--text-tertiary);">
                            {{ __('financial.no_overdue_invoices') }}
                        </div>
                        @endforelse
                        
                        @if($overdueInvoices->count() > 5)
                        <div style="text-align: center; margin-top: var(--space-md);">
                            <a href="{{ route('invoices.index') }}" class="btn-apple-outline" style="font-size: 0.875rem;">
                                {{ __('common.view_all') }} ({{ $overdueInvoices->count() }})
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Top Services -->
                <div class="card-apple-elevated">
                    <div style="padding: var(--space-xl);">
                        <h3 class="title-large mb-4">{{ __('financial.top_services') }}</h3>
                        @forelse($topServices as $service)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: var(--space-md) 0; border-bottom: 1px solid var(--border-light);">
                            <div>
                                <div style="font-weight: 500; color: var(--text-primary);">{{ $service->name ?? 'Unknown Service' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-tertiary);">{{ $service->total_quantity ?? 0 }} items</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 600; color: var(--primary);">${{ number_format($service->total_revenue ?? 0, 2) }}</div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align: center; padding: var(--space-xl); color: var(--text-tertiary);">
                            {{ __('financial.no_services_data') }}
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">{{ __('financial.quick_actions') }}</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                {{ __('financial.manage_financial_operations') }}
            </p>
        </div>
        
        <div class="grid-apple grid-apple-4">
            <a href="{{ route('invoices.create') }}" class="card-apple" style="text-align: center; padding: var(--space-xl); text-decoration: none; color: inherit;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10,9 9,9 8,9"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('financial.create_invoice') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('financial.generate_new_invoice') }}
                </p>
            </a>
            
            <a href="{{ route('payments.create') }}" class="card-apple" style="text-align: center; padding: var(--space-xl); text-decoration: none; color: inherit;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #34C759 0%, #30A46C 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('financial.record_payment') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('financial.process_payment') }}
                </p>
            </a>
            
            <a href="{{ route('financial.reports') }}" class="card-apple" style="text-align: center; padding: var(--space-xl); text-decoration: none; color: inherit;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #FF9500 0%, #FF8C00 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M3 3v18h18"/>
                        <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('financial.view_reports') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('financial.analytics_insights') }}
                </p>
            </a>
            
            <a href="{{ route('financial.cash-flow') }}" class="card-apple" style="text-align: center; padding: var(--space-xl); text-decoration: none; color: inherit;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #5856D6 0%, #4A4A9C 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
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
                <h3 class="title-large mb-3">{{ __('financial.cash_flow') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('financial.monitor_cash_flow') }}
                </p>
            </a>
        </div>
    </div>
</section>
@endsection