<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Mașinile mele</h1>
                    <p class="text-gray-500 text-sm mt-1">Gestionează vehiculele tale și vezi istoricul fiecăreia.</p>
                </div>
                <a href="{{ route('client.cars.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Adaugă mașină
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-medium ring-1 ring-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            @if($cars->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Nu ai nicio mașină</h3>
                    <p class="text-gray-400 text-sm mb-6">Adaugă prima ta mașină pentru a putea face programări.</p>
                    <a href="{{ route('client.cars.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">Adaugă mașină</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($cars as $car)
                    <a href="{{ route('client.cars.show', $car) }}" class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg hover:shadow-gray-200/50 hover:-translate-y-0.5 transition-all duration-200">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-5">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $car->brand }} {{ $car->model }}</h3>
                                    <span class="text-sm text-gray-400">{{ $car->year }}</span>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-400">Nr. înmatriculare</span>
                                    <span class="text-sm font-mono font-medium text-gray-700 bg-gray-50 px-2 py-0.5 rounded">{{ $car->plate }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-400">Kilometraj</span>
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($car->km_current, 0, ',', '.') }} km</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-400">Intervenții</span>
                                    <span class="text-sm font-medium text-gray-700">{{ $car->interventions_count }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-xs font-semibold text-indigo-600 group-hover:text-indigo-700">Vezi detalii</span>
                            <svg class="w-4 h-4 text-indigo-600 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
