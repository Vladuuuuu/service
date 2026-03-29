<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-3 mb-8">
                <a href="{{ route('services.index') }}" class="p-2 rounded-xl hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $service->city }} · {{ $service->address }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Info --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-900">{{ $service->rating ? number_format($service->rating, 1) : '—' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Rating</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-900">{{ $service->interventions_count }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Intervenții</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-900">{{ $service->phone ?: '—' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Telefon</p>
                            </div>
                        </div>

                        @if($service->description)
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $service->description }}</p>
                        @endif
                    </div>

                    {{-- Map --}}
                    @if($service->lat && $service->lng)
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div id="map" class="h-64 w-full"></div>
                    </div>
                    @endif
                </div>

                {{-- Booking --}}
                <div>
                    @auth
                        @if(auth()->user()->isClient() && $cars->count())
                            <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-24">
                                <h3 class="text-base font-semibold text-gray-900 mb-5">Programează-te</h3>

                                <form method="POST" action="{{ route('services.book', $service) }}">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Mașina</label>
                                            <select name="car_id" required class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                                                @foreach($cars as $car)
                                                    <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>{{ $car->brand }} {{ $car->model }} — {{ $car->plate }}</option>
                                                @endforeach
                                            </select>
                                            @error('car_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tip intervenție</label>
                                            <select name="type" required class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                                                <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>General</option>
                                                <option value="ulei" {{ old('type') === 'ulei' ? 'selected' : '' }}>Schimb ulei</option>
                                                <option value="revizie" {{ old('type') === 'revizie' ? 'selected' : '' }}>Revizie</option>
                                                <option value="frane" {{ old('type') === 'frane' ? 'selected' : '' }}>Frâne</option>
                                            </select>
                                            @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Dată dorită</label>
                                            <input type="date" name="scheduled_at" required value="{{ old('scheduled_at') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                            @error('scheduled_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Km actuali</label>
                                            <input type="number" name="km_at_intervention" value="{{ old('km_at_intervention') }}" min="0" placeholder="Opțional"
                                                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                                            @error('km_at_intervention') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Descriere problemă</label>
                                            <textarea name="description" required rows="3" placeholder="Descrie pe scurt problema..."
                                                      class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition resize-none">{{ old('description') }}</textarea>
                                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">Trimite programarea</button>
                                    </div>
                                </form>
                            </div>
                        @elseif(auth()->user()->isClient())
                            <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
                                <p class="text-sm text-gray-500 mb-3">Adaugă o mașină pentru a te programa</p>
                                <a href="{{ route('client.cars.create') }}" class="inline-flex px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">Adaugă mașină</a>
                            </div>
                        @endif
                    @else
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
                            <p class="text-sm text-gray-500 mb-3">Conectează-te pentru a te programa</p>
                            <a href="{{ route('login') }}" class="inline-flex px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">Autentifică-te</a>
                        </div>
                    @endauth
                </div>
            </div>

        </div>
    </div>

    @if($service->lat && $service->lng)
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9/dist/leaflet.css" />
    @endpush
    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([{{ $service->lat }}, {{ $service->lng }}], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
            L.marker([{{ $service->lat }}, {{ $service->lng }}]).addTo(map).bindPopup('{{ $service->name }}');
        });
    </script>
    @endpush
    @endif
</x-app-layout>
