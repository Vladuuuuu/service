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
                                    <th class="px-6 py-3">Deviz</th>
                                    <th class="px-6 py-3 text-right">Cost</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($interventions as $intervention)
                                    <tr class="hover:bg-gray-50/50" x-data="{ devizOpen: {{ $intervention->areDevizPending() ? 'true' : 'false' }} }">
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
                                        <td class="px-6 py-4">
                                            @if($intervention->deviz_status === 'trimis')
                                                <button @click="devizOpen = !devizOpen" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 hover:bg-amber-100 transition">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse inline-block"></span>
                                                    Deviz primit
                                                </button>
                                            @elseif($intervention->deviz_status === 'aprobat')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">Aprobat</span>
                                            @elseif($intervention->deviz_status === 'respins')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">Respins</span>
                                            @else
                                                <span class="text-gray-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium text-gray-900">
                                            {{ $intervention->final_cost ? number_format($intervention->final_cost, 0, ',', '.') . ' RON' : '—' }}
                                        </td>
                                    </tr>
                                    @if($intervention->areDevizPending())
                                        <tr x-data="{ devizOpen: true }" x-show="devizOpen" class="bg-amber-50/50">
                                            <td colspan="7" class="px-6 py-5">
                                                <div class="flex flex-col gap-5">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900 mb-4">Deviz primit de la {{ $intervention->service->name }}</p>

                                                        <div class="bg-white rounded-xl border border-amber-100 overflow-hidden mb-4">
                                                            <table class="w-full text-sm">
                                                                <thead>
                                                                    <tr class="text-xs font-medium text-gray-400 uppercase tracking-wide border-b border-gray-100 bg-gray-50">
                                                                        <th class="px-4 py-2.5 text-left">Piesă / Serviciu</th>
                                                                        <th class="px-4 py-2.5 text-right w-16">Cant.</th>
                                                                        <th class="px-4 py-2.5 text-right w-28">Preț/buc</th>
                                                                        <th class="px-4 py-2.5 text-right w-28">Subtotal</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr class="border-b border-gray-50">
                                                                        <td class="px-4 py-3 font-medium text-gray-900">Manoperă</td>
                                                                        <td class="px-4 py-3 text-right text-gray-500">1</td>
                                                                        <td class="px-4 py-3 text-right text-gray-700">{{ number_format($intervention->deviz_manopera, 2, ',', '.') }} RON</td>
                                                                        <td class="px-4 py-3 text-right font-semibold text-gray-900">{{ number_format($intervention->deviz_manopera, 2, ',', '.') }} RON</td>
                                                                    </tr>
                                                                    @foreach($intervention->parts as $part)
                                                                        <tr class="border-b border-gray-50">
                                                                            <td class="px-4 py-3 text-gray-700">{{ $part->name }}</td>
                                                                            <td class="px-4 py-3 text-right text-gray-500">{{ $part->quantity }}</td>
                                                                            <td class="px-4 py-3 text-right text-gray-700">{{ number_format($part->unit_price, 2, ',', '.') }} RON</td>
                                                                            <td class="px-4 py-3 text-right font-semibold text-gray-900">{{ number_format($part->subtotal(), 2, ',', '.') }} RON</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr class="bg-gray-50">
                                                                        <td colspan="3" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</td>
                                                                        <td class="px-4 py-3 text-right font-bold text-gray-900 text-base">{{ number_format($intervention->devizTotal(), 2, ',', '.') }} RON</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <p class="text-xs text-gray-400">Dacă accepți devizul, alegi <strong>Aprobă</strong>. Dacă îl respingi, lucrarea se oprește și se facturează doar manopera efectuată ({{ number_format($intervention->deviz_manopera, 2, ',', '.') }} RON).</p>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <form method="POST" action="{{ route('client.interventions.deviz.approve', $intervention) }}">
                                                            @csrf
                                                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition">Aprobă</button>
                                                        </form>
                                                        <form method="POST" action="{{ route('client.interventions.deviz.reject', $intervention) }}">
                                                            @csrf
                                                            <button type="submit" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-xl text-sm font-semibold hover:bg-red-50 transition"
                                                                    onclick="return confirm('Ești sigur că vrei să refuzi devizul? Se va emite o factură pentru manopera efectuată și intervenția se va închide.')">
                                                                Respinge
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
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
