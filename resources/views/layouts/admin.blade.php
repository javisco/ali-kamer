<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Administration') — {{ config('app.name', 'Ali-Kamer') }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .admin-sidebar-nav {
            scrollbar-width: none;
        }

        .admin-sidebar-nav::-webkit-scrollbar {
            display: none;
        }

        @media (min-width: 768px) and (min-height: 680px) {
            .admin-sidebar-nav {
                overflow-y: hidden;
            }
        }
    </style>

    @stack('styles')
</head>


<body class="h-full font-sans antialiased text-slate-800 bg-slate-50" x-data="{ sidebarOpen: false }"
    @keydown.escape.window="sidebarOpen = false">


    <div class="flex h-screen overflow-hidden">


        {{-- =========================================================
         OVERLAY MOBILE
    ========================================================== --}}

        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/80 z-40 md:hidden"></div>


        {{-- =========================================================
         SIDEBAR ADMIN
    ========================================================== --}}

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a1b12] text-white flex flex-col transition-transform duration-300 ease-in-out md:static md:translate-x-0 border-r border-slate-800 shrink-0">

            <div class="flex flex-col h-full min-h-0">


                {{-- =================================================
                 LOGO
            ================================================== --}}

                <div class="px-4 py-3 border-b border-white/10 flex items-center justify-between shrink-0 bg-black/20">

                    <div class="flex items-center gap-3">

                        <div
                            class="w-10 h-10 bg-white rounded-xl shadow-md p-1 flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo"
                                class="h-full w-auto object-contain">
                        </div>

                        <div class="leading-tight">

                            <h2 class="font-black text-sm tracking-wide text-white uppercase">
                                Ali-Kamer
                            </h2>

                            <span
                                class="inline-block mt-1 text-[9px] font-bold text-[#F9A01B] bg-[#F9A01B]/10 px-2 py-0.5 rounded border border-[#F9A01B]/30">
                                Administration
                            </span>

                        </div>

                    </div>


                    {{-- Fermeture mobile --}}

                    <button @click="sidebarOpen = false" type="button"
                        class="md:hidden text-slate-400 hover:text-white p-1">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>

                    </button>

                </div>


                {{-- =================================================
                 NAVIGATION
            ================================================== --}}

                <nav class="admin-sidebar-nav flex-1 px-3 py-2 overflow-y-auto text-xs">


                    {{-- =================================================
                     VUE GLOBALE
                ================================================== --}}

                    <div class="px-3 pt-1 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                        Vue globale
                    </div>


                    {{-- Dashboard --}}

                    <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl font-bold transition-all
                    {{ request()->routeIs('admin.dashboard')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40 ring-1 ring-emerald-400/30'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                        <svg class="w-4 h-4 shrink-0 text-[#F9A01B]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>

                        <span>Tableau de bord</span>

                    </a>


                    {{-- =================================================
                     UTILISATEURS & SÉCURITÉ
                ================================================== --}}

                    <div class="px-3 pt-3 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                        Utilisateurs & sécurité
                    </div>


                    {{-- Utilisateurs --}}

                    {{-- Lien Utilisateurs (Exclusion stricte des mauvaises notes) --}}
                    <a href="{{ route('admin.users.index') }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
    {{ request()->routeIs('admin.users.index', 'admin.users.show', 'admin.users.edit', 'admin.users.history')
        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>

                        <span>Utilisateurs</span>
                    </a>

                    {{-- Lien Mauvais Scores --}}
                    <a href="{{ route('admin.users.low-scores') }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
    {{ request()->routeIs('admin.users.low*')
        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                        <span>Mauvais scores</span>
                    </a>

                    {{-- KYC --}}

                    <a href="{{ route('admin.kyc.index') }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('admin.kyc.*')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z" />
                        </svg>

                        <span>Vérifications KYC</span>

                    </a>


                    {{-- =================================================
                     MARKETPLACE
                ================================================== --}}

                    <div class="px-3 pt-3 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                        Marketplace
                    </div>


                    {{-- Catégories --}}

                    <a href="{{ route('admin.categories.index') }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('admin.categories.*')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8" />
                        </svg>

                        <span>Catégories</span>

                    </a>


                    {{-- =================================================
                     LOGISTIQUE
                ================================================== --}}

                    <div class="px-3 pt-3 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                        Logistique
                    </div>


                    {{-- Agences --}}

                    <a href="{{ route('admin.agencies.index') }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('admin.agencies.*')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4" />
                        </svg>

                        <span>Agences de livraison</span>

                    </a>


                    {{-- =================================================
                     FINANCES
                ================================================== --}}

                    <div class="px-3 pt-3 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                        Finances
                    </div>


                    {{-- Moteur financier --}}

                    <a href="{{ route('admin.financial-engine.index') }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('admin.financial-engine.*')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1m8-6a8 8 0 11-16 0 8 8 0 0116 0z" />
                        </svg>

                        <span>Moteur financier</span>

                    </a>
                    {{-- Trésorerie --}}
                    <a href="{{ route('admin.treasury.index') }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
{{ request()->routeIs('admin.treasury.*')
    ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
    : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 7h6m-9 4h12M4 7v10a2 2 0 002 2h12a2 2 0 002-2V7M4 7l1.5-3h13L20 7" />
                        </svg>

                        <span>Trésorerie</span>
                    </a>

                    {{-- =================================================
                     MODÉRATION & SUPPORT
                ================================================== --}}

                    <div class="px-3 pt-3 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                        Modération & support
                    </div>


                    {{-- Litiges --}}

                    <a href="{{ route('admin.disputes.index') }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('admin.disputes.*')
                        ? 'bg-[#E30613] text-white shadow-lg shadow-red-600/30'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z" />
                        </svg>

                        <span>Gestion des litiges</span>

                    </a>


                    {{-- Tutoriels --}}

                    <a href="{{ route('admin.tutorials.index') }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('admin.tutorials.*')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>

                        <span>Tutoriels vidéo</span>

                    </a>

                </nav>


                {{-- =================================================
                 FOOTER SIDEBAR
            ================================================== --}}

                <div class="p-3 border-t border-white/10 bg-black/20 shrink-0">

                    <a href="{{ route('buyer.home') }}"
                        class="flex items-center justify-center gap-2 w-full px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-200 text-xs font-bold transition-all border border-white/10 group">

                        <svg class="w-4 h-4 text-[#F9A01B] group-hover:-translate-x-1 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>

                        <span>Quitter l'Administration</span>

                    </a>


                    <form action="{{ route('logout') }}" method="POST" class="mt-1">

                        @csrf

                        <button type="submit"
                            class="flex items-center justify-center gap-2 w-full px-3 py-2 rounded-xl text-red-300 hover:text-white hover:bg-[#E30613] transition-all text-xs font-bold">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>

                            <span>Déconnexion</span>

                        </button>

                    </form>

                </div>

            </div>

        </aside>


        {{-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== --}}

        <div class="flex-1 flex flex-col h-screen overflow-y-auto">


            {{-- =====================================================
             TOPBAR
        ====================================================== --}}

            <header
                class="bg-white border-b border-slate-200/80 px-4 sm:px-6 py-3.5 sticky top-0 z-30 flex items-center justify-between shadow-xs">

                <div class="flex items-center gap-3">

                    {{-- Hamburger mobile --}}

                    <button @click="sidebarOpen = !sidebarOpen" type="button"
                        class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none border border-slate-200">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>

                    </button>


                    <div class="flex items-center gap-2">

                        <span class="w-2.5 h-2.5 rounded-full bg-[#016837] animate-pulse"></span>

                        <span class="text-xs font-black text-slate-700 uppercase tracking-wider">
                            Panneau de contrôle
                        </span>

                    </div>

                </div>


                {{-- =================================================
                 PROFIL ADMIN
            ================================================== --}}

                <div class="flex items-center gap-3">

                    <div class="flex items-center gap-3 pl-3 border-l border-slate-200">

                        <div
                            class="w-9 h-9 rounded-xl bg-[#016837] text-[#F9A01B] flex items-center justify-center font-black text-xs shadow-md">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>

                        <div class="hidden sm:block text-left leading-tight">

                            <p class="text-xs font-bold text-slate-800">
                                {{ auth()->user()->name ?? 'Administrateur' }}
                            </p>

                            <p class="text-[10px] text-slate-400">
                                {{ auth()->user()->email ?? '' }}
                            </p>

                        </div>

                    </div>


                    {{-- Déconnexion rapide --}}

                    <form action="{{ route('logout') }}" method="POST" class="inline">

                        @csrf

                        <button type="submit" title="Déconnexion"
                            class="p-2 rounded-xl text-red-400 hover:text-[#E30613] hover:bg-red-50 transition">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>

                        </button>

                    </form>

                </div>

            </header>


            {{-- =====================================================
             CONTENU DES PAGES
        ====================================================== --}}

            <main class="flex-1 p-4 sm:p-6 max-w-[1600px] w-full mx-auto">

                @if (session('success'))
                    <div
                        class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif


                @if (session('error'))
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif


                @yield('content')

            </main>

        </div>

    </div>


    @stack('scripts')

</body>

</html>
