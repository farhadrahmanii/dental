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
@endphp

<div class="row g-3" style="display:flex;gap:1.5rem;flex-wrap:nowrap;width:100%">
    <div class="col-md-6" style="width:50%;min-width:0">
        <div
            class="fi-widget w-full p-5 bg-white/80 backdrop-blur rounded-2xl shadow ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold tracking-tight">Financial Overview</h3>
            </div>
            <div class="flex gap-4 items-center" style="height:280px">
                <div class="flex-1 h-full overflow-auto pr-2">
                    <ul class="space-y-2.5 text-sm">
                        @php
                            $costs = [
                                ['label' => 'مصارف تجهیزات', 'value' => 450000, 'color' => '#2563eb'],
                                ['label' => 'قبض کرایه (فرض)', 'value' => 250000, 'color' => '#22c55e'],
                                ['label' => 'معاش عمومی', 'value' => 200000, 'color' => '#a855f7'],
                                ['label' => 'پُل خدمات', 'value' => 150000, 'color' => '#f59e0b'],
                                ['label' => 'فرعی خدمت', 'value' => 100000, 'color' => '#06b6d4'],
                                ['label' => 'قرطاسیه و لوازم', 'value' => 75000, 'color' => '#ef4444'],
                                ['label' => 'مصارف لوازم‌یار', 'value' => 25000, 'color' => '#eab308'],
                            ];
                            $totalCosts = collect($costs)->sum('value');
                        @endphp
                        @foreach ($costs as $row)
                            <li class="flex items-center gap-2.5">
                                <span class="inline-block w-2.5 h-2.5 rounded-full"
                                    style="background: {{ $row['color'] }}"></span>
                                <span class="font-semibold tabular-nums">${{ number_format($row['value']) }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $row['label'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="relative" style="width:280px;height:280px">
                    <canvas id="financeDonut" style="width:280px;height:280px"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="text-center">
                            <div class="text-2xl font-extrabold tabular-nums">
                                ${{ number_format($totalCosts / 1000000, 2) }}M</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total Costs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6" style="width:50%;min-width:0">
        <div
            class="fi-widget w-full p-5 bg-white/80 backdrop-blur rounded-2xl shadow ring-1 ring-gray-100 dark:bg-gray-900/70 dark:ring-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold tracking-tight">Patient Visits Trend</h3>
                <div
                    class="flex items-center text-xs rounded-lg ring-1 ring-gray-200 overflow-hidden dark:ring-gray-700">
                    <button data-range="monthly" class="range-btn px-3 py-1.5 bg-blue-600 text-white">Monthly</button>
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
                                label: (ctx) => '$' + Number(ctx.raw).toLocaleString()
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
