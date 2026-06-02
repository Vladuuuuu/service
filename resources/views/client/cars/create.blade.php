<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-3 mb-8">
                <a href="{{ route('client.cars.index') }}" class="p-2 rounded-xl hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Adaugă mașină nouă</h1>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-8">
                <form method="POST" action="{{ route('client.cars.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-1.5">Marca</label>
                            <input id="brand" name="brand" type="text" value="{{ old('brand') }}" required placeholder="ex: BMW"
                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                            @error('brand') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700 mb-1.5">Model</label>
                            <input id="model" name="model" type="text" value="{{ old('model') }}" required placeholder="ex: Seria 3 F30"
                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                            @error('model') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-1.5">An fabricație</label>
                            <input id="year" name="year" type="number" value="{{ old('year') }}" required min="1900" max="{{ date('Y') + 1 }}" placeholder="ex: 2018"
                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                            @error('year') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="plate" class="block text-sm font-medium text-gray-700 mb-1.5">Nr. Înmatriculare</label>
                            <input id="plate" name="plate" type="text" value="{{ old('plate') }}" required placeholder="ex: BV-12-ABC"
                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                            @error('plate') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="vin" class="block text-sm font-medium text-gray-700 mb-1.5">Serie caroserie (VIN) <span class="text-gray-400 font-normal">— opțional</span></label>
                            <input id="vin" name="vin" type="text" value="{{ old('vin') }}" maxlength="17" placeholder="ex: WBA3A5G59DNP26082"
                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition uppercase" />
                            @error('vin') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="km_current" class="block text-sm font-medium text-gray-700 mb-1.5">Kilometraj actual</label>
                            <input id="km_current" name="km_current" type="number" value="{{ old('km_current') }}" required min="0" placeholder="ex: 125000"
                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                            @error('km_current') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 gap-3">
                        <a href="{{ route('client.cars.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-xl transition">Anulează</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">Salvează mașina</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
