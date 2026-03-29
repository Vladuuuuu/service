<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-3 mb-8">
                <a href="{{ route('service.dashboard') }}" class="p-2 rounded-xl hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Setări service</h1>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-6 text-sm text-emerald-700">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 p-8">
                <form method="POST" action="{{ route('service.settings.update') }}">
                    @csrf @method('PATCH')

                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Numele service-ului</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $service->name) }}" required
                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                            @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1.5">Oraș</label>
                                <input id="city" name="city" type="text" value="{{ old('city', $service->city) }}" required
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                @error('city') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Telefon</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone', $service->phone) }}"
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                @error('phone') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Adresa</label>
                            <input id="address" name="address" type="text" value="{{ old('address', $service->address) }}" required
                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                            @error('address') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Descriere</label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition resize-none">{{ old('description', $service->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">Salvează setările</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
