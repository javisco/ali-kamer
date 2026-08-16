<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Ali-Kamer — Achetez et vendez sans stress')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#FAF9F6] text-slate-800 antialiased font-sans">

    <!-- NAVBAR STYLE "MARCHÉ" -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-[#FAF9F6]/95 backdrop-blur-md border-b border-slate-200/80 py-2.5">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between gap-4">

            {{-- 1. LOGO ALI-KAMER --}}
            <a href="{{ route('buyer.home') }}"
                class="flex items-center gap-2 group transition-transform duration-200 hover:scale-105 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 130" class="h-10 md:h-11 w-auto">
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

                    <!-- Emblème Cercle & Mains & A -->
                    <g transform="translate(10, 5)">
                        <circle cx="60" cy="60" r="54" fill="none" stroke="url(#akGreen)"
                            stroke-width="4" stroke-dasharray="240 80" />
                        <circle cx="60" cy="60" r="54" fill="none" stroke="url(#akOrange)"
                            stroke-width="4" stroke-dasharray="100 200" stroke-dashoffset="-180" />

                        <path d="M 32 92 L 60 22 L 88 92" fill="none" stroke="url(#akGreen)" stroke-width="12"
                            stroke-linejoin="round" stroke-linecap="round" />
                        <path d="M 60 22 L 88 92" fill="none" stroke="url(#akOrange)" stroke-width="12"
                            stroke-linejoin="round" stroke-linecap="round" />

                        <path d="M 38 88 C 45 102, 75 102, 82 88" fill="none" stroke="url(#akGreen)" stroke-width="6"
                            stroke-linecap="round" />
                        <path d="M 52 92 C 58 100, 68 100, 74 92" fill="none" stroke="url(#akOrange)"
                            stroke-width="5" stroke-linecap="round" />

                        <g transform="translate(48, 52) scale(0.6)">
                            <path d="M0 0 h6 l8 20 h22 l6 -14 h-30" fill="none" stroke="url(#akOrange)"
                                stroke-width="4" stroke-linecap="round" />
                            <circle cx="16" cy="25" r="3" fill="#EA580C" />
                            <circle cx="32" cy="25" r="3" fill="#EA580C" />
                        </g>

                        <rect x="8" y="42" width="12" height="12" rx="2" fill="#064E3B" />
                        <rect x="98" y="42" width="14" height="14" rx="2" fill="#F97316" />
                    </g>

                    <text x="145" y="76" font-family="'Poppins', 'Arial Black', sans-serif" font-weight="900"
                        font-size="54" fill="#F97316">Ali-</text>
                    <text x="238" y="76" font-family="'Poppins', 'Arial Black', sans-serif" font-weight="900"
                        font-size="54" fill="#043226">Kamer</text>

                    <polygon points="145,92 148,98 155,98 150,102 152,108 145,104 138,108 140,102 135,98 142,98"
                        fill="#F97316" />
                    <line x1="160" y1="99" x2="380" y2="99" stroke="#043226" stroke-width="2"
                        stroke-linecap="round" />
                </svg>
            </a>

            {{-- 2. MENU CENTRAL ("Pill Nav") --}}
            <nav
                class="hidden md:flex items-center bg-[#F1EFE9] px-3 py-1.5 rounded-full text-slate-700 font-medium text-sm space-x-1 shadow-inner">

                {{-- ACCUEIL / CATALOGUE (Commun à tous) --}}
                <a href="{{ route('buyer.home') }}"
                    class="flex items-center gap-1.5 px-4 py-2 rounded-full transition {{ request()->routeIs('buyer.home') ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'hover:text-black' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Accueil
                </a>

                @auth
                    {{-- ── ACHETEUR ── --}}
                    @if (auth()->user()->role === 'buyer')
                        <a href="{{ route('buyer.orders.index') }}"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-full transition {{ request()->routeIs('buyer.orders.*') ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'hover:text-black' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Commandes
                        </a>
                    @endif

                    {{-- ── VENDEUR ── --}}
                    @if (auth()->user()->role === 'seller')
                        <a href="{{ route('seller.products.index') }}"
                            class="px-4 py-2 rounded-full transition {{ request()->routeIs('seller.products.*') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'hover:text-black' }}">
                            Produits
                        </a>
                        <a href="{{ route('seller.orders.index') }}"
                            class="px-4 py-2 rounded-full transition {{ request()->routeIs('seller.orders.*') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'hover:text-black' }}">
                            Commandes
                        </a>
                    @endif

                    {{-- ── MESSAGERIE (Acheteurs & Vendeurs) ── --}}
                    @if (in_array(auth()->user()->role, ['buyer', 'seller']))
                        <a href="{{ route('messaging.index') }}"
                            class="relative flex items-center gap-1.5 px-4 py-2 rounded-full transition {{ request()->routeIs('messaging.*') ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'hover:text-black' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Messages
                        </a>
                    @endif

                    {{-- ── ADMIN ── --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.disputes.index') }}"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-full transition {{ request()->routeIs('admin.disputes.*') ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'hover:text-black' }}">
                            Litiges
                        </a>
                    @endif

                    {{-- TUTORIELS (Visible pour tous les utilisateurs connectés) --}}
                    {{-- <a href="{{ route('tutorials.index') }}"
                        class="px-4 py-2 rounded-full transition {{ request()->routeIs('tutorials.*') ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'hover:text-black' }}">
                        Tutoriels
                    </a> --}}

                    {{-- BOUTON DASHBOARD DYNAMIQUE --}}
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
                        class="flex items-center gap-2 px-5 py-2 rounded-full font-medium transition ml-1 {{ $isDashboardActive ? 'bg-blue-700 text-white shadow-md' : 'bg-slate-900 text-white hover:bg-black' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>
                @endauth

            </nav>

            {{-- 3. RECHERCHE CONTEXTUELLE & UTILISATEUR --}}
            <div class="flex items-center gap-3">

                <!-- BARRE DE RECHERCHE DYNAMIQUE -->
                @php
                    $searchAction = route('buyer.home');
                    $searchPlaceholder = 'Rechercher un article...';

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
                    class="relative hidden lg:block w-64 transition-all duration-300 opacity-0 pointer-events-none -translate-y-2">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="{{ $searchPlaceholder }}"
                        class="w-full bg-white border border-slate-300 focus:border-blue-600 rounded-full pl-9 pr-4 py-1.5 text-sm outline-none transition shadow-sm placeholder-slate-400">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </form>

                @auth
                    <!-- USER AVATAR & BOUTON DÉCONNEXION -->
                    <div class="flex items-center gap-2">
                        <div title="{{ auth()->user()->name }}"
                            class="w-9 h-9 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center uppercase shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 2)) }}
                        </div>

                        <!-- Bouton Déconnexion -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-2 rounded-full transition border border-rose-200 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login.show') }}"
                        class="text-sm font-semibold text-slate-700 hover:text-blue-600 px-2">Connexion</a>
                    <a href="{{ route('register.show') }}"
                        class="text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full transition shadow-sm">Inscription</a>
                @endguest

            </div>

        </div>
    </header>

    <div class="pt-20"></div>

    @yield('content')

    @stack('scripts')

    <!-- Script de visibilité au scroll -->
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
