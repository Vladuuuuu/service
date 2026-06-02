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

            <form method="POST" action="{{ route('service.settings.update') }}" class="space-y-6">
                @csrf @method('PATCH')

                {{-- Profil --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50">
                        <h2 class="text-sm font-semibold text-gray-900">Profil service</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Informații afișate clienților pe pagina serviciului</p>
                    </div>
                    <div class="p-6 space-y-5">
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
                                <input id="phone" name="phone" type="text" value="{{ old('phone', $service->phone) }}" placeholder="ex: 0722 123 456"
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                @error('phone') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Adresa</label>
                            <input id="address" name="address" type="text" value="{{ old('address', $service->address) }}" required
                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                            @error('address') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email contact <span class="text-gray-400 font-normal">— opțional</span></label>
                                <input id="email" name="email" type="email" value="{{ old('email', $service->email) }}" placeholder="ex: contact@service.ro"
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 mb-1.5">Website <span class="text-gray-400 font-normal">— opțional</span></label>
                                <input id="website" name="website" type="url" value="{{ old('website', $service->website) }}" placeholder="ex: https://service.ro"
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                @error('website') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Descriere</label>
                            <textarea id="description" name="description" rows="3" placeholder="Scurtă descriere a serviciilor oferite..."
                                      class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition resize-none">{{ old('description', $service->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Program & disponibilitate --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50">
                        <h2 class="text-sm font-semibold text-gray-900">Program & disponibilitate</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Afectează direct calendarul de programări al clienților</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label for="schedule_start" class="block text-sm font-medium text-gray-700 mb-1.5">Deschidere</label>
                                <input id="schedule_start" name="schedule_start" type="time"
                                       value="{{ old('schedule_start', \Carbon\Carbon::parse($service->schedule_start ?? '08:00')->format('H:i')) }}"
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                @error('schedule_start') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="schedule_end" class="block text-sm font-medium text-gray-700 mb-1.5">Închidere</label>
                                <input id="schedule_end" name="schedule_end" type="time"
                                       value="{{ old('schedule_end', \Carbon\Carbon::parse($service->schedule_end ?? '17:00')->format('H:i')) }}"
                                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                @error('schedule_end') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="max_daily_slots" class="block text-sm font-medium text-gray-700 mb-1.5">Programări maxime pe zi</label>
                            <input id="max_daily_slots" name="max_daily_slots" type="number" min="1" max="50"
                                   value="{{ old('max_daily_slots', $service->max_daily_slots ?? 5) }}"
                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                            <p class="text-xs text-gray-400 mt-1.5">Zilele cu toate sloturile ocupate apar în roșu pe calendar</p>
                            @error('max_daily_slots') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">Salvează setările</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
