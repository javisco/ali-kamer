<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Espace acheteur - Ali-Kamer')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="h-full font-sans antialiased text-slate-900 bg-slate-50" x-data="{ sidebarOpen: false }"
    @keydown.escape.window="sidebarOpen = false">

    <div class="flex h-screen overflow-hidden">

        {{-- =========================================================
        MOBILE OVERLAY
    ========================================================== --}}
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden" style="display:none;"></div>


        {{-- =========================================================
        SIDEBAR ACHETEUR
    ========================================================== --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col
               bg-primary-800 text-white border-r border-primary-900
               transform transition-transform duration-300 ease-in-out
               lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- =====================================================
            LOGO
        ====================================================== --}}
            <div class="flex-shrink-0 px-3 py-2.5 border-b border-white/10 bg-black/20">

                <div class="flex items-center justify-between gap-2">

                    <a href="{{ route('buyer.home') }}" class="flex items-center gap-2.5 min-w-0 group">

                        <div
                            class="w-9 h-9 flex-shrink-0 rounded-lg bg-white
                               shadow-md p-1 flex items-center justify-center
                               transition-transform duration-200 group-hover:scale-105">
                            <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer"
                                class="w-full h-full object-contain">
                        </div>

                        <div class="min-w-0">
                            <div class="font-black text-sm tracking-tight text-white leading-none">
                                ALI-KAMER
                            </div>

                            <div
                                class="inline-flex items-center mt-1 px-1.5 py-0.5
                                   rounded text-[8px] font-bold uppercase tracking-wider
                                   text-accent-500 bg-accent-500/10
                                   border border-accent-500/30">
                                Espace acheteur
                            </div>
                        </div>

                    </a>

                    {{-- Fermeture mobile --}}
                    <button type="button" @click="sidebarOpen = false"
                        class="lg:hidden p-1.5 rounded-lg text-slate-400
                           hover:text-white hover:bg-white/10 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>
            </div>


            {{-- =====================================================
            MINI PROFIL
        ====================================================== --}}
            <div class="flex-shrink-0 px-2.5 py-2 border-b border-white/10">

                <div class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg bg-white/5">

                    <div
                        class="w-8 h-8 flex-shrink-0 rounded-lg
                           bg-primary-600 text-white
                           flex items-center justify-center
                           font-black text-xs">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>

                    <div class="min-w-0 flex-1">

                        <p class="text-[11px] font-bold text-white truncate leading-tight">
                            {{ auth()->user()->name ?? 'Acheteur' }}
                        </p>

                        <p class="text-[9px] text-slate-400 truncate mt-0.5">
                            {{ auth()->user()->email ?? '' }}
                        </p>

                    </div>

                    <span
                        class="w-1.5 h-1.5 flex-shrink-0 rounded-full
                           bg-success shadow-sm shadow-success/50"
                        title="Compte actif"></span>

                </div>
            </div>


            {{-- =====================================================
            NAVIGATION
            Compacte : aucun scroll nécessaire sur desktop
        ====================================================== --}}
            <nav class="flex-1 min-h-0 px-2.5 py-2 overflow-hidden">

                {{-- =========================
                PRINCIPAL
            ========================== --}}
                <div class="mb-2.5">

                    <p class="px-2 mb-1 text-[8px] font-bold uppercase tracking-[0.16em] text-primary-400">
                        Principal
                    </p>

                    <div class="space-y-0.5">

                        {{-- Tableau de bord --}}
                        <a href="{{ route('buyer.dashboard') }}" @click="sidebarOpen = false"
                            class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               transition-all duration-200
                               {{ request()->routeIs('buyer.dashboard')
                                   ? 'bg-primary-600 text-white shadow-md shadow-primary-600/30'
                                   : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                            <svg class="w-4 h-4 flex-shrink-0
                                   {{ request()->routeIs('buyer.dashboard') ? 'text-accent-500' : 'text-slate-500 group-hover:text-accent-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z
                                   M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z
                                   M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z
                                   M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>

                            <span class="truncate">Tableau de bord</span>

                        </a>


                        {{-- Marketplace --}}
                        {{-- <a
                        href="{{ route('buyer.home') }}"
                        @click="sidebarOpen = false"
                        class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-200"
                    >

                        <svg
                            class="w-4 h-4 flex-shrink-0 text-slate-500 group-hover:text-accent-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4
                                   M5 9h14l1 11H4L5 9z"
                            />
                        </svg>

                        <span class="truncate">Marketplace</span>

                    </a> --}}

                    </div>
                </div>


                {{-- =========================
                MES ACHATS
            ========================== --}}
                <div class="mb-2.5">

                    <p class="px-2 mb-1 text-[8px] font-bold uppercase tracking-[0.16em] text-primary-400">
                        Mes achats
                    </p>

                    <div class="space-y-0.5">

                        {{-- Commandes --}}
                        <a href="{{ route('buyer.orders.index') }}" @click="sidebarOpen = false"
                            class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               transition-all duration-200
                               {{ request()->routeIs('buyer.orders.*')
                                   ? 'bg-primary-600 text-white shadow-md shadow-primary-600/30'
                                   : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                            <svg class="w-4 h-4 flex-shrink-0
                                   {{ request()->routeIs('buyer.orders.*') ? 'text-accent-500' : 'text-slate-500 group-hover:text-accent-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                   M9 5a3 3 0 006 0
                                   M9 12h6M9 16h4" />
                            </svg>

                            <span class="truncate flex-1">Mes commandes</span>

                        </a>


                        {{-- Favoris --}}
                        <a href="{{ route('buyer.wishlist.index') }}" @click="sidebarOpen = false"
                            class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               transition-all duration-200
                               {{ request()->routeIs('buyer.wishlist.*')
                                   ? 'bg-primary-600 text-white shadow-md shadow-primary-600/30'
                                   : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                            <svg class="w-4 h-4 flex-shrink-0
                                   {{ request()->routeIs('buyer.wishlist.*') ? 'text-danger' : 'text-slate-500 group-hover:text-danger' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06
                                   a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84
                                   a5.5 5.5 0 000-7.78z" />
                            </svg>

                            <span class="truncate">Mes favoris</span>

                        </a>


                        {{-- Panier --}}
                        <a href="{{ route('buyer.cart.index') }}" @click="sidebarOpen = false"
                            class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-200">

                            <svg class="w-4 h-4 flex-shrink-0 text-slate-500 group-hover:text-accent-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4
                                   M7 13L5.4 5M7 13l-2 4h13
                                   M9 21a1 1 0 100-2 1 1 0 000 2z
                                   M18 21a1 1 0 100-2 1 1 0 000 2z" />
                            </svg>

                            <span class="truncate flex-1">Mon panier</span>

                            @php
                                $cartCount = 0;

                                try {
                                    if (auth()->check() && method_exists(auth()->user(), 'cart')) {
                                        $cartCount = (int) (auth()->user()->cart?->items?->sum('quantity') ?? 0);
                                    }
                                } catch (\Throwable $e) {
                                    $cartCount = 0;
                                }
                            @endphp

                            @if ($cartCount > 0)
                                <span
                                    class="min-w-[18px] h-[18px] px-1 rounded-full
                                       bg-accent-600 text-white text-[9px]
                                       font-black flex items-center justify-center">
                                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                                </span>
                            @endif

                        </a>

                    </div>
                </div>


                {{-- =========================
                FINANCES
            ========================== --}}
                <div class="mb-2.5">

                    <p class="px-2 mb-1 text-[8px] font-bold uppercase tracking-[0.16em] text-primary-400">
                        Finances
                    </p>

                    <div class="space-y-0.5">

                        <a href="{{ route('buyer.wallet.history') }}" @click="sidebarOpen = false"
                            class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               transition-all duration-200
                               {{ request()->routeIs('buyer.wallet.*')
                                   ? 'bg-primary-600 text-white shadow-md shadow-primary-600/30'
                                   : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                            <svg class="w-4 h-4 flex-shrink-0
                                   {{ request()->routeIs('buyer.wallet.*') ? 'text-accent-500' : 'text-slate-500 group-hover:text-accent-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                                   3 0 3 .895 3 2-1.343 2-3 2
                                   m0-8c1.11 0 2.08.402 2.599 1
                                   M12 8V6m0 12v-2
                                   M18 12a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>

                            <span class="truncate">Mes transactions</span>

                        </a>

                    </div>
                </div>


                {{-- =========================
                COMMUNICATION
            ========================== --}}
                <div class="mb-2.5">

                    <p class="px-2 mb-1 text-[8px] font-bold uppercase tracking-[0.16em] text-primary-400">
                        Communication
                    </p>

                    <div class="space-y-0.5">

                        {{-- Messages --}}
                        <a href="{{ route('messaging.index') }}" @click="sidebarOpen = false"
                            class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               transition-all duration-200
                               {{ request()->routeIs('messaging.*')
                                   ? 'bg-primary-600 text-white shadow-md shadow-primary-600/30'
                                   : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                            <svg class="w-4 h-4 flex-shrink-0
                                   {{ request()->routeIs('messaging.*') ? 'text-accent-500' : 'text-slate-500 group-hover:text-accent-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5
                                   M21 12a8 8 0 01-8 8H7l-4 2 1.5-4.5
                                   A8 8 0 1121 12z" />
                            </svg>

                            <span class="truncate flex-1">Messages</span>

                        </a>


                        {{-- Notifications --}}
                        {{-- <a
                        href="{{ route('notifications.index') }}"
                        @click="sidebarOpen = false"
                        class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-200"
                    >

                        <svg
                            class="w-4 h-4 flex-shrink-0 text-slate-500 group-hover:text-accent-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                                   a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
                                   C7.67 6.165 6 8.388 6 11v3.159
                                   c0 .538-.214 1.055-.595 1.436L4 17h5
                                   m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                            />
                        </svg>

                        <span class="truncate flex-1">Notifications</span>

                        @if (isset($unreadNotifications) && $unreadNotifications > 0)
                            <span
                                class="min-w-[18px] h-[18px] px-1 rounded-full
                                       bg-accent-600 text-white text-[9px]
                                       font-black flex items-center justify-center"
                            >
                                {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                            </span>
                        @endif

                    </a>

                </div>
            </div> --}}
                        @include('components.notification-bell')

                        {{-- =========================
                MON COMPTE
            ========================== --}}
                        <div class="mb-2.5">

                            <p class="px-2 mb-1 text-[8px] font-bold uppercase tracking-[0.16em] text-primary-400">
                                Mon compte
                            </p>

                            <div class="space-y-0.5">

                                {{-- Profil --}}
                                <a href="{{ route('buyer.profile') }}" @click="sidebarOpen = false"
                                    class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               transition-all duration-200
                               {{ request()->routeIs('buyer.profile')
                                   ? 'bg-primary-600 text-white shadow-md shadow-primary-600/30'
                                   : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">

                                    <svg class="w-4 h-4 flex-shrink-0
                                   {{ request()->routeIs('buyer.profile') ? 'text-accent-500' : 'text-slate-500 group-hover:text-accent-500' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z
                                   M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>

                                    <span class="truncate">Mon profil</span>

                                </a>

                            </div>
                        </div>


                        {{-- =========================
                ASSISTANCE
            ========================== --}}
                        <div>

                            <p class="px-2 mb-1 text-[8px] font-bold uppercase tracking-[0.16em] text-primary-400">
                                Assistance
                            </p>

                            <div class="space-y-0.5">

                                {{-- Tutoriels --}}
                                <a href="{{ route('tutorials.index') }}" @click="sidebarOpen = false"
                                    class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-200">

                                    <svg class="w-4 h-4 flex-shrink-0 text-slate-500 group-hover:text-accent-500"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13
                                   m0-13C10.832 5.477 9.246 5 7.5 5
                                   S4.168 5.477 3 6.253v13
                                   C4.168 18.477 5.754 18 7.5 18
                                   s3.332.477 4.5 1.253
                                   m0-13C13.168 5.477 14.754 5 16.5 5
                                   s3.332.477 4.5 1.253v13
                                   C19.832 18.477 18.246 18 16.5 18
                                   s-3.332.477-4.5 1.253" />
                                    </svg>

                                    <span class="truncate">Tutoriels</span>

                                </a>


                                {{-- Centre d'aide --}}
                                <a href="{{ route('help') }}" @click="sidebarOpen = false"
                                    class="group flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-semibold
                               text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-200">

                                    <svg class="w-4 h-4 flex-shrink-0 text-slate-500 group-hover:text-accent-500"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 1.907-2 3.772-2
                                   2.21 0 4 1.343 4 3 0 1.4-.9 2.3-2.2 2.8
                                   -.9.35-1.8 1.05-1.8 2.2v.5
                                   M12 18h.01
                                   M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>

                                    <span class="truncate">Centre d'aide</span>

                                </a>

                            </div>
                        </div>

            </nav>


            {{-- =====================================================
            FOOTER SIDEBAR
        ====================================================== --}}
            <div class="flex-shrink-0 px-2.5 py-2 border-t border-white/10 bg-black/20">

                {{-- Retour marketplace --}}
                <a href="{{ route('buyer.home') }}" @click="sidebarOpen = false"
                    class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg
                       bg-white/10 hover:bg-white/20
                       text-white text-[11px] font-semibold
                       transition-all duration-200">

                    <svg class="w-4 h-4 flex-shrink-0 text-accent-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3
                           m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                    </svg>

                    <span class="truncate">Retour à la Marketplace</span>

                </a>


                {{-- Déconnexion --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf

                    <button type="submit"
                        class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg
                           text-[11px] font-semibold text-slate-400
                           hover:text-white hover:bg-danger
                           transition-all duration-200">

                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7
                               m6 4v1a3 3 0 01-3 3H6
                               a3 3 0 01-3-3V7a3 3 0 013-3h4
                               a3 3 0 013 3v1" />
                        </svg>

                        <span>Déconnexion</span>

                    </button>
                </form>

            </div>

        </aside>


        {{-- =========================================================
        MAIN AREA
    ========================================================== --}}
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">


            {{-- =====================================================
            TOPBAR
        ====================================================== --}}
            <header
                class="flex-shrink-0 bg-primary-600 border-b border-primary-700
                   sticky top-0 z-30">

                <div class="flex items-center justify-between gap-3 px-4 sm:px-6 py-3">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3 min-w-0">

                        {{-- Mobile menu --}}
                        <button type="button" @click="sidebarOpen = true"
                            class="lg:hidden p-2 rounded-xl
                               text-slate-600 bg-slate-50
                               border border-slate-200
                               hover:bg-slate-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>


                        {{-- Indicateur --}}
                        <div class="flex items-center gap-2 min-w-0">

                            <span
                                class="w-2 h-2 flex-shrink-0 rounded-full
                                   bg-primary-600
                                   shadow-sm shadow-primary-600/50"></span>

                            <span
                                class="hidden sm:block text-[10px] font-bold
                                   uppercase tracking-wider text-slate-500 truncate">
                                Espace acheteur
                            </span>

                            @hasSection('breadcrumb')
                                <span class="hidden md:block text-slate-300">/</span>

                                <span
                                    class="hidden md:block text-xs font-semibold
                                       text-slate-700 truncate">
                                    @yield('breadcrumb')
                                </span>
                            @endif

                        </div>
                    </div>


                    {{-- RIGHT --}}
                    <div class="flex items-center gap-1.5 sm:gap-2.5">

                        {{-- Notifications --}}
                        @include('components.notification-bell')

                        {{-- Messages --}}
                        <a href="{{ route('messaging.index') }}"
                            class="hidden sm:flex p-2 rounded-xl
                               text-slate-500 hover:text-primary-600
                               hover:bg-primary-50 transition"
                            title="Messages">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5
                                   M21 12a8 8 0 01-8 8H7l-4 2 1.5-4.5
                                   A8 8 0 1121 12z" />
                            </svg>

                        </a>


                        {{-- Profil --}}
                        <div class="flex items-center gap-2 pl-2 border-l border-slate-200">

                            <div
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl
                                   bg-primary-600 text-white
                                   flex items-center justify-center
                                   font-black text-xs sm:text-sm shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>

                            <div class="hidden md:block max-w-[150px]">

                                <p class="text-xs font-bold text-slate-800 truncate">
                                    {{ auth()->user()->name ?? 'Acheteur' }}
                                </p>

                                <p class="text-[9px] text-slate-400 truncate">
                                    Acheteur
                                </p>

                            </div>

                        </div>


                        {{-- Déconnexion rapide --}}
                        <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                            @csrf

                            <button type="submit"
                                class="p-2 rounded-xl text-danger
                                   hover:text-white hover:bg-danger
                                   transition"
                                title="Déconnexion">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7
                                       m6 4v1a3 3 0 01-3 3H6
                                       a3 3 0 01-3-3V7a3 3 0 013-3h4
                                       a3 3 0 013 3v1" />
                                </svg>

                            </button>
                        </form>

                    </div>

                </div>

            </header>


            {{-- =====================================================
            CONTENU
        ====================================================== --}}
            <main class="flex-1 overflow-y-auto bg-slate-50">

                <div class="p-4 sm:p-6 max-w-[1600px] w-full mx-auto">

                    {{-- =================================================
                    SUCCESS
                ================================================== --}}
                    @if (session('success'))
                        <div
                            class="mb-4 flex items-start gap-3 p-3 rounded-xl
                               bg-success-50 border border-success-200
                               text-success-800">

                            <div
                                class="w-8 h-8 flex-shrink-0 rounded-lg
                                   bg-success-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>

                            <div class="min-w-0">

                                <p class="text-[10px] font-black uppercase tracking-wide">
                                    Opération réussie
                                </p>

                                <p class="text-sm mt-0.5">
                                    {{ session('success') }}
                                </p>

                            </div>

                        </div>
                    @endif


                    {{-- =================================================
                    ERROR
                ================================================== --}}
                    @if (session('error'))
                        <div
                            class="mb-4 flex items-start gap-3 p-3 rounded-xl
                               bg-danger-50 border border-danger-200
                               text-danger-800">

                            <div
                                class="w-8 h-8 flex-shrink-0 rounded-lg
                                   bg-danger-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>

                            <div class="min-w-0">

                                <p class="text-[10px] font-black uppercase tracking-wide">
                                    Une erreur est survenue
                                </p>

                                <p class="text-sm mt-0.5">
                                    {{ session('error') }}
                                </p>

                            </div>

                        </div>
                    @endif


                    {{-- =================================================
                    VALIDATION ERRORS
                ================================================== --}}
                    @if ($errors->any())

                        <div
                            class="mb-4 p-3 rounded-xl
                               bg-danger-50 border border-danger-200
                               text-danger-800">

                            <div class="flex items-center gap-2 mb-2">

                                <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86
                                       a2 2 0 001.73-3L13.73 4
                                       a2 2 0 00-3.46 0L3.34 16
                                       a2 2 0 001.73 3z" />
                                </svg>

                                <p class="text-sm font-black">
                                    Vérifiez les informations saisies.
                                </p>

                            </div>

                            <ul class="ml-7 space-y-1 text-xs">

                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- =================================================
                    PAGE
                ================================================== --}}
                    @yield('content')

                </div>

            </main>

        </div>

    </div>


    {{-- =========================================================
    SCRIPTS
========================================================== --}}
    @stack('scripts')

</body>

</html>
