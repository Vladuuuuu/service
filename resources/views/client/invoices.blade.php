<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Facturi</h1>
                <p class="text-sm text-gray-500 mt-1">Toate facturile asociate intervențiilor tale</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                @if($invoices->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-400 uppercase tracking-wide border-b border-gray-50">
                                    <th class="px-6 py-3">Nr. factură</th>
                                    <th class="px-6 py-3">Dată</th>
                                    <th class="px-6 py-3">Mașină</th>
                                    <th class="px-6 py-3">Service</th>
                                    <th class="px-6 py-3 text-right">Total</th>
                                    <th class="px-6 py-3 text-right">PDF</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($invoices as $invoice)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $invoice->number }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $invoice->issued_at ? \Carbon\Carbon::parse($invoice->issued_at)->format('d.m.Y') : $invoice->created_at->format('d.m.Y') }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $invoice->intervention->car->brand }} {{ $invoice->intervention->car->model }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $invoice->intervention->service->name }}</td>
                                        <td class="px-6 py-4 text-right font-semibold text-gray-900">{{ number_format($invoice->total, 0, ',', '.') }} RON</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('invoices.pdf', $invoice) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Descarcă
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-50">
                        {{ $invoices->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <p class="text-sm text-gray-400">Nicio factură emisă</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
