<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">Panou de administrare service</p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">În așteptare</p>
                    <p class="text-3xl font-bold text-amber-600 mt-1">{{ $pendingCount }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">În lucru</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $inProgressCount }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Finalizate</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $completedCount }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Venituri</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">RON</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Pending --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">Programări noi</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700">{{ $pendingCount }}</span>
                    </div>
                    @if($pendingInterventions->count())
                        <div class="divide-y divide-gray-50">
                            @foreach($pendingInterventions as $intervention)
                                <a href="{{ route('service.interventions.show', $intervention) }}" class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $intervention->car->brand }} {{ $intervention->car->model }} <span class="text-gray-400 font-normal">· {{ $intervention->car->plate }}</span></p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $intervention->car->user->name }} · {{ ucfirst($intervention->type) }}</p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs text-gray-400">{{ $intervention->scheduled_at?->format('d.m.Y') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-10 text-center text-sm text-gray-400">Nicio programare nouă</div>
                    @endif
                </div>

                {{-- In Progress --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">În lucru</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">{{ $inProgressCount }}</span>
                    </div>
                    @if($activeInterventions->count())
                        <div class="divide-y divide-gray-50">
                            @foreach($activeInterventions as $intervention)
                                <a href="{{ route('service.interventions.show', $intervention) }}" class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $intervention->car->brand }} {{ $intervention->car->model }} <span class="text-gray-400 font-normal">· {{ $intervention->car->plate }}</span></p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $intervention->car->user->name }} · {{ ucfirst($intervention->type) }}</p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-medium text-gray-600">{{ $intervention->estimated_hours ? $intervention->estimated_hours . 'h' : '' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-10 text-center text-sm text-gray-400">Nicio lucrare activă</div>
                    @endif
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('service.interventions') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 transition">Vezi toate intervențiile →</a>
            </div>

        </div>
    </div>
</x-app-layout>
