<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <a href="{{ route('client.cars.index') }}" class="p-2 rounded-xl hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $car->brand }} {{ $car->model }}</h1>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $car->year }} · {{ $car->plate }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('client.cars.history', $car) }}" class="px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 rounded-xl border border-indigo-200 hover:border-indigo-300 transition inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Istoric PDF
                    </a>
                    <a href="{{ route('client.cars.edit', $car) }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-xl border border-gray-200 hover:border-gray-300 transition">Editează</a>
                    <form method="POST" action="{{ route('client.cars.destroy', $car) }}" onsubmit="return confirm('Ești sigur că vrei să ștergi această mașină?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 rounded-xl border border-red-200 hover:border-red-300 transition">Șterge</button>
                    </form>
                </div>
            </div>

            {{-- Info Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Kilometraj</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($car->km_current, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">km</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Intervenții</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $car->interventions->count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">total</p>
                </div>
                @php
                    $totalCost = $car->interventions->sum('final_cost');
                    $lastOilChange = $car->interventions->where('type', 'ulei')->where('status', 'completed')->first();
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Cost total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalCost, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">RON</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Ultimul schimb ulei</p>
                    @if($lastOilChange)
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($lastOilChange->km_at_intervention, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">km</p>
                    @else
                        <p class="text-sm font-medium text-gray-400 mt-2">—</p>
                    @endif
                </div>
            </div>

            {{-- Alerts --}}
            @php
                $activeIntervention = $car->interventions->whereIn('status', ['pending', 'in_progress'])->first();
                $kmSinceOil = $lastOilChange ? $car->km_current - $lastOilChange->km_at_intervention : null;
                $needsOilChange = $kmSinceOil && $kmSinceOil > 15000;
            @endphp

            @if($activeIntervention)
                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5 mb-6 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20 10 10 0 010-20z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-indigo-900">Intervenție activă — {{ $activeIntervention->status === 'pending' ? 'Programată' : 'În lucru' }}</p>
                        <p class="text-sm text-indigo-700 mt-0.5">{{ $activeIntervention->description }} · {{ $activeIntervention->service->name }}</p>
                    </div>
                </div>
            @endif

            @if($needsOilChange)
                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 mb-6 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 2a10 10 0 110 20 10 10 0 010-20z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-amber-900">Schimb ulei recomandat</p>
                        <p class="text-sm text-amber-700 mt-0.5">Au trecut {{ number_format($kmSinceOil, 0, ',', '.') }} km de la ultimul schimb de ulei.</p>
                    </div>
                </div>
            @endif

            {{-- Charts --}}
            @if($car->interventions->where('status', 'completed')->count() >= 2)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Evoluție kilometraj</h3>
                    <canvas id="kmChart" height="200"></canvas>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Tipuri intervenții</h3>
                    <canvas id="typeChart" height="200"></canvas>
                </div>
            </div>
            @endif

            {{-- Intervention History --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50">
                    <h3 class="text-base font-semibold text-gray-900">Istoric intervenții</h3>
                </div>

                @if($car->interventions->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-400 uppercase tracking-wide">
                                    <th class="px-6 py-3">Dată</th>
                                    <th class="px-6 py-3">Service</th>
                                    <th class="px-6 py-3">Tip</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Km</th>
                                    <th class="px-6 py-3 text-right">Cost</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($car->interventions as $intervention)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-6 py-4 text-gray-500">{{ $intervention->created_at->format('d.m.Y') }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $intervention->service->name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ match($intervention->type) {
                                                    'ulei' => 'bg-yellow-50 text-yellow-700',
                                                    'revizie' => 'bg-blue-50 text-blue-700',
                                                    'frane' => 'bg-red-50 text-red-700',
                                                    default => 'bg-gray-50 text-gray-700',
                                                } }}">
                                                {{ ucfirst($intervention->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ match($intervention->status) {
                                                    'completed' => 'bg-emerald-50 text-emerald-700',
                                                    'in_progress' => 'bg-blue-50 text-blue-700',
                                                    'pending' => 'bg-amber-50 text-amber-700',
                                                    'cancelled' => 'bg-red-50 text-red-700',
                                                    default => 'bg-gray-50 text-gray-700',
                                                } }}">
                                                {{ match($intervention->status) {
                                                    'completed' => 'Finalizat',
                                                    'in_progress' => 'În lucru',
                                                    'pending' => 'Programat',
                                                    'cancelled' => 'Anulat',
                                                    default => $intervention->status,
                                                } }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">{{ $intervention->km_at_intervention ? number_format($intervention->km_at_intervention, 0, ',', '.') : '—' }}</td>
                                        <td class="px-6 py-4 text-right font-medium text-gray-900">{{ $intervention->final_cost ? number_format($intervention->final_cost, 0, ',', '.') . ' RON' : '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-400">Nicio intervenție înregistrată</p>
                        <a href="{{ route('services.index') }}" class="inline-flex items-center mt-3 text-sm font-medium text-indigo-600 hover:text-indigo-700">Caută un service →</a>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @if($car->interventions->where('status', 'completed')->count() >= 2)
    @php
        $chartData = $car->interventions->where('status', 'completed')->sortBy('created_at')->values()->map(function($i) {
            return ['date' => $i->created_at->format('d.m.Y'), 'km' => $i->km_at_intervention, 'type' => $i->type];
        });
    @endphp
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const completed = @json($chartData);

            // KM Chart
            new Chart(document.getElementById('kmChart'), {
                type: 'line',
                data: {
                    labels: completed.map(i => i.date),
                    datasets: [{
                        label: 'Kilometraj',
                        data: completed.map(i => i.km),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.08)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#4f46e5',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: '#f3f4f6' }, ticks: { callback: v => v.toLocaleString('ro-RO') } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Type Chart
            const types = {};
            completed.forEach(i => { types[i.type] = (types[i.type] || 0) + 1; });
            const colors = { ulei: '#eab308', revizie: '#3b82f6', frane: '#ef4444', general: '#6b7280' };

            new Chart(document.getElementById('typeChart'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(types).map(t => t.charAt(0).toUpperCase() + t.slice(1)),
                    datasets: [{
                        data: Object.values(types),
                        backgroundColor: Object.keys(types).map(t => colors[t] || '#6b7280'),
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: { legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' } } }
                }
            });
        });
    </script>
    @endpush
    @endif
</x-app-layout>
