<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-8">
                <a href="{{ route('service.interventions') }}" class="p-2 rounded-xl hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $intervention->car->brand }} {{ $intervention->car->model }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $intervention->car->plate }} · {{ $intervention->car->user->name }}</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-6 text-sm text-emerald-700">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Info --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Status & Quick Actions --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-sm font-semibold text-gray-900">Status intervenție</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
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
                        </div>

                        <form method="POST" action="{{ route('service.interventions.status', $intervention) }}" class="flex flex-wrap gap-2">
                            @csrf @method('PATCH')
                            @if($intervention->status === 'pending')
                                <button type="submit" name="status" value="in_progress" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition">Acceptă — Începe lucrul</button>
                                <button type="submit" name="status" value="cancelled" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-xl text-sm font-medium hover:bg-red-50 transition">Refuză</button>
                            @elseif($intervention->status === 'in_progress')
                                <button type="submit" name="status" value="completed" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition">Marchează finalizat</button>
                                <button type="submit" name="status" value="cancelled" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-xl text-sm font-medium hover:bg-red-50 transition">Anulează</button>
                            @elseif($intervention->status === 'cancelled')
                                <button type="submit" name="status" value="pending" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Reactivează</button>
                            @endif
                        </form>
                    </div>

                    {{-- Details --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-5">Detalii intervenție</h3>

                        <form method="POST" action="{{ route('service.interventions.update', $intervention) }}">
                            @csrf @method('PATCH')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Ore estimate</label>
                                    <input type="number" name="estimated_hours" step="0.5" min="0" value="{{ old('estimated_hours', $intervention->estimated_hours) }}"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Cost final (RON)</label>
                                    <input type="number" name="final_cost" step="0.01" min="0" value="{{ old('final_cost', $intervention->final_cost) }}"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Km la intervenție</label>
                                    <input type="number" name="km_at_intervention" min="0" value="{{ old('km_at_intervention', $intervention->km_at_intervention) }}"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                    <p class="mt-1 text-xs text-gray-400">Se actualizează automat km-ul curent al mașinii.</p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Descriere lucrări</label>
                                <textarea name="description" rows="3" required
                                          class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition resize-none">{{ old('description', $intervention->description) }}</textarea>
                            </div>
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">Salvează</button>
                        </form>
                    </div>

                    {{-- Deviz --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-5">Deviz</h3>

                        @if($intervention->deviz_status === 'aprobat')
                            <div class="flex items-center gap-3 p-4 bg-emerald-50 rounded-xl mb-4">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <p class="text-sm text-emerald-700 font-medium">Deviz aprobat de client</p>
                            </div>
                            @include('service._deviz-summary', ['intervention' => $intervention])
                        @elseif($intervention->deviz_status === 'respins')
                            <div class="flex items-center gap-3 p-4 bg-red-50 rounded-xl mb-4">
                                <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <p class="text-sm text-red-700 font-medium">Deviz respins de client — factură emisă pentru manoperă</p>
                            </div>
                            @include('service._deviz-summary', ['intervention' => $intervention, 'rejectedMode' => true])
                        @elseif($intervention->deviz_status === 'trimis')
                            <div class="flex items-center gap-3 p-4 bg-amber-50 rounded-xl mb-4">
                                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-amber-700 font-medium">Deviz trimis — așteptare răspuns client</p>
                            </div>
                            @include('service._deviz-summary', ['intervention' => $intervention])
                        @elseif($intervention->status === 'in_progress')
                            <form method="POST" action="{{ route('service.interventions.deviz', $intervention) }}"
                                  x-data="{
                                      parts: [{ name: '', quantity: 1, unit_price: '' }],
                                      addPart() { this.parts.push({ name: '', quantity: 1, unit_price: '' }) },
                                      removePart(i) { this.parts.splice(i, 1) },
                                      partsTotal() { return this.parts.reduce((s, p) => s + (parseFloat(p.quantity)||0) * (parseFloat(p.unit_price)||0), 0) },
                                      grandTotal(manopera) { return (parseFloat(manopera)||0) + this.partsTotal() }
                                  }">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Manoperă (RON)</label>
                                    <input type="number" name="deviz_manopera" x-ref="manopera" step="0.01" min="0" required value="{{ old('deviz_manopera') }}"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                    @error('deviz_manopera') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-xs font-medium text-gray-500">Piese folosite</label>
                                        <button type="button" @click="addPart()" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">+ Adaugă piesă</button>
                                    </div>

                                    <div class="space-y-2">
                                        <template x-for="(part, i) in parts" :key="i">
                                            <div class="grid grid-cols-12 gap-2 items-start">
                                                <div class="col-span-5">
                                                    <input type="text" :name="`parts[${i}][name]`" x-model="part.name" placeholder="Denumire piesă" required
                                                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                                </div>
                                                <div class="col-span-2">
                                                    <input type="number" :name="`parts[${i}][quantity]`" x-model="part.quantity" placeholder="Cant." step="0.01" min="0.01" required
                                                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                                </div>
                                                <div class="col-span-3">
                                                    <input type="number" :name="`parts[${i}][unit_price]`" x-model="part.unit_price" placeholder="Preț/buc (RON)" step="0.01" min="0" required
                                                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                                </div>
                                                <div class="col-span-1 text-right text-xs text-gray-400 pt-2.5" x-text="((parseFloat(part.quantity)||0)*(parseFloat(part.unit_price)||0)).toFixed(2) + ' RON'"></div>
                                                <div class="col-span-1 flex justify-end pt-1.5">
                                                    <button type="button" @click="removePart(i)" x-show="parts.length > 1" class="p-1 text-gray-300 hover:text-red-500 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    @error('parts.*.name') <p class="text-red-500 text-xs mt-1">Denumirea piesei este obligatorie.</p> @enderror
                                    @error('parts.*.unit_price') <p class="text-red-500 text-xs mt-1">Prețul piesei este obligatoriu.</p> @enderror
                                </div>

                                <div class="flex items-center justify-between py-3 border-t border-gray-100 mb-4">
                                    <span class="text-xs text-gray-500">Total estimat deviz</span>
                                    <span class="font-bold text-gray-900" x-text="grandTotal($refs.manopera?.value).toFixed(2) + ' RON'"></span>
                                </div>

                                <p class="text-xs text-gray-400 mb-4">Devizul va fi trimis clientului spre aprobare. Dacă îl respinge, se facturează doar manopera.</p>
                                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">Trimite deviz</button>
                            </form>
                        @else
                            <p class="text-sm text-gray-400">Devizul se poate trimite după ce intervenția este în lucru.</p>
                        @endif
                    </div>

                    {{-- Invoice --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-5">Factură</h3>

                        @if($intervention->invoice)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $intervention->invoice->number }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Emisă {{ $intervention->invoice->issued_at?->format('d.m.Y') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <p class="text-lg font-bold text-gray-900">{{ number_format($intervention->invoice->total, 2, ',', '.') }} RON</p>
                                    <a href="{{ route('invoices.pdf', $intervention->invoice) }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-medium hover:bg-indigo-700 transition inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        PDF
                                    </a>
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('service.interventions.invoice', $intervention) }}" class="flex items-end gap-3">
                                @csrf
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Total factură (RON)</label>
                                    <input type="number" name="total" step="0.01" min="0.01" required value="{{ $intervention->final_cost }}"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                    @error('total') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition shrink-0">Emite factură</button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Informații</h3>
                        <dl class="space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400">Client</dt>
                                <dd class="font-medium text-gray-900">{{ $intervention->car->user->name }}</dd>
                                <dd class="text-xs text-gray-500">{{ $intervention->car->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400">Mașină</dt>
                                <dd class="font-medium text-gray-900">{{ $intervention->car->brand }} {{ $intervention->car->model }} ({{ $intervention->car->year }})</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400">Nr. Înmatriculare</dt>
                                <dd class="font-medium text-gray-900">{{ $intervention->car->plate }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400">Km actuali mașină</dt>
                                <dd class="font-medium text-gray-900">{{ number_format($intervention->car->km_current, 0, ',', '.') }} km</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400">Tip intervenție</dt>
                                <dd class="font-medium text-gray-900">{{ ucfirst($intervention->type) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400">Data programării</dt>
                                <dd class="font-medium text-gray-900">{{ $intervention->scheduled_at?->format('d.m.Y') ?? '—' }}</dd>
                            </div>
                            @if($intervention->completed_at)
                            <div>
                                <dt class="text-xs text-gray-400">Data finalizării</dt>
                                <dd class="font-medium text-gray-900">{{ $intervention->completed_at->format('d.m.Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
