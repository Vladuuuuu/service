<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-medium ring-1 ring-emerald-200 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="mb-10">
                <h1 class="text-2xl font-bold text-gray-900">Bună, {{ $user->name }}</h1>
                <p class="text-gray-500 mt-1">Iată ce se întâmplă cu mașinile tale.</p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $cars->count() }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Mașini</div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $activeInterventions->count() }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Active acum</div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $invoiceCount }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Facturi</div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($totalSpent, 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">RON</span></div>
                    <div class="text-xs text-gray-400 mt-0.5">Total cheltuit</div>
                </div>
            </div>

            {{-- Active interventions --}}
            @if($activeInterventions->isNotEmpty())
            <div class="mb-10">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Intervenții active</h2>
                <div class="space-y-3">
                    @foreach($activeInterventions as $intervention)
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex items-center gap-4 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $intervention->status === 'in_progress' ? 'bg-amber-50' : 'bg-gray-50' }}">
                                @if($intervention->status === 'in_progress')
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                                @else
                                    <span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-900 truncate">{{ $intervention->car->brand }} {{ $intervention->car->model }}</div>
                                <div class="text-sm text-gray-500 truncate">{{ $intervention->description }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 flex-shrink-0">
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">{{ $intervention->service->name }}</div>
                                <div class="text-xs text-gray-400">{{ $intervention->scheduled_at?->format('d.m.Y') ?? 'Neplanificat' }}</div>
                            </div>
                            @php $statusBadge = ['pending' => 'bg-gray-100 text-gray-600', 'in_progress' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200']; @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusBadge[$intervention->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $intervention->status === 'in_progress' ? 'În lucru' : 'În așteptare' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Cars --}}
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Mașinile tale</h2>
                    <a href="{{ route('client.cars.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Adaugă
                    </a>
                </div>

                @if($cars->isEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <p class="text-gray-400 text-sm mb-4">Nu ai adăugat nicio mașină.</p>
                        <a href="{{ route('client.cars.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">Adaugă prima mașină</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($cars as $car)
                        <a href="{{ route('client.cars.show', $car) }}" class="group bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-lg hover:shadow-gray-200/50 hover:-translate-y-0.5 transition-all duration-200">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $car->brand }} {{ $car->model }}</h3>
                                    <span class="text-xs text-gray-400">{{ $car->year }}</span>
                                </div>
                                <div class="px-2 py-1 rounded-lg bg-gray-50 text-xs font-mono font-medium text-gray-600">{{ $car->plate }}</div>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">{{ number_format($car->km_current, 0, ',', '.') }} km</span>
                                <span class="text-gray-400">{{ $car->interventions_count }} intervenții</span>
                            </div>
                            @php $active = $car->interventions->first(fn($i) => $i->status === 'in_progress'); @endphp
                            @if($active)
                                <div class="mt-3 flex items-center gap-2 text-xs text-amber-700 bg-amber-50 rounded-lg px-3 py-1.5 ring-1 ring-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    În service
                                </div>
                            @endif
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent interventions --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Activitate recentă</h2>
                    <a href="{{ route('client.interventions') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Vezi toate</a>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    @if($recentInterventions->isEmpty())
                        <div class="p-12 text-center">
                            <p class="text-gray-400 text-sm">Nu ai nicio intervenție înregistrată.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Mașină</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Service</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Descriere</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($recentInterventions as $intervention)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-5 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $intervention->car->brand }} {{ $intervention->car->model }}</td>
                                        <td class="px-5 py-4 text-gray-600 whitespace-nowrap">{{ $intervention->service->name }}</td>
                                        <td class="px-5 py-4 text-gray-500 max-w-[200px] truncate">{{ $intervention->description }}</td>
                                        <td class="px-5 py-4">
                                            @php
                                                $sc = ['pending' => 'bg-gray-100 text-gray-600', 'in_progress' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200', 'completed' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200', 'cancelled' => 'bg-red-50 text-red-600 ring-1 ring-red-200'];
                                                $sl = ['pending' => 'În așteptare', 'in_progress' => 'În lucru', 'completed' => 'Finalizat', 'cancelled' => 'Anulat'];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc[$intervention->status] ?? '' }}">{{ $sl[$intervention->status] ?? $intervention->status }}</span>
                                        </td>
                                        <td class="px-5 py-4 text-right font-semibold text-gray-900 whitespace-nowrap">{{ $intervention->final_cost ? number_format($intervention->final_cost, 0, ',', '.') . ' RON' : '—' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
