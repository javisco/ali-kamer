<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#FAF9F6]">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ali-Kamer — Achetez et vendez en toute sécurité')</title>

    <!-- Polices Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Assets Vite & Alpine.js -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @stack('styles')
</head>

<body class="flex flex-col min-h-screen bg-[#FAF9F6] text-slate-800 antialiased" x-data="{ mobileMenuOpen: false }">

    <!-- NAVBAR STYLE MARCHÉ -->
    <header
        class="fixed top-0 left-0 right-0 z-50
           bg-[#FAF9F6]/95 backdrop-blur-md
           border-b border-slate-200/80
           py-2.5">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-3 min-w-0">

                {{-- =========================================================
                 LOGO
            ========================================================== --}}
                <a href="{{ route('buyer.home') }}"
                    class="flex items-center shrink-0 hover:scale-[1.02] transition-transform duration-200">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 420 120" class="h-9 md:h-10 w-auto">

                        <defs>
                            <linearGradient id="akGreen" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#064E3B" />
                                <stop offset="100%" stop-color="#043226" />
                            </linearGradient>

                            <linearGradient id="akOrange" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#F97316" />
                                <stop offset="100%" stop-color="#EA580C" />
                            </linearGradient>
                        </defs>

                        <g transform="translate(10, 0)">
                            <circle cx="60" cy="60" r="50" fill="none" stroke="url(#akGreen)"
                                stroke-width="4" stroke-dasharray="220 70" />

                            <circle cx="60" cy="60" r="50" fill="none" stroke="url(#akOrange)"
                                stroke-width="4" stroke-dasharray="90 190" stroke-dashoffset="-160" />

                            <path d="M 35 90 L 60 25 L 85 90" fill="none" stroke="url(#akGreen)" stroke-width="11"
                                stroke-linejoin="round" stroke-linecap="round" />

                            <path d="M 60 25 L 85 90" fill="none" stroke="url(#akOrange)" stroke-width="11"
                                stroke-linejoin="round" stroke-linecap="round" />

                            <path d="M 40 86 C 47 98, 73 98, 80 86" fill="none" stroke="url(#akGreen)"
                                stroke-width="5" stroke-linecap="round" />

                            <path d="M 52 90 C 57 97, 63 97, 68 90" fill="none" stroke="url(#akOrange)"
                                stroke-width="4" stroke-linecap="round" />
                        </g>

                        <text x="135" y="72" font-family="'Plus Jakarta Sans', sans-serif" font-weight="900"
                            font-size="52" fill="#F97316">
                            Ali-
                        </text>

                        <text x="225" y="72" font-family="'Plus Jakarta Sans', sans-serif" font-weight="900"
                            font-size="52" fill="#043226">
                            Kamer
                        </text>
                    </svg>
                </a>


                {{-- =========================================================
                 NAVIGATION DESKTOP
            ========================================================== --}}
                <nav
                    class="hidden lg:flex
                       flex-1 min-w-0
                       items-center justify-center
                       overflow-hidden">

                    <div
                        class="flex items-center
                           bg-[#F1EFE9]
                           px-1.5 py-1
                           rounded-full
                           shadow-inner
                           text-slate-700
                           font-medium
                           text-xs
                           whitespace-nowrap">

                        {{-- ACCUEIL --}}
                        <a href="{{ route('buyer.home') }}"
                            class="flex items-center gap-1.5
                               px-3 py-1.5
                               rounded-full
                               transition
                               {{ request()->routeIs('buyer.home')
                                   ? 'bg-blue-600 text-white font-bold shadow-sm'
                                   : 'hover:bg-white/70 hover:text-slate-900' }}">

                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />

                            </svg>

                            Accueil
                        </a>


                        @auth

                            {{-- ACHETEUR --}}
                            @if (auth()->user()->role === 'buyer')
                                <a href="{{ route('buyer.orders.index') }}"
                                    class="px-3 py-1.5 rounded-full transition
                                {{ request()->routeIs('buyer.orders.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'hover:bg-white/70' }}">
                                    Commandes
                                </a>

                                <a href="{{ route('buyer.disputes.index') }}"
                                    class="px-3 py-1.5 rounded-full transition
                                {{ request()->routeIs('buyer.disputes.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'hover:bg-white/70' }}">
                                    Litiges
                                </a>
                            @endif


                            {{-- VENDEUR --}}
                            @if (auth()->user()->role === 'seller')
                                <a href="{{ route('seller.products.index') }}"
                                    class="px-3 py-1.5 rounded-full transition
                                {{ request()->routeIs('seller.products.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'hover:bg-white/70' }}">
                                    Produits
                                </a>

                                <a href="{{ route('seller.orders.index') }}"
                                    class="px-3 py-1.5 rounded-full transition
                                {{ request()->routeIs('seller.orders.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'hover:bg-white/70' }}">
                                    Commandes
                                </a>

                                <a href="{{ route('seller.disputes.index') }}"
                                    class="px-3 py-1.5 rounded-full transition
                                {{ request()->routeIs('seller.disputes.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'hover:bg-white/70' }}">
                                    Litiges
                                </a>
                            @endif


                            {{-- MESSAGERIE --}}
                            @if (in_array(auth()->user()->role, ['buyer', 'seller']))
                                <a href="{{ route('messaging.index') }}"
                                    class="flex items-center gap-1
                                       px-3 py-1.5
                                       rounded-full
                                       transition
                                       {{ request()->routeIs('messaging.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'hover:bg-white/70' }}">

                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />

                                    </svg>

                                    Messages
                                </a>
                            @endif


                            {{-- ADMIN --}}
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.disputes.index') }}"
                                    class="px-3 py-1.5 rounded-full transition
                                {{ request()->routeIs('admin.disputes.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'hover:bg-white/70' }}">
                                    Litiges
                                </a>
                            @endif


                            {{-- TUTORIELS --}}
                            <a href="{{ route('tutorials.index') }}"
                                class="px-3 py-1.5 rounded-full transition
                            {{ request()->routeIs('tutorials.*') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'hover:bg-white/70' }}">
                                Tutoriels
                            </a>


                            {{-- AIDE --}}
                            <a href="{{ route('help') }}"
                                class="px-3 py-1.5 rounded-full transition
                            {{ request()->routeIs('help') ? 'bg-blue-600 text-white font-bold shadow-sm' : 'hover:bg-white/70' }}">
                                Aide
                            </a>


                            {{-- ESPACE --}}
                            @php
                                $dashboardRoute = match (auth()->user()->role) {
                                    'seller' => route('seller.dashboard'),
                                    'secretary' => route('secretary.dashboard'),
                                    'admin' => route('admin.dashboard'),
                                    default => route('buyer.dashboard'),
                                };

                                $isDashboardActive = request()->routeIs('*.dashboard');
                            @endphp

                            <a href="{{ $dashboardRoute }}"
                                class="flex items-center gap-1.5
                                   ml-1
                                   px-3.5 py-1.5
                                   rounded-full
                                   font-bold
                                   transition
                                   {{ $isDashboardActive ? 'bg-blue-700 text-white shadow-md' : 'bg-slate-900 text-white hover:bg-black' }}">

                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2-2v-2zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />

                                </svg>

                                Espace
                            </a>

                        @endauth

                    </div>
                </nav>


                {{-- =========================================================
                 PARTIE DROITE
            ========================================================== --}}
                <div class="flex items-center gap-2 shrink-0">
                    {{-- PANIER & FAVORIS --}}
                    @auth
                        @if (auth()->user()->isBuyer())
                            {{-- PANIER --}}
                            @php
                                $cartCount = app(App\Services\CartService::class)->count(auth()->user());
                            @endphp

                            <a href="{{ route('buyer.cart.index') }}" title="Mon panier"
                                class="relative w-9 h-9 shrink-0 flex items-center justify-center
                   rounded-full bg-white border border-slate-200
                   text-slate-600 hover:text-blue-600 hover:border-blue-200
                   hover:bg-blue-50 transition-all duration-200">

                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1 5h13M9 21h.01M18 21h.01" />
                                </svg>

                                @if ($cartCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1
                             min-w-[17px] h-[17px] px-1
                             bg-blue-600 text-white
                             text-[9px] font-black
                             rounded-full
                             flex items-center justify-center
                             border-2 border-[#FAF9F6]">
                                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                                    </span>
                                @endif
                            </a>

                            {{-- FAVORIS --}}
                            @php
                                $wishlistCount = auth()->user()->wishlist()->count();
                            @endphp

                            <a href="{{ route('buyer.wishlist.index') }}" title="Mes favoris"
                                class="relative w-9 h-9 shrink-0 flex items-center justify-center
                   rounded-full bg-white border border-slate-200
                   text-slate-600 hover:text-red-500 hover:border-red-200
                   hover:bg-red-50 transition-all duration-200">

                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06
                                           a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84
                                           a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>

                                @if ($wishlistCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1
                             min-w-[17px] h-[17px] px-1
                             bg-red-500 text-white
                             text-[9px] font-black
                             rounded-full
                             flex items-center justify-center
                             border-2 border-[#FAF9F6]">
                                        {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                                    </span>
                                @endif
                            </a>
                        @endif

                    @endauth
                    {{-- RECHERCHE --}}
                    @php
                        $searchAction = route('buyer.home');
                        $searchPlaceholder = 'Rechercher...';

                        if (request()->routeIs('messaging.*')) {
                            $searchAction = route('messaging.index');
                            $searchPlaceholder = 'Rechercher un message...';
                        } elseif (request()->routeIs('seller.products.*')) {
                            $searchAction = route('seller.products.index');
                            $searchPlaceholder = 'Filtrer vos produits...';
                        } elseif (request()->routeIs('buyer.orders.*') || request()->routeIs('seller.orders.*')) {
                            $searchPlaceholder = 'Rechercher une commande...';
                        }
                    @endphp

                    <form id="navbar-search" action="{{ $searchAction }}" method="GET"
                        class="relative hidden xl:block w-48">

                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="{{ $searchPlaceholder }}"
                            class="w-full
                               h-9
                               bg-white
                               border border-slate-300
                               focus:border-blue-500
                               focus:ring-2
                               focus:ring-blue-500/10
                               rounded-full
                               pl-9 pr-3
                               text-xs
                               outline-none
                               shadow-sm">

                        <svg class="w-4 h-4 text-slate-400
                               absolute left-3 top-1/2
                               -translate-y-1/2"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0a7 7 0 0114 0z" />

                        </svg>

                    </form>


                    @auth

                        @if (auth()->user()->isBuyer())
                            <a href="{{ route('buyer.profile') }}"
                                class="text-sm text-gray-600 hover:text-indigo-600 mx-0">
                                <div title="{{ auth()->user()->name }}"
                                    class="w-9 h-9 shrink-0
                               rounded-full
                               bg-blue-600
                               text-white
                               font-black
                               text-[11px]
                               flex items-center justify-center
                               uppercase
                               border-2 border-white
                               shadow-md">

                                    {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 2)) }}

                                </div>
                            </a>
                        @endif
                        {{-- AVATAR --}}



                        {{-- DÉCONNEXION --}}
                        <form action="{{ route('logout') }}" method="POST" class="shrink-0">

                            @csrf

                            <button type="submit" title="Se déconnecter"
                                class="h-9
                                   inline-flex items-center justify-center
                                   gap-1.5
                                   px-3.5
                                   rounded-full
                                   bg-red-600
                                   hover:bg-red-700
                                   active:scale-95
                                   text-white
                                   text-xs
                                   font-extrabold
                                   border border-red-600
                                   shadow-md shadow-red-600/25
                                   whitespace-nowrap
                                   transition-all duration-200">

                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />

                                </svg>

                                Déconnexion

                            </button>

                        </form>

                    @endauth


                    @guest

                        <div class="hidden sm:flex items-center gap-2">

                            <a href="{{ route('login.show') }}"
                                class="text-xs font-bold text-slate-700 hover:text-blue-600 px-2 py-2">
                                Connexion
                            </a>

                            <a href="{{ route('register.show') }}"
                                class="text-xs font-bold
                                   bg-blue-600 hover:bg-blue-700
                                   text-white
                                   px-3.5 py-2
                                   rounded-full
                                   shadow-md shadow-blue-200">
                                Inscription
                            </a>

                        </div>

                    @endguest


                    {{-- MENU MOBILE : UN SEUL BOUTON --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden
                           p-2
                           rounded-xl
                           bg-slate-200/70
                           text-slate-700
                           hover:bg-slate-200
                           transition">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />

                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>

                </div>

            </div>


            {{-- =============================================================
             MENU MOBILE
        ========================================================== --}}
            <div x-show="mobileMenuOpen" x-cloak x-transition
                class="lg:hidden
                   bg-white
                   border-t border-slate-200
                   mt-3
                   px-2 pt-3 pb-5
                   space-y-1
                   shadow-xl rounded-b-2xl">

                <a href="{{ route('buyer.home') }}"
                    class="block px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-100">
                    Accueil
                </a>

                @auth
                    {{-- Badge panier --}}
                    <a href="{{ route('buyer.cart.index') }}"
                        class="relative flex items-center gap-1 text-gray-600 hover:text-indigo-600">
                        🛒
                        @php
                            $cartCount = auth()->user()->isBuyer()
                                ? app(App\Services\CartService::class)->count(auth()->user())
                                : 0;
                        @endphp
                        @if ($cartCount > 0)
                            <span
                                class="absolute -top-2 -right-2 w-5 h-5 bg-indigo-600 text-white
                         text-xs font-bold rounded-full flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                    @if (auth()->user()->isBuyer())
                        <a href="{{ route('buyer.profile') }}" class="text-sm text-gray-600 hover:text-indigo-600 mx-4">
                            Mon profil
                        </a>
                    @endif
                    {{-- Favoris --}}
                    @if (auth()->user()->isBuyer())
                        <a href="{{ route('buyer.wishlist.index') }}" class="text-gray-600 hover:text-red-500">
                            ♡
                        </a>
                    @endif
                    @if (auth()->user()->role === 'buyer')
                        <a href="{{ route('buyer.orders.index') }}"
                            class="block px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-100">
                            Mes commandes
                        </a>

                        <a href="{{ route('buyer.disputes.index') }}"
                            class="block px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-100">
                            Mes litiges
                        </a>
                    @elseif(auth()->user()->role === 'seller')
                        <a href="{{ route('seller.products.index') }}"
                            class="block px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-100">
                            Mes produits
                        </a>

                        <a href="{{ route('seller.orders.index') }}"
                            class="block px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-100">
                            Commandes reçues
                        </a>

                        <a href="{{ route('seller.disputes.index') }}"
                            class="block px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-100">
                            Gestion des litiges
                        </a>
                    @endif


                    @if (in_array(auth()->user()->role, ['buyer', 'seller']))
                        <a href="{{ route('messaging.index') }}"
                            class="block px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-100">
                            Messagerie
                        </a>
                    @endif


                    <a href="{{ $dashboardRoute }}"
                        class="block px-4 py-2.5 rounded-xl text-sm font-bold bg-slate-900 text-white">
                        Mon espace
                    </a>


                    <div
                        class="pt-3 mt-2 border-t border-slate-200
                            flex items-center justify-between">

                        <span class="text-xs font-bold text-slate-700">
                            {{ auth()->user()->name }}
                        </span>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button type="submit"
                                class="px-3.5 py-2
                                   rounded-lg
                                   bg-red-600 hover:bg-red-700
                                   text-white
                                   text-xs
                                   font-extrabold
                                   shadow-sm">

                                Déconnexion

                            </button>

                        </form>

                    </div>

                @endauth


                @guest

                    <div class="pt-3 mt-2 border-t border-slate-100 flex gap-2">

                        <a href="{{ route('login.show') }}"
                            class="flex-1 text-center text-xs font-bold
                               py-2.5 border border-slate-300 rounded-xl">
                            Connexion
                        </a>

                        <a href="{{ route('register.show') }}"
                            class="flex-1 text-center text-xs font-bold
                               py-2.5 bg-blue-600 text-white rounded-xl">
                            Inscription
                        </a>

                    </div>

                @endguest

            </div>

        </div>

    </header>
    {{-- ESPACEMENT FIXE NAVBAR --}}
    <div class="pt-20"></div>

    {{-- MESSAGES FLASH GLOBAUX --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('success'))
            <div
                class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm mb-4">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('fail') || session('error'))
            <div
                class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm mb-4">
                <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('fail') ?? session('error') }}</span>
            </div>
        @endif
    </div>

    {{-- CONTENU PRINCIPAL --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER MODERNE --}}
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-20 font-sans">
        <!-- Section Principale : Liens et Infos -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                <!-- Colonne 1: À propos & Confiance -->
                <div class="space-y-4">
                    <div class="text-xl font-black text-white">
                        Ali-<span class="text-orange-500">Kamer</span>
                    </div>
                    <p class="text-sm leading-relaxed text-slate-400">
                        La marketplace camerounaise de référence. Achetez et vendez en toute sérénité grâce à notre
                        système d'Escrow sécurisé.
                    </p>
                    <!-- Badges de réassurance -->
                    <div class="pt-2 flex flex-wrap gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 bg-slate-800 text-emerald-400 text-xs font-medium px-2.5 py-1 rounded-full border border-slate-700">
                            🛡️ Escrow Sécurisé
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 bg-slate-800 text-orange-400 text-xs font-medium px-2.5 py-1 rounded-full border border-slate-700">
                            🇨🇲 100% Cameroun
                        </span>
                    </div>
                </div>

                <!-- Colonne 2: Acheter & Vendre -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Navigation</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('tutorials.index') }}"
                                class="hover:text-orange-500 transition-colors flex items-center gap-2">📖 Tutoriels &
                                Guides</a></li>
                        <li><a href="#"
                                class="hover:text-orange-500 transition-colors flex items-center gap-2">🛍️ Explorer
                                les produits</a></li>
                        <li><a href="#"
                                class="hover:text-orange-500 transition-colors flex items-center gap-2">💼 Devenir
                                Vendeur</a></li>
                    </ul>
                </div>

                <!-- Colonne 3: Support & Sécurité -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Aide & Confiance</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('help') }}" class="hover:text-orange-500 transition-colors">Centre
                                d'aide & FAQ</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Comment fonctionne
                                l'Escrow ?</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Signaler un problème</a>
                        </li>
                        <li><a href="{{ route('about') }}" class="hover:text-orange-500 transition-colors">À propos
                                d'Ali-Kamer</a></li>
                    </ul>
                </div>

                <!-- Colonne 4: Modes de Paiement Locaux (Rassure l'acheteur) -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Paiements Sécurisés</h3>
                    <p class="text-xs text-slate-500 mb-3">Nous acceptons vos moyens de paiement locaux préférés :</p>
                    <div class="flex flex-wrap gap-2 text-xs font-bold text-slate-300">
                        <span class="bg-amber-500 text-slate-950 px-2 py-1 rounded shadow-sm">MTN MoMo</span>
                        <span class="bg-orange-600 text-white px-2 py-1 rounded shadow-sm">Orange Money</span>
                        <span class="bg-blue-600 text-white px-2 py-1 rounded shadow-sm">Carte Visa / Express
                            Union</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section Basse : Mentions légales et Copyright -->
        <div class="border-t border-slate-800 bg-slate-950/50">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <div class="text-slate-500 text-center sm:text-left">
                    &copy; {{ date('Y') }} Ali-Kamer. Tous droits réservés.
                </div>
                <div class="flex flex-wrap justify-center gap-6 text-slate-500">
                    <a href="#" class="hover:text-slate-300 transition-colors">Conditions Générales
                        (CGU/CGV)</a>
                    <a href="#" class="hover:text-slate-300 transition-colors">Politique de Confidentialité</a>
                </div>
            </div>
        </div>
    </footer>


    @stack('scripts')

    <!-- Script de visibilité au scroll pour la recherche -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBar = document.getElementById('navbar-search');

            if (searchBar) {
                const isHomePage = {{ request()->routeIs('buyer.home') ? 'true' : 'false' }};

                if (!isHomePage) {
                    searchBar.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-2');
                    searchBar.classList.add('opacity-100', 'translate-y-0');
                } else {
                    window.addEventListener('scroll', function() {
                        if (window.scrollY > 280) {
                            searchBar.classList.remove('opacity-0', 'pointer-events-none',
                                '-translate-y-2');
                            searchBar.classList.add('opacity-100', 'translate-y-0');
                        } else {
                            searchBar.classList.add('opacity-0', 'pointer-events-none', '-translate-y-2');
                            searchBar.classList.remove('opacity-100', 'translate-y-0');
                        }
                    });
                }
            }
        });
    </script>

</body>

</html>
