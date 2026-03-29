<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Service-uri auto</h1>
                <p class="text-sm text-gray-500 mt-1">Alege un service și programează-te online</p>
            </div>

            {{-- Filters --}}
            <form method="GET" class="flex flex-col sm:flex-row gap-3 mb-8">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Caută service..."
                       class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition" />
                <select name="city" onchange="this.form.submit()"
                        class="rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                    <option value="">Toate orașele</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-5 py-3 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">Caută</button>
            </form>

            {{-- Results --}}
            @if($services->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($services as $service)
                        <a href="{{ route('services.show', $service) }}" class="group bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition">{{ $service->name }}</h3>
                                @if($service->rating)
                                    <span class="inline-flex items-center gap-1 text-sm font-medium text-amber-600">
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        {{ number_format($service->rating, 1) }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $service->description ?: 'Service auto profesional' }}</p>
                            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $service->city }} · {{ $service->address }}
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <p class="text-gray-400 text-sm">Niciun service găsit.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
