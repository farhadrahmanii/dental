@php
    // Build dynamic visits dataset from appointments if available; otherwise fallback
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    try {
        $year = now()->year;
        $dbMonthly = \App\Models\Appointment::selectRaw('MONTH(appointment_date) as m, COUNT(*) as c')
            ->whereYear('appointment_date', $year)
            ->groupBy('m')
            ->pluck('c', 'm')
            ->toArray();
        $visitsMonthly = [];
        for ($i = 1; $i <= 12; $i++) {
            $visitsMonthly[] = (int) ($dbMonthly[$i] ?? 0);
        }
        $hasVisits = array_sum($visitsMonthly) > 0;
    } catch (\Throwable $e) {
        $hasVisits = false;
        $visitsMonthly = [];
    }
    if (!$hasVisits) {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
        $visitsMonthly = [100, 150, 200, 250, 300, 350, 400];
    }

    // Build expense data by expense type from database
    $expenseColors = [
        '#2563eb', // Blue
        '#22c55e', // Green
        '#a855f7', // Purple
        '#f59e0b', // Orange
        '#06b6d4', // Cyan
        '#ef4444', // Red
        '#eab308', // Yellow
        '#8b5cf6', // Violet
        '#ec4899', // Pink
        '#14b8a6', // Teal
    ];
    
    try {
        $expenseTypes = \App\Models\Expense::selectRaw('expense_type, SUM(amount) as total_amount, COUNT(*) as count')
            ->groupBy('expense_type')
            ->orderByDesc('total_amount')
            ->get();
        
        $costs = [];
        $colorIndex = 0;
        foreach ($expenseTypes as $expense) {
            $costs[] = [
                'label' => $expense->expense_type,
                'value' => (float) $expense->total_amount,
                'color' => $expenseColors[$colorIndex % count($expenseColors)],
                'count' => $expense->count,
            ];
            $colorIndex++;
        }
        
        // If no expenses, show fallback message
        if (empty($costs)) {
            $costs = [
                ['label' => 'No Expenses Yet', 'value' => 0, 'color' => '#9ca3af', 'count' => 0],
            ];
        }
        
        $totalCosts = collect($costs)->sum('value');
    } catch (\Throwable $e) {
        // Fallback if Expense model doesn't exist yet
        $costs = [
            ['label' => 'No Expenses Yet', 'value' => 0, 'color' => '#9ca3af', 'count' => 0],
        ];
        $totalCosts = 0;
    }
@endphp

@if (request()->routeIs('filament.admin.pages.dashboard'))
    <div class="row g-3" style="display:flex;gap:1.5rem;flex-wrap:nowrap;width:100%; margin-top: 1rem;">
        <div class="col-md-6" style="width:50%;min-width:0">
            <div
                class="fi-widget w-full p-5 bg-white/80 backdrop-blur rounded-2xl shadow ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
                <div class="flex items-center justify-between mb-4" style="margin: 2rem 0;">
                    <h3 class="text-base font-semibold tracking-tight">Financial Overview - Expenses by Type</h3>
                </div>
                <div
                    style="display:flex; align-items:center; justify-content:space-between; gap:16px; height:280px; flex-wrap:nowrap;">
                    <div class="relative" style="width:280px;height:280px; flex:0 0 280px;">
                        <canvas id="financeDonut"
                            style="width:280px;height:280px; position:relative; z-index:1"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none"
                            style="z-index:2;">
                            <div class="text-center">
                                <div class="text-2xl font-extrabold tabular-nums">
                                    {{ \App\Helpers\CurrencyHelper::format($totalCosts, true) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Total Expenses</div>
                            </div>
                        </div>
                    </div>
                    <ul class="legend list-unstyled m-0" style="flex:1 1 auto; font-size:12px; line-height:1.4; max-height:280px; overflow-y:auto;">
                        @foreach ($costs as $row)
                            <li class="d-flex align-items-center mb-2"
                                style="display:flex; align-items:center; justify-content:space-between; gap:.5rem; padding:4px 0;">
                                <div style="display:flex; align-items:center; gap:.5rem; flex:1;">
                                    <span
                                        style="display:inline-block; width:10px; height:10px; border-radius:2px; background: {{ $row['color'] }}; flex-shrink:0;"></span>
                                    <span style="font-weight:500;">{{ $row['label'] }}</span>
                                </div>
                                <div style="text-align:right; font-weight:600; color:var(--text-primary);">
                                    {{ \App\Helpers\CurrencyHelper::format($row['value'], true) }}
                                    @if(isset($row['count']) && $row['count'] > 0)
                                        <span style="font-size:10px; color:var(--text-tertiary); font-weight:normal; display:block;">({{ $row['count'] }} {{ $row['count'] == 1 ? 'expense' : 'expenses' }})</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                        @if(empty($costs) || (count($costs) == 1 && $costs[0]['value'] == 0))
                            <li style="color:var(--text-tertiary); font-size:11px; padding:8px;">
                                No expenses recorded yet. <a href="/admin/expenses/create" style="color:var(--primary); text-decoration:underline;">Add expense</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6" style="width:50%;min-width:0">
            <div
                class="fi-widget w-full p-5 bg-white/80 backdrop-blur rounded-2xl shadow ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
                <div class="flex items-center justify-between mb-4" style="margin: 2rem 0;">
                    <h3 class="text-base font-semibold tracking-tight">Patient Visits Trend</h3>
                    <div
                        class="flex items-center text-xs rounded-lg ring-1 ring-gray-200 overflow-hidden dark:ring-gray-700">
                        <button data-range="monthly"
                            class="range-btn px-3 py-1.5 bg-blue-600 text-white">Monthly</button>
                        <button data-range="weekly" class="range-btn px-3 py-1.5">Weekly</button>
                        <button data-range="daily" class="range-btn px-3 py-1.5">Daily</button>
                    </div>
                </div>
                <canvas id="visitsLine" style="height:280px"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            (function() {
                const donutCtx = document.getElementById('financeDonut');
                if (!donutCtx) return;

                const financeData = @json(collect($costs)->pluck('value'));
                const financeColors = @json(collect($costs)->pluck('color'));
                const financeLabels = @json(collect($costs)->pluck('label'));

                new Chart(donutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: financeLabels,
                        datasets: [{
                            data: financeData,
                            backgroundColor: financeColors,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => {
                                        const value = Number(ctx.raw);
                                        const label = ctx.label || '';
                                        const currency = '{{ \App\Helpers\CurrencyHelper::symbol() }}';
                                        return label + ': ' + currency + value.toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        }
                    }
                });

                const visitsCtx = document.getElementById('visitsLine');
                const monthly = {
                    labels: @json($months),
                    data: @json($visitsMonthly)
                };
                const weekly = {
                    labels: ['W1', 'W2', 'W3', 'W4'],
                    data: [80, 120, 160, 210]
                };
                const daily = {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    data: [10, 15, 25, 20, 30, 35, 40]
                };

                let current = monthly;
                const visitsChart = new Chart(visitsCtx, {
                    type: 'line',
                    data: {
                        labels: current.labels,
                        datasets: [{
                            label: 'Visits',
                            data: current.data,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,.12)',
                            fill: true,
                            tension: .35,
                            pointRadius: 3,
                            pointHoverRadius: 4
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

                document.querySelectorAll('.range-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('.range-btn').forEach(b => b.classList.remove(
                            'bg-blue-600', 'text-white'))
                        btn.classList.add('bg-blue-600', 'text-white')
                        const map = {
                            monthly,
                            weekly,
                            daily
                        };
                        current = map[btn.dataset.range] || monthly;
                        visitsChart.data.labels = current.labels;
                        visitsChart.data.datasets[0].data = current.data;
                        visitsChart.update();
                    })
                })
            })();
        </script>
    @endpush
@endif
