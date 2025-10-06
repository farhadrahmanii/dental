@extends('layouts.app')

@section('title', 'Financial Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Financial Dashboard</h1>
                <div class="btn-group">
                    <a href="{{ route('financial.reports') }}" class="btn btn-outline-primary">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                    <a href="{{ route('financial.cash-flow') }}" class="btn btn-outline-success">
                        <i class="fas fa-money-bill-wave"></i> Cash Flow
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Today's Revenue</h4>
                            <h2 class="mb-0">${{ number_format($todayRevenue, 2) }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Monthly Revenue</h4>
                            <h2 class="mb-0">${{ number_format($monthlyRevenue, 2) }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Outstanding</h4>
                            <h2 class="mb-0">${{ number_format($outstandingInvoices->sum('balance'), 2) }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Overdue</h4>
                            <h2 class="mb-0">{{ $overdueInvoices->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Payments -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Payments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Invoice</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>{{ $payment->patient->name }}</td>
                                    <td>{{ $payment->invoice->invoice_number }}</td>
                                    <td class="text-success">${{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->payment_method === 'cash' ? 'success' : 'primary' }}">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No recent payments</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    @forelse($paymentMethodsSummary as $method)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>{{ ucfirst($method->payment_method) }}</span>
                        <strong>${{ number_format($method->total, 2) }}</strong>
                    </div>
                    @empty
                    <p class="text-muted">No payment data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Top Services -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Services</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Revenue</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topServices as $service)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>${{ number_format($service->total_revenue, 2) }}</td>
                                    <td>{{ $service->total_quantity }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No service data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Invoices -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Overdue Invoices</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Patient</th>
                                    <th>Amount</th>
                                    <th>Days Overdue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overdueInvoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->patient->name }}</td>
                                    <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ now()->diffInDays($invoice->due_date) }} days
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No overdue invoices</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-file-invoice"></i> Create Invoice
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('payments.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-money-bill-wave"></i> Record Payment
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('services.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-cogs"></i> Manage Services
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('invoices.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-list"></i> View All Invoices
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
