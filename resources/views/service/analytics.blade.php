<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">Statistici & analiză</p>
            </div>

            {{-- KPI Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total intervenții</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Finalizate</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Venituri totale</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['revenue'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">RON</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Cost mediu</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">{{ number_format($stats['avg_cost'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">RON / intervenție</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Timp mediu</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ number_format($stats['avg_hours'], 1) }}h</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Clienți unici</p>
                    <p class="text-3xl font-bold text-violet-600 mt-1">{{ $stats['clients'] }}</p>
                </div>
            </div>

            {{-- Grafice rând 1 --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                {{-- Intervenții pe luni --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Intervenții pe luni</h2>
                    <div style="height:220px">
                        <canvas id="chartMonthly"></canvas>
                    </div>
                </div>

                {{-- Distribuție tip --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Tipuri de intervenții</h2>
                    <div style="height:220px;display:flex;align-items:center;justify-content:center">
                        <canvas id="chartTypes"></canvas>
                    </div>
                </div>
            </div>

            {{-- Grafice rând 2 --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                {{-- Venituri pe luni --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Venituri pe luni (RON)</h2>
                    <div style="height:220px">
                        <canvas id="chartRevenue"></canvas>
                    </div>
                </div>

                {{-- Top mărci --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Top mărci deservite</h2>
                    <div style="height:220px">
                        <canvas id="chartBrands"></canvas>
                    </div>
                </div>
            </div>

            {{-- Grafice rând 3 --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Timp mediu pe tip --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Timp mediu manoperă pe tip (ore)</h2>
                    @if($avgHoursByType->count())
                        <div style="height:200px">
                            <canvas id="chartAvgHours"></canvas>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-32 text-sm text-gray-400">Date insuficiente</div>
                    @endif
                </div>

                {{-- Statusuri deviz --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Statusuri devize</h2>
                    @if($totalWithDeviz > 0)
                        <div style="height:200px;display:flex;align-items:center;justify-content:center">
                            <canvas id="chartDeviz"></canvas>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-32 text-sm text-gray-400">Niciun deviz trimis încă</div>
                    @endif

                    @if($totalWithDeviz > 0)
                    <div class="flex justify-center gap-6 mt-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full bg-emerald-400"></span>Aprobate: {{ $approved }}</span>
                        <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full bg-red-400"></span>Respinse: {{ $rejected }}</span>
                        <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full bg-amber-400"></span>În așteptare: {{ $pending }}</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = 'inherit';
Chart.defaults.color = '#9ca3af';

const monthlyLabels = @json($monthlyLabels);
const monthlyCounts = @json($monthlyCounts);
const monthlyRevenue = @json($monthlyRevenue);
const typeLabels = @json($typeLabels);
const typeCounts = @json($typeCounts);
const brandLabels = @json($topBrands->keys());
const brandCounts = @json($topBrands->values());
const avgHoursLabels = @json($avgHoursLabels);
const avgHoursData = @json($avgHoursByType);

const palette = ['#6366f1','#22d3ee','#34d399','#f59e0b','#f87171','#a78bfa'];

// Intervenții pe luni
new Chart(document.getElementById('chartMonthly'), {
    type: 'bar',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Intervenții',
            data: monthlyCounts,
            backgroundColor: '#6366f1',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});

// Tipuri intervenții donut
if (typeCounts.length) new Chart(document.getElementById('chartTypes'), {
    type: 'doughnut',
    data: {
        labels: typeLabels,
        datasets: [{ data: typeCounts, backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 10, font: { size: 11 } } } },
        cutout: '65%',
    }
});

// Venituri pe luni
new Chart(document.getElementById('chartRevenue'), {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'RON',
            data: monthlyRevenue,
            borderColor: '#34d399',
            backgroundColor: 'rgba(52,211,153,.08)',
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: '#34d399',
            fill: true,
            tension: 0.3,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});

// Top mărci
if (brandCounts.length) new Chart(document.getElementById('chartBrands'), {
    type: 'bar',
    data: {
        labels: brandLabels,
        datasets: [{
            label: 'Intervenții',
            data: brandCounts,
            backgroundColor: palette,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
            y: { grid: { display: false } }
        }
    }
});

// Timp mediu pe tip
if (avgHoursData.length) new Chart(document.getElementById('chartAvgHours'), {
    type: 'bar',
    data: {
        labels: avgHoursLabels,
        datasets: [{
            label: 'Ore',
            data: avgHoursData,
            backgroundColor: '#818cf8',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});

// Devize
@if($totalWithDeviz > 0)
new Chart(document.getElementById('chartDeviz'), {
    type: 'doughnut',
    data: {
        labels: ['Aprobate', 'Respinse', 'În așteptare'],
        datasets: [{ data: [{{ $approved }}, {{ $rejected }}, {{ $pending }}], backgroundColor: ['#34d399','#f87171','#fbbf24'], borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '60%',
    }
});
@endif
</script>
@endpush
</x-app-layout>
