{{-- Partial: rezumat deviz cu piese --}}
<div class="text-sm">
    <div class="bg-gray-50 rounded-xl overflow-hidden mb-3">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs font-medium text-gray-400 uppercase tracking-wide border-b border-gray-100">
                    <th class="px-4 py-2.5 text-left">Piesă / Serviciu</th>
                    <th class="px-4 py-2.5 text-right w-16">Cant.</th>
                    <th class="px-4 py-2.5 text-right w-28">Preț/buc</th>
                    <th class="px-4 py-2.5 text-right w-28">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-100">
                    <td class="px-4 py-2.5 font-medium text-gray-900">Manoperă</td>
                    <td class="px-4 py-2.5 text-right text-gray-500">1</td>
                    <td class="px-4 py-2.5 text-right text-gray-700">{{ number_format($intervention->deviz_manopera, 2, ',', '.') }} RON</td>
                    <td class="px-4 py-2.5 text-right font-semibold text-gray-900">{{ number_format($intervention->deviz_manopera, 2, ',', '.') }} RON</td>
                </tr>
                @foreach($intervention->parts as $part)
                    <tr class="border-b border-gray-100 {{ isset($rejectedMode) && $rejectedMode ? 'opacity-50' : '' }}">
                        <td class="px-4 py-2.5 text-gray-700">
                            {{ $part->name }}
                            @if(isset($rejectedMode) && $rejectedMode)
                                <span class="ml-1.5 text-xs text-red-400">(neefectuată)</span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-right text-gray-500">{{ $part->quantity }}</td>
                        <td class="px-4 py-2.5 text-right text-gray-700">{{ number_format($part->unit_price, 2, ',', '.') }} RON</td>
                        <td class="px-4 py-2.5 text-right font-semibold text-gray-900">{{ number_format($part->subtotal(), 2, ',', '.') }} RON</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between px-1">
        @if(isset($rejectedMode) && $rejectedMode)
            <span class="text-xs text-gray-400">Facturat (doar manoperă)</span>
            <span class="font-bold text-gray-900">{{ number_format($intervention->deviz_manopera, 2, ',', '.') }} RON</span>
        @else
            <span class="text-xs text-gray-400">Total deviz</span>
            <span class="font-bold text-gray-900">{{ number_format($intervention->devizTotal(), 2, ',', '.') }} RON</span>
        @endif
    </div>
</div>
