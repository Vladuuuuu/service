<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Intervenții</h1>
                <p class="text-sm text-gray-500 mt-1">Toate intervențiile pentru mașinile tale</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                @if($interventions->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-400 uppercase tracking-wide border-b border-gray-50">
                                    <th class="px-6 py-3">Dată</th>
                                    <th class="px-6 py-3">Mașină</th>
                                    <th class="px-6 py-3">Service</th>
                                    <th class="px-6 py-3">Tip</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Cost</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($interventions as $intervention)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-6 py-4 text-gray-500">{{ $intervention->created_at->format('d.m.Y') }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('client.cars.show', $intervention->car) }}" class="font-medium text-gray-900 hover:text-indigo-600 transition">
                                                {{ $intervention->car->brand }} {{ $intervention->car->model }}
                                            </a>
                                            <span class="text-xs text-gray-400 ml-1">{{ $intervention->car->plate }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $intervention->service->name }}</td>
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
                                        <td class="px-6 py-4 text-right font-medium text-gray-900">
                                            {{ $intervention->final_cost ? number_format($intervention->final_cost, 0, ',', '.') . ' RON' : '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-50">
                        {{ $interventions->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <p class="text-sm text-gray-400">Nicio intervenție înregistrată</p>
                        <a href="{{ route('services.index') }}" class="inline-flex items-center mt-3 text-sm font-medium text-indigo-600 hover:text-indigo-700">Găsește un service →</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
