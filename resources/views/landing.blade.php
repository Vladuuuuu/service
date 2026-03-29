<!DOCTYPE html>
<html lang="ro" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ServiceAuto — Platforma digitală pentru service-uri auto</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-[Inter] antialiased text-gray-800 bg-white" x-data="{ mobileOpen: false }">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
         x-data="{ scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
         :class="scrolled ? 'bg-white/80 backdrop-blur-xl shadow-sm border-b border-gray-100' : 'bg-transparent'">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="flex justify-between h-[72px] items-center">

                <a href="/" class="flex items-center gap-2.5 group">
                    <span class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-indigo-600/30 group-hover:shadow-indigo-600/50 transition-shadow">S</span>
                    <span class="text-lg font-bold" :class="scrolled ? 'text-gray-900' : 'text-white'">ServiceAuto</span>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    <a href="#despre" class="px-3.5 py-2 text-sm font-medium rounded-lg transition-colors" :class="scrolled ? 'text-gray-600 hover:text-gray-900' : 'text-white/70 hover:text-white'">Despre noi</a>
                    <a href="#cum-functioneaza" class="px-3.5 py-2 text-sm font-medium rounded-lg transition-colors" :class="scrolled ? 'text-gray-600 hover:text-gray-900' : 'text-white/70 hover:text-white'">Cum funcționează</a>
                    <a href="#servicii" class="px-3.5 py-2 text-sm font-medium rounded-lg transition-colors" :class="scrolled ? 'text-gray-600 hover:text-gray-900' : 'text-white/70 hover:text-white'">Service-uri</a>
                    <a href="#harta" class="px-3.5 py-2 text-sm font-medium rounded-lg transition-colors" :class="scrolled ? 'text-gray-600 hover:text-gray-900' : 'text-white/70 hover:text-white'">Locații</a>
                    <a href="#contact" class="px-3.5 py-2 text-sm font-medium rounded-lg transition-colors" :class="scrolled ? 'text-gray-600 hover:text-gray-900' : 'text-white/70 hover:text-white'">Contact</a>
                </div>

                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @if(auth()->user()->role === 'client')
                            <a href="{{ route('client.dashboard') }}" class="px-5 py-2.5 text-sm bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition shadow-sm">Dashboard</a>
                        @else
                            <a href="/admin" class="px-5 py-2.5 text-sm bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition shadow-sm">Admin Panel</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2.5 text-sm font-medium rounded-xl transition" :class="scrolled ? 'text-gray-700 hover:text-gray-900' : 'text-white/80 hover:text-white'">Autentificare</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition shadow-sm shadow-indigo-600/25">Înregistrare</a>
                    @endauth
                </div>

                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-xl transition" :class="scrolled ? 'text-gray-700' : 'text-white'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div x-show="mobileOpen" x-transition.opacity class="md:hidden pb-5 space-y-1 bg-white rounded-2xl mt-1 p-4 shadow-xl border border-gray-100">
                <a href="#despre" @click="mobileOpen = false" class="block px-4 py-2.5 text-sm text-gray-600 hover:text-indigo-600 rounded-lg font-medium">Despre noi</a>
                <a href="#cum-functioneaza" @click="mobileOpen = false" class="block px-4 py-2.5 text-sm text-gray-600 hover:text-indigo-600 rounded-lg font-medium">Cum funcționează</a>
                <a href="#servicii" @click="mobileOpen = false" class="block px-4 py-2.5 text-sm text-gray-600 hover:text-indigo-600 rounded-lg font-medium">Service-uri</a>
                <a href="#harta" @click="mobileOpen = false" class="block px-4 py-2.5 text-sm text-gray-600 hover:text-indigo-600 rounded-lg font-medium">Locații</a>
                <a href="#contact" @click="mobileOpen = false" class="block px-4 py-2.5 text-sm text-gray-600 hover:text-indigo-600 rounded-lg font-medium">Contact</a>
                <div class="border-t border-gray-100 pt-3 mt-3 space-y-1">
                    @auth
                        <a href="{{ route('client.dashboard') }}" class="block px-4 py-2.5 text-sm font-semibold text-indigo-600">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2.5 text-sm text-gray-600 font-medium">Autentificare</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2.5 text-sm font-semibold text-indigo-600">Înregistrare</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="relative min-h-[100vh] flex items-center overflow-hidden bg-gray-950">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=1920&q=80')] bg-cover bg-center"></div>
        <div class="absolute inset-0 bg-gray-950/75"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-gray-950/50 via-transparent to-gray-950"></div>

        <div class="relative max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-32 w-full">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/80 text-sm font-medium mb-8 ring-1 ring-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Platformă activă cu peste 50 de service-uri partenere
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold text-white leading-[1.1] tracking-tight mb-6">
                    Îngrijirea mașinii tale,<br>simplificată.
                </h1>

                <p class="text-lg text-white/60 mb-10 max-w-xl leading-relaxed">
                    Conectăm proprietarii de mașini cu cele mai bune service-uri auto din România. Programare online, istoric complet al reparațiilor și facturi digitale — totul într-un singur loc.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('services.index') }}" class="group inline-flex items-center justify-center gap-2.5 px-7 py-4 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Găsește un service
                    </a>
                    <a href="#cum-functioneaza" class="inline-flex items-center justify-center gap-2.5 px-7 py-4 text-white/90 rounded-xl font-semibold ring-1 ring-white/20 hover:ring-white/40 hover:text-white transition-all">
                        Află cum funcționează
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-white to-transparent"></div>
    </section>

    {{-- STATS BAR --}}
    <section class="relative -mt-16 z-10 pb-16">
        <div class="max-w-5xl mx-auto px-5 sm:px-8">
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
                <div class="px-8 py-7 text-center">
                    <div class="text-3xl font-extrabold text-gray-900">50+</div>
                    <div class="text-sm text-gray-500 mt-1">Service-uri verificate</div>
                </div>
                <div class="px-8 py-7 text-center">
                    <div class="text-3xl font-extrabold text-gray-900">1.200+</div>
                    <div class="text-sm text-gray-500 mt-1">Reparații finalizate</div>
                </div>
                <div class="px-8 py-7 text-center">
                    <div class="text-3xl font-extrabold text-gray-900">4.8<span class="text-amber-400 ml-1">★</span></div>
                    <div class="text-sm text-gray-500 mt-1">Satisfacție clienți</div>
                </div>
            </div>
        </div>
    </section>

    {{-- DESPRE NOI --}}
    <section id="despre" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-4">Despre noi</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight mb-6">
                        Construim poduri între<br>șoferi și service-uri auto
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-6">
                        ServiceAuto a apărut dintr-o nevoie reală: găsirea unui service auto de încredere nu ar trebui să fie complicată. Am creat o platformă care reunește cele mai bune service-uri auto din România și le pune la dispoziția ta printr-o experiență digitală simplă.
                    </p>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        Fie că ai nevoie de un schimb de ulei, o revizie completă sau o reparație urgentă, noi te conectăm cu profesioniștii potriviți — rapid, transparent și fără bătăi de cap.
                    </p>
                    <div class="grid grid-cols-2 gap-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 text-sm">Verificate</div>
                                <div class="text-xs text-gray-400 mt-0.5">Toate service-urile sunt validate</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 text-sm">Rapid</div>
                                <div class="text-xs text-gray-400 mt-0.5">Programare în câteva minute</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 text-sm">Transparent</div>
                                <div class="text-xs text-gray-400 mt-0.5">Prețuri clare, fără surprize</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 text-sm">Digital</div>
                                <div class="text-xs text-gray-400 mt-0.5">Facturi și istoric online</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl shadow-gray-300/30">
                        <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?w=800&q=80" alt="Service auto profesionist" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl shadow-gray-200/60 border border-gray-100 p-5 max-w-[220px]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">100%</div>
                                <div class="text-xs text-gray-400">Gratuit pentru clienți</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CUM FUNCȚIONEAZĂ --}}
    <section id="cum-functioneaza" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-4">Cum funcționează</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">
                    Trei pași simpli pentru mașina ta
                </h2>
                <p class="mt-4 text-gray-500">De la căutare la reparație finalizată — totul online, fără complicații.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="relative bg-white rounded-2xl border border-gray-100 p-8 shadow-sm hover:shadow-md transition-shadow group">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-bold text-lg mb-6 shadow-lg shadow-indigo-600/25 group-hover:scale-105 transition-transform">1</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Caută un service</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Folosește platforma pentru a găsi service-uri auto de încredere, verificate și cu recenzii reale, în zona ta sau oriunde în țară.</p>
                </div>
                <div class="relative bg-white rounded-2xl border border-gray-100 p-8 shadow-sm hover:shadow-md transition-shadow group">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-bold text-lg mb-6 shadow-lg shadow-indigo-600/25 group-hover:scale-105 transition-transform">2</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Programează online</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Alege tipul de intervenție, selectează o dată convenabilă și programează-te direct din cont — fără apeluri telefonice.</p>
                </div>
                <div class="relative bg-white rounded-2xl border border-gray-100 p-8 shadow-sm hover:shadow-md transition-shadow group">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-bold text-lg mb-6 shadow-lg shadow-indigo-600/25 group-hover:scale-105 transition-transform">3</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Urmărește totul</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Primești notificări despre starea lucrării, vezi istoricul complet al intervențiilor și descarci facturile direct din platformă.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CE OFERIM --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="order-2 lg:order-1">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                            <div class="w-11 h-11 rounded-xl bg-indigo-100 flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 text-sm mb-1">Căutare avansată</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Filtrare după oraș, rating și tip de serviciu</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                            <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 text-sm mb-1">Istoric complet</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Toate intervențiile mașinii tale, într-un singur loc</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                            <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 text-sm mb-1">Facturi PDF</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Generare și descărcare automată de facturi</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                            <div class="w-11 h-11 rounded-xl bg-violet-100 flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 text-sm mb-1">Hartă interactivă</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Vizualizează service-urile pe hartă în timp real</p>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-4">Funcționalități</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight mb-6">
                        Tot ce ai nevoie pentru mașina ta, într-o singură platformă
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-6">
                        Am construit ServiceAuto cu grijă pentru fiecare detaliu. De la căutarea unui service potrivit până la descărcarea facturii finale, totul este gândit să fie intuitiv.
                    </p>
                    <p class="text-gray-500 leading-relaxed">
                        Platforma integrează hartă interactivă cu locațiile tuturor partenerilor, un sistem complet de management al intervențiilor și generare automată de documente — totul accesibil de pe orice dispozitiv.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICE-URI TOP --}}
    <section id="servicii" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-14 gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-4">Rețeaua noastră</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">Service-uri de încredere</h2>
                    <p class="mt-3 text-gray-500">Cele mai apreciate service-uri auto din platformă</p>
                </div>
                <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors flex-shrink-0">
                    Vezi toate service-urile
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                <a href="{{ route('services.show', $service) }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <div class="p-7">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                ★ {{ $service->rating }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-1">{{ $service->name }}</h3>
                        <p class="text-sm text-gray-400 mb-4">{{ $service->city }}</p>
                        <p class="text-gray-500 text-sm leading-relaxed mb-6">{{ Str::limit($service->description, 100) }}</p>
                        <span class="text-sm font-semibold text-indigo-600 inline-flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                            Vezi detalii
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- HARTĂ --}}
    <section id="harta" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-start">
                <div class="lg:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-4">Locații</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight mb-4">Unde ne găsești</h2>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        Acoperire la nivel național, cu service-uri partenere în principalele orașe din România. Fiecare locație este verificată și evaluată de comunitatea noastră.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <span class="text-gray-600">Prezență în peste <strong class="text-gray-900">15 județe</strong></span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-gray-600">Toate service-urile sunt <strong class="text-gray-900">verificate</strong></span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                            <span class="text-gray-600">Rating mediu de <strong class="text-gray-900">4.8 stele</strong></span>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-3">
                    <div id="map" class="w-full h-[420px] rounded-2xl shadow-lg border border-gray-200 overflow-hidden"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALE --}}
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-4">Recenzii</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">Ce spun clienții noștri</h2>
                <p class="mt-4 text-gray-500">Experiențe reale de la utilizatorii platformei</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $testimonials = [
                        ['initials' => 'VP', 'name' => 'Vlad P.', 'role' => 'Proprietar BMW Seria 3', 'color' => 'indigo', 'stars' => 5, 'text' => 'Am găsit service-ul perfect în zona mea. Programare rapidă, comunicare clară pe tot parcursul lucrării și factura a venit automat pe email. Nu mă mai întorc la metoda veche.'],
                        ['initials' => 'MI', 'name' => 'Maria I.', 'role' => 'Proprietar VW Golf', 'color' => 'emerald', 'stars' => 5, 'text' => 'Platforma e foarte ușor de folosit. Îmi place că pot vedea istoricul complet al mașinii — schimburi de ulei, revizii, tot. Mă simt că am totul sub control.'],
                        ['initials' => 'AD', 'name' => 'Andrei D.', 'role' => 'Proprietar Dacia Duster', 'color' => 'violet', 'stars' => 5, 'text' => 'Ca cineva care nu se pricepe la mașini, platforma asta e o binecuvântare. Am ales service-ul după recenzii, am programat online și totul a mers perfect.'],
                    ];
                @endphp
                @foreach($testimonials as $t)
                <div class="bg-white rounded-2xl border border-gray-100 p-7 shadow-sm">
                    <div class="flex items-center gap-1 mb-5">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 {{ $i < $t['stars'] ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">&bdquo;{{ $t['text'] }}&rdquo;</p>
                    <div class="flex items-center gap-3 pt-5 border-t border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-{{ $t['color'] }}-100 flex items-center justify-center text-{{ $t['color'] }}-600 font-bold text-xs">{{ $t['initials'] }}</div>
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">{{ $t['name'] }}</div>
                            <div class="text-xs text-gray-400">{{ $t['role'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 bg-gray-950 relative overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[100px]"></div>
        <div class="relative max-w-3xl mx-auto px-5 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white leading-tight mb-5">
                Pregătit să ai grijă de mașina ta?
            </h2>
            <p class="text-gray-400 text-lg mb-10 max-w-xl mx-auto">
                Creează un cont gratuit și descoperă cum poți gestiona întreținerea mașinii tale mai simplu ca niciodată.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/30 text-base">
                    Creează cont gratuit
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 text-white/80 rounded-xl font-semibold ring-1 ring-white/15 hover:ring-white/30 hover:text-white transition-all text-base">
                    Explorează service-uri
                </a>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer id="contact" class="bg-gray-950 border-t border-white/5 text-gray-400">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 pt-16 pb-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-white/5">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2.5 text-lg font-bold text-white mb-4">
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">S</span>
                        ServiceAuto
                    </div>
                    <p class="text-sm leading-relaxed text-gray-500">Platforma digitală care simplifică relația dintre proprietarii de mașini și service-urile auto din România.</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-5">Platformă</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#despre" class="hover:text-white transition-colors">Despre noi</a></li>
                        <li><a href="#cum-functioneaza" class="hover:text-white transition-colors">Cum funcționează</a></li>
                        <li><a href="{{ route('services.index') }}" class="hover:text-white transition-colors">Service-uri</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-5">Cont</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Autentificare</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Înregistrare</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-5">Contact</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            contact@serviceauto.ro
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            0700 000 000
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            România
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-600">
                <div>&copy; {{ date('Y') }} ServiceAuto Platform. Lucrare de licență — Automatică și Informatică Aplicată.</div>
                <div class="flex items-center gap-1">
                    Construit cu
                    <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                    în România
                </div>
            </div>
        </div>
    </footer>

    {{-- LEAFLET JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map', { scrollWheelZoom: false }).setView([45.0, 26.0], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const services = @json($mapMarkers);
            services.forEach(s => {
                L.marker([s.lat, s.lng]).addTo(map)
                    .bindPopup(`<strong>${s.name}</strong><br>★ ${s.rating}<br>${s.phone}`);
            });
        });
    </script>
</body>
</html>
