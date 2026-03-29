<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Intervenții</h1>
                    <p class="text-sm text-gray-500 mt-1">Toate intervențiile din service-ul tău</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="flex gap-2 mb-6">
                <a href="{{ route('service.interventions') }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition {{ !request('status') ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300' }}">Toate</a>
                @foreach(['pending' => 'În așteptare', 'in_progress' => 'În lucru', 'completed' => 'Finalizate', 'cancelled' => 'Anulate'] as $key => $label)
                    <a href="{{ route('service.interventions', ['status' => $key]) }}"
                       class="px-4 py-2 rounded-xl text-sm font-medium transition {{ request('status') === $key ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300' }}">{{ $label }}</a>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                @if($interventions->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-400 uppercase tracking-wide border-b border-gray-50">
                                    <th class="px-6 py-3">Dată</th>
                                    <th class="px-6 py-3">Client</th>
                                    <th class="px-6 py-3">Mașină</th>
                                    <th class="px-6 py-3">Tip</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Cost</th>
                                    <th class="px-6 py-3 text-right">Factură</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($interventions as $intervention)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-6 py-4 text-gray-500">{{ $intervention->scheduled_at?->format('d.m.Y') ?? $intervention->created_at->format('d.m.Y') }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $intervention->car->user->name }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $intervention->car->brand }} {{ $intervention->car->model }} <span class="text-gray-400">· {{ $intervention->car->plate }}</span></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ match($intervention->type) { 'ulei' => 'bg-yellow-50 text-yellow-700', 'revizie' => 'bg-blue-50 text-blue-700', 'frane' => 'bg-red-50 text-red-700', default => 'bg-gray-50 text-gray-700' } }}">{{ ucfirst($intervention->type) }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ match($intervention->status) { 'completed' => 'bg-emerald-50 text-emerald-700', 'in_progress' => 'bg-blue-50 text-blue-700', 'pending' => 'bg-amber-50 text-amber-700', 'cancelled' => 'bg-red-50 text-red-700', default => 'bg-gray-50 text-gray-700' } }}">
                                                {{ match($intervention->status) { 'completed' => 'Finalizat', 'in_progress' => 'În lucru', 'pending' => 'Programat', 'cancelled' => 'Anulat', default => $intervention->status } }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium text-gray-900">{{ $intervention->final_cost ? number_format($intervention->final_cost, 0, ',', '.') . ' RON' : '—' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            @if($intervention->invoice)
                                                <span class="text-xs text-emerald-600 font-medium">{{ $intervention->invoice->number }}</span>
                                            @else
                                                <span class="text-xs text-gray-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('service.interventions.show', $intervention) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 transition">Detalii</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-50">
                        {{ $interventions->withQueryString()->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <p class="text-sm text-gray-400">Nicio intervenție găsită</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
