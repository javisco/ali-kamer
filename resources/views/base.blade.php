<!DOCTYPE html>

<html lang="fr" class="h-full bg-[#FAF9F6]">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ali-Kamer — Achetez et vendez sans stress')</title>


    {{-- Police --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Assets Laravel --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --ak-green: #00843D;
            --ak-green-dark: #006B32;
            --ak-green-deep: #004D2A;
            --ak-red: #CE1126;
            --ak-yellow: #FCD116;
            --ak-cream: #FAF9F6;
        }

        html {
            background: var(--ak-cream);
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        /* =========================================================
       LOGO
    ========================================================== */

        .ak-logo {
            display: block;
            width: 155px;
            height: 68px;
            object-fit: contain;
        }

        @media (max-width: 640px) {
            .ak-logo {
                width: 125px;
                height: 58px;
            }
        }

        /* =========================================================
       RECHERCHE AU SCROLL
    ========================================================== */

        .ak-scroll-search {
            position: fixed;
            top: 8px;
            left: 50%;
            transform: translate(-50%, -130%);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            width: min(460px, calc(100vw - 150px));
            z-index: 70;
            transition:
                transform 0.32s cubic-bezier(.22, 1, .36, 1),
                opacity 0.22s ease,
                visibility 0.22s ease;
        }

        .ak-scroll-search.is-visible {
            transform: translate(-50%, 0);
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .ak-scroll-search-box {
            height: 38px;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, .97);
            border: 1px solid #d9ebe2;
            border-radius: 999px;
            box-shadow:
                0 8px 25px rgba(0, 77, 42, .12),
                0 2px 8px rgba(0, 0, 0, .05);
            backdrop-filter: blur(16px);
            overflow: hidden;
        }

        .ak-scroll-search-box:focus-within {
            border-color: var(--ak-green);
            box-shadow:
                0 8px 25px rgba(0, 132, 61, .15),
                0 0 0 3px rgba(0, 132, 61, .08);
        }

        .ak-scroll-search input {
            flex: 1;
            min-width: 0;
            height: 100%;
            padding: 0 12px 0 16px;
            background: transparent;
            border: 0;
            outline: 0;
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
        }

        .ak-scroll-search input::placeholder {
            color: #94a3b8;
            font-weight: 500;
        }

        .ak-scroll-search button {
            width: 30px;
            height: 30px;
            margin-right: 4px;
            flex-shrink: 0;
            border: 0;
            border-radius: 999px;
            background: var(--ak-green);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: .2s ease;
        }

        .ak-scroll-search button:hover {
            background: var(--ak-green-dark);
            transform: scale(1.04);
        }

        /* =========================================================
       PETIT INDICATEUR SCROLL
    ========================================================== */

        .ak-scroll-search::before {
            content: "";
            position: absolute;
            left: 50%;
            bottom: -6px;
            width: 32px;
            height: 2px;
            transform: translateX(-50%);
            border-radius: 999px;
            background: linear-gradient(90deg,
                    var(--ak-green) 0 33%,
                    var(--ak-red) 33% 66%,
                    var(--ak-yellow) 66% 100%);
            opacity: .8;
        }

        /* =========================================================
       BARRE DE DÉFILEMENT
    ========================================================== */

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f3;
        }

        ::-webkit-scrollbar-thumb {
            background: #b9d9c8;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--ak-green);
        }

        /* =========================================================
       MOBILE
    ========================================================== */

        @media (max-width: 640px) {
            .ak-scroll-search {
                width: calc(100vw - 105px);
                top: 7px;
            }

            .ak-scroll-search-box {
                height: 36px;
            }

            .ak-scroll-search input {
                font-size: 11px;
                padding-left: 13px;
            }

            .ak-scroll-search button {
                width: 28px;
                height: 28px;
            }
        }

        @media (max-width: 380px) {
            .ak-scroll-search {
                width: calc(100vw - 92px);
            }
        }
    </style>

    @stack('styles')


</head>

<body class="min-h-screen flex flex-col bg-[#FAF9F6] text-slate-800 antialiased">


    {{-- =========================================================
     RECHERCHE COMPACTE APPARAISSANT AU SCROLL
     Elle est volontairement absente au sommet de la page.
========================================================== --}}

    @php
        $searchAction = route('buyer.home');
        $searchPlaceholder = 'Rechercher un produit...';

        if (request()->routeIs('messaging.*')) {
            $searchAction = route('messaging.index');
            $searchPlaceholder = 'Rechercher un message...';
        } elseif (request()->routeIs('seller.products.*')) {
            $searchAction = route('seller.products.index');
            $searchPlaceholder = 'Rechercher dans vos produits...';
        } elseif (request()->routeIs('buyer.orders.*') || request()->routeIs('seller.orders.*')) {
            $searchPlaceholder = 'Rechercher une commande...';
        }
    @endphp




    {{-- =========================================================
     NAVBAR
========================================================== --}}

    <header x-data="{
        mobileMenuOpen: false,
        categoriesOpen: false
    }"
        class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-b border-slate-200 shadow-sm">

        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- =====================================================
             LIGNE PRINCIPALE
        ====================================================== --}}

            <div class="h-[72px] flex items-center gap-3 sm:gap-4">

                {{-- LOGO --}}
                <a href="{{ route('buyer.home') }}" class="flex items-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 420 120" class="h-10 sm:h-12 w-auto">
                        <!-- Icone : Carte / Marqueur vert et orange avec caddie -->
                        <g transform="translate(10, 10)">
                            <!-- Cercle vert -->
                            <path d="M 15 50 A 35 35 0 0 1 75 25" fill="none" stroke="#00843D" stroke-width="8"
                                stroke-linecap="round" />
                            <!-- Cercle rouge/orange -->
                            <path d="M 75 25 A 35 35 0 0 1 65 85" fill="none" stroke="#EA580C" stroke-width="8"
                                stroke-linecap="round" />
                            <!-- Forme A stylisee -->
                            <path d="M 25 75 L 45 20 L 65 75" fill="none" stroke="#00843D" stroke-width="10"
                                stroke-linejoin="round" stroke-linecap="round" />
                            <path d="M 33 55 L 57 55" stroke="#EA580C" stroke-width="8" stroke-linecap="round" />
                            <!-- Caddie minimaliste -->
                            <path d="M 38 42 H 52 L 48 50 H 40 Z" fill="#EA580C" />
                        </g>

                        <!-- Texte : Ali-Kamer -->
                        <text x="105" y="65" font-family="'Plus Jakarta Sans', sans-serif" font-weight="900"
                            font-size="46" fill="#EA580C">Ali-</text>
                        <text x="185" y="65" font-family="'Plus Jakarta Sans', sans-serif" font-weight="900"
                            font-size="46" fill="#004D2A">Kamer</text>

                        <!-- Slogan sous le logo -->
                        <text x="106" y="85" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700"
                            font-size="12" fill="#00843D">Acheter et vendez sans strees</text>

                        <!-- Tagline Cameroun -->
                        <g transform="translate(290, 20)">
                            <rect x="0" y="0" width="115" height="24" rx="12" fill="#F0FDF4"
                                stroke="#DCFCE7" stroke-width="1" />
                            <text x="10" y="16" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700"
                                font-size="9" fill="#00843D">Construit au Cameroun</text>
                            <!-- Drapeau discret -->
                            <rect x="95" y="7" width="3" height="10" fill="#00843D" />
                            <rect x="98" y="7" width="3" height="10" fill="#CE1126" />
                            <rect x="101" y="7" width="3" height="10" fill="#FCD116" />
                        </g>
                    </svg>
                </a>



                {{-- RECHERCHE NORMALE AU SOMMET
                 Petite et discrète : elle disparaît sur scroll.
            --}}
                <form action="{{ $searchAction }}" method="GET" class="hidden sm:flex flex-1 max-w-lg mx-auto">

                    <div
                        class="relative w-full h-9 bg-[#F8FAF9] border border-emerald-100 rounded-full overflow-hidden
                            focus-within:border-[#00843D]
                            focus-within:ring-4
                            focus-within:ring-emerald-500/10
                            transition">

                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="{{ $searchPlaceholder }}" autocomplete="off"
                            class="w-full h-full bg-transparent border-0 outline-none
                               pl-4 pr-12 text-xs text-slate-800
                               placeholder:text-slate-400">

                        <button type="submit" aria-label="Rechercher"
                            class="absolute right-1 top-1 w-7 h-7 rounded-full
                               bg-[#00843D] hover:bg-[#006B32]
                               text-white flex items-center justify-center transition">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1 8 8 8 8 0 0 1 16 0z" />
                            </svg>
                        </button>
                    </div>
                </form>


                {{-- ACTIONS À DROITE --}}
                <div class="ml-auto flex items-center gap-1 sm:gap-2 shrink-0">

                    @auth

                        @include('components.notification-bell')

                        @if (auth()->user()->isBuyer())
                            @php
                                $cartCount = app(App\Services\CartService::class)->count(auth()->user());
                                $wishlistCount = auth()->user()->wishlist()->count();
                            @endphp

                            {{-- FAVORIS --}}
                            <a href="{{ route('buyer.wishlist.index') }}" title="Mes favoris" aria-label="Mes favoris"
                                class="relative w-10 h-10 rounded-full flex items-center justify-center
                                   text-slate-700 hover:text-[#CE1126] hover:bg-red-50 transition">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>

                                @if ($wishlistCount > 0)
                                    <span
                                        class="absolute -top-0.5 -right-0.5 min-w-[17px] h-[17px] px-1
                                             bg-[#CE1126] text-white text-[9px] font-black rounded-full
                                             flex items-center justify-center border-2 border-white">

                                        {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                                    </span>
                                @endif
                            </a>


                            {{-- PANIER --}}
                            <a href="{{ route('buyer.cart.index') }}" title="Mon panier" aria-label="Mon panier"
                                class="relative w-10 h-10 rounded-full flex items-center justify-center
                                   text-slate-700 hover:text-[#00843D] hover:bg-emerald-50 transition">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1 5h13M9 21h.01M18 21h.01" />
                                </svg>

                                @if ($cartCount > 0)
                                    <span
                                        class="absolute -top-0.5 -right-0.5 min-w-[17px] h-[17px] px-1
                                             bg-[#CE1126] text-white text-[9px] font-black rounded-full
                                             flex items-center justify-center border-2 border-white">

                                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                                    </span>
                                @endif
                            </a>
                        @endif


                        {{-- DASHBOARD --}}
                        @php
                            $dashboardRoute = match (auth()->user()->role) {
                                'seller' => route('seller.dashboard'),
                                'secretary' => route('secretary.dashboard'),
                                'admin' => route('admin.dashboard'),
                                'agency_manager' => route('agency.dashboard'),
                                default => route('buyer.dashboard'),
                            };
                        @endphp

                        <a href="{{ $dashboardRoute }}" title="Mon espace"
                            class="hidden md:flex items-center gap-2 h-10 px-4 rounded-full
                               bg-[#00843D] hover:bg-[#006B32]
                               text-white text-xs font-extrabold shadow-sm transition">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6zm10 0a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2V6zM4 16a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2zm10 0a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-2z" />
                            </svg>

                            Mon espace
                        </a>


                        @if (auth()->user()->isBuyer())
                            <a href="{{ route('buyer.profile') }}" title="{{ auth()->user()->name }}"
                                aria-label="Mon profil" class="hidden sm:block">

                                <div
                                    class="w-10 h-10 rounded-full bg-[#006B32] text-white font-black text-xs
                                        flex items-center justify-center uppercase border-2 border-white
                                        shadow-sm hover:scale-105 transition">

                                    {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 2)) }}
                                </div>
                            </a>
                        @endif


                        <form action="{{ route('logout') }}" method="POST" class="hidden lg:block">

                            @csrf

                            <button type="submit" title="Se déconnecter"
                                class="h-10 px-4 rounded-full border border-red-100
                                   bg-red-50 text-[#CE1126]
                                   hover:bg-[#CE1126] hover:text-white
                                   text-xs font-extrabold transition">

                                Déconnexion
                            </button>
                        </form>

                    @endauth


                    @guest

                        <a href="{{ route('login.show') }}"
                            class="hidden sm:flex h-10 px-4 items-center
                               text-sm font-bold text-slate-700
                               hover:text-[#00843D]">

                            Connexion
                        </a>

                        <a href="{{ route('register.show') }}"
                            class="hidden sm:flex h-10 px-5 items-center
                               rounded-full bg-[#00843D]
                               hover:bg-[#006B32]
                               text-white text-sm font-extrabold shadow-sm">

                            Inscription
                        </a>

                    @endguest


                    {{-- HAMBURGER MOBILE --}}
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen; categoriesOpen = false"
                        :aria-expanded="mobileMenuOpen.toString()" aria-label="Ouvrir le menu"
                        class="lg:hidden w-10 h-10 rounded-full
                           bg-emerald-50 text-[#00843D]
                           flex items-center justify-center">

                        <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>

                        <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>

                    </button>

                </div>
            </div>


            {{-- =====================================================
             PETITE NAVBAR DESKTOP
        ====================================================== --}}

            <div class="hidden lg:flex h-[46px] items-center border-t border-slate-100">

                {{-- CATÉGORIES --}}
                <div class="relative h-full shrink-0">

                    <button type="button" @click="categoriesOpen = !categoriesOpen"
                        :aria-expanded="categoriesOpen.toString()"
                        class="h-full flex items-center gap-2.5 pr-6
                           text-sm font-extrabold text-slate-800
                           hover:text-[#00843D] transition">

                        <span
                            class="w-8 h-8 rounded-lg bg-[#00843D] text-white
                                 flex items-center justify-center">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>

                        </span>

                        <span>Toutes les catégories</span>

                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': categoriesOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" />
                        </svg>

                    </button>
                </div>


                <div class="w-px h-6 bg-slate-200 mx-3"></div>


                {{-- LIENS --}}
                <nav class="flex items-center gap-1 text-[13px] font-bold whitespace-nowrap">

                    <a href="{{ route('buyer.home') }}"
                        class="px-3.5 py-2 rounded-lg text-[#00843D]
                           bg-emerald-50 hover:bg-emerald-100 transition">

                        Accueil
                    </a>

                    <a href="{{ route('buyer.home') }}#produits"
                        class="px-3.5 py-2 rounded-lg text-slate-600
                           hover:text-[#00843D] hover:bg-emerald-50 transition">

                        Boutiques
                    </a>

                    <a href="{{ route('buyer.home') }}#produits"
                        class="px-3.5 py-2 rounded-lg text-slate-600
                           hover:text-[#CE1126] hover:bg-red-50 transition">

                        Promotions
                    </a>

                    <a href="{{ route('tutorials.index') }}"
                        class="px-3.5 py-2 rounded-lg text-slate-600
                           hover:text-[#00843D] hover:bg-emerald-50 transition">

                        Comment ça marche ?
                    </a>

                    <a href="{{ route('buyer.home') }}#agences"
                        class="px-3.5 py-2 rounded-lg text-slate-600
                           hover:text-[#00843D] hover:bg-emerald-50 transition">

                        Nos agences
                    </a>

                    {{-- À PROPOS --}}
                    <a href="{{ route('about') }}"
                        class="px-3.5 py-2 rounded-lg text-slate-600
                           hover:text-[#CE1126] hover:bg-red-50 transition">

                        À propos
                    </a>

                    <a href="{{ route('help') }}"
                        class="px-3.5 py-2 rounded-lg text-slate-600
                           hover:text-[#00843D] hover:bg-emerald-50 transition">

                        Aide
                    </a>

                </nav>
            </div>


            {{-- =====================================================
             PETITE NAVBAR MOBILE
        ====================================================== --}}

            <div
                class="lg:hidden flex items-center gap-2 h-[42px]
                    border-t border-slate-100 overflow-x-auto scrollbar-hide">

                <button type="button" @click="categoriesOpen = !categoriesOpen; mobileMenuOpen = false"
                    :aria-expanded="categoriesOpen.toString()"
                    class="shrink-0 flex items-center gap-1.5 px-3 py-1.5
                       rounded-lg bg-[#00843D] text-white
                       text-[11px] font-extrabold">

                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    Catégories
                </button>


                <a href="{{ route('buyer.home') }}"
                    class="shrink-0 px-3 py-1.5 rounded-lg
                       bg-emerald-50 text-[#00843D]
                       text-[11px] font-extrabold">

                    Accueil
                </a>


                <a href="{{ route('buyer.home') }}#produits"
                    class="shrink-0 px-3 py-1.5 text-slate-600 text-[11px] font-bold">

                    Boutiques
                </a>


                <a href="{{ route('buyer.home') }}#produits"
                    class="shrink-0 px-3 py-1.5 text-slate-600 text-[11px] font-bold">

                    Promotions
                </a>


                <a href="{{ route('tutorials.index') }}"
                    class="shrink-0 px-3 py-1.5 text-slate-600 text-[11px] font-bold">

                    Comment ça marche ?
                </a>


                <a href="{{ route('buyer.home') }}#agences"
                    class="shrink-0 px-3 py-1.5 text-slate-600 text-[11px] font-bold">

                    Nos agences
                </a>


                {{-- À PROPOS MOBILE --}}
                <a href="{{ route('about') }}"
                    class="shrink-0 px-3 py-1.5 text-slate-600
                       hover:text-[#CE1126]
                       text-[11px] font-bold">

                    À propos
                </a>


                <a href="{{ route('help') }}" class="shrink-0 px-3 py-1.5 text-slate-600 text-[11px] font-bold">

                    Aide
                </a>

            </div>


            {{-- =====================================================
             PANNEAU CATÉGORIES
        ====================================================== --}}

            <div x-show="categoriesOpen" x-cloak @click.outside="categoriesOpen = false"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="absolute left-0 right-0 top-[118px]
                   bg-white border-t border-slate-100
                   border-b shadow-xl">

                <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-5">

                    <div class="flex items-center justify-between mb-4">

                        <div>
                            <p
                                class="text-[11px] uppercase tracking-wider
                                  font-black text-[#00843D]">

                                Explorer Ali-Kamer
                            </p>

                            <h3 class="text-lg font-black text-slate-900">
                                Toutes les catégories
                            </h3>
                        </div>

                        <button type="button" @click="categoriesOpen = false"
                            class="w-9 h-9 rounded-full bg-slate-100
                               hover:bg-slate-200 text-slate-500
                               flex items-center justify-center transition"
                            aria-label="Fermer les catégories">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 6l12 12M18 6L6 18" />
                            </svg>

                        </button>
                    </div>


                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5">

                        @php
                            $akCategories = [
                                ['name' => 'Électronique', 'icon' => '📱'],
                                ['name' => 'Mode & vêtements', 'icon' => '👕'],
                                ['name' => 'Maison', 'icon' => '🏠'],
                                ['name' => 'Beauté & soins', 'icon' => '✨'],
                                ['name' => 'Téléphones', 'icon' => '📲'],
                                ['name' => 'Informatique', 'icon' => '💻'],
                                ['name' => 'Électroménager', 'icon' => '🧺'],
                                ['name' => 'Alimentation', 'icon' => '🥜'],
                                ['name' => 'Auto & moto', 'icon' => '🚗'],
                                ['name' => 'Agriculture', 'icon' => '🌱'],
                            ];
                        @endphp

                        @foreach ($akCategories as $category)
                            <a href="{{ route('buyer.home') }}#produits"
                                class="group flex items-center gap-3 p-3 rounded-xl
                                   border border-slate-100 bg-[#FAF9F6]
                                   hover:bg-emerald-50
                                   hover:border-emerald-200 transition">

                                <span
                                    class="w-9 h-9 shrink-0 rounded-lg
                                         bg-white border border-slate-100
                                         flex items-center justify-center
                                         text-lg shadow-sm">

                                    {{ $category['icon'] }}
                                </span>

                                <span class="min-w-0">

                                    <span
                                        class="block text-xs font-extrabold
                                             text-slate-800
                                             group-hover:text-[#00843D]">

                                        {{ $category['name'] }}
                                    </span>

                                    <span class="block text-[9px] text-slate-400 mt-0.5">
                                        Découvrir
                                    </span>

                                </span>

                                <svg class="w-3.5 h-3.5 ml-auto
                                        text-slate-300
                                        group-hover:text-[#00843D]
                                        group-hover:translate-x-0.5 transition"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m9 18 6-6-6-6" />
                                </svg>

                            </a>
                        @endforeach

                    </div>
                </div>
            </div>


            {{-- =====================================================
             MENU MOBILE
        ====================================================== --}}

            <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="lg:hidden absolute left-0 right-0 top-full
                   bg-white border-t border-emerald-100 shadow-xl">

                <div class="max-w-[1440px] mx-auto px-4 py-4">

                    <div class="grid grid-cols-2 gap-2">

                        <a href="{{ route('buyer.home') }}"
                            class="px-4 py-3 rounded-xl bg-emerald-50
                               text-[#00843D] text-sm font-bold">
                            Accueil
                        </a>

                        <a href="{{ route('buyer.home') }}#produits"
                            class="px-4 py-3 rounded-xl bg-slate-50
                               text-slate-700 text-sm font-bold">
                            Boutiques
                        </a>

                        <a href="{{ route('buyer.home') }}#produits"
                            class="px-4 py-3 rounded-xl bg-red-50
                               text-[#CE1126] text-sm font-bold">
                            Promotions
                        </a>

                        <a href="{{ route('tutorials.index') }}"
                            class="px-4 py-3 rounded-xl bg-emerald-50
                               text-[#00843D] text-sm font-bold">
                            Comment ça marche ?
                        </a>

                        <a href="{{ route('buyer.home') }}#agences"
                            class="px-4 py-3 rounded-xl bg-yellow-50
                               text-[#806600] text-sm font-bold">
                            Nos agences
                        </a>

                        {{-- À PROPOS --}}
                        <a href="{{ route('about') }}"
                            class="px-4 py-3 rounded-xl bg-red-50
                               text-[#CE1126] text-sm font-bold">
                            À propos
                        </a>

                        <a href="{{ route('help') }}"
                            class="px-4 py-3 rounded-xl bg-slate-50
                               text-slate-700 text-sm font-bold">
                            Aide
                        </a>


                        @auth

                            <a href="{{ $dashboardRoute }}"
                                class="px-4 py-3 rounded-xl bg-[#00843D]
                                   text-white text-sm font-bold">
                                Mon espace
                            </a>

                            @if (auth()->user()->isBuyer())
                                <a href="{{ route('buyer.wishlist.index') }}"
                                    class="px-4 py-3 rounded-xl bg-red-50
                                       text-[#CE1126] text-sm font-bold">
                                    Mes favoris
                                </a>

                                <a href="{{ route('buyer.cart.index') }}"
                                    class="px-4 py-3 rounded-xl bg-yellow-50
                                       text-[#806600] text-sm font-bold">
                                    Mon panier
                                </a>

                                <a href="{{ route('buyer.profile') }}"
                                    class="px-4 py-3 rounded-xl bg-slate-50
                                       text-slate-700 text-sm font-bold">
                                    Mon profil
                                </a>
                            @endif


                            <form action="{{ route('logout') }}" method="POST" class="col-span-2">

                                @csrf

                                <button type="submit"
                                    class="w-full px-4 py-3 rounded-xl
                                       bg-red-50 text-[#CE1126]
                                       text-sm font-bold">

                                    Déconnexion
                                </button>
                            </form>

                        @endauth


                        @guest

                            <a href="{{ route('login.show') }}"
                                class="px-4 py-3 rounded-xl border
                                   border-emerald-100 text-slate-700
                                   text-sm font-bold">

                                Connexion
                            </a>

                            <a href="{{ route('register.show') }}"
                                class="px-4 py-3 rounded-xl bg-[#00843D]
                                   text-white text-sm font-bold">

                                Inscription
                            </a>

                        @endguest

                    </div>
                </div>
            </div>

        </div>
    </header>


    {{-- =========================================================
     ESPACE RÉSERVÉ À LA NAVBAR

     La recherche n'occupe PLUS d'espace supplémentaire.
     Elle est flottante au-dessus du contenu lorsqu'elle apparaît.

     Desktop : 118px
     Mobile  : 114px
========================================================== --}}

    <div class="pt-[114px] lg:pt-[118px] shrink-0"></div>


    {{-- =========================================================
     MESSAGES FLASH
========================================================== --}}

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">

        @if (session('success'))
            <div
                class="flex items-center gap-3 rounded-2xl
                    border border-emerald-200 bg-emerald-50 p-4
                    text-sm text-emerald-800 shadow-sm mb-4">

                <svg class="h-5 w-5 shrink-0 text-[#00843D]" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>

                <span>{{ session('success') }}</span>
            </div>
        @endif


        @if (session('fail') || session('error'))
            <div
                class="flex items-center gap-3 rounded-2xl
                    border border-red-200 bg-red-50 p-4
                    text-sm text-red-800 shadow-sm mb-4">

                <svg class="h-5 w-5 shrink-0 text-[#CE1126]" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <span>{{ session('fail') ?? session('error') }}</span>
            </div>
        @endif

    </div>


    {{-- =========================================================
     CONTENU
========================================================== --}}

    <main class="flex-grow">
        @yield('content')
    </main>


    {{-- =========================================================
     FOOTER
========================================================== --}}

    <footer class="bg-[#004D2A] text-white border-t border-emerald-900 mt-16">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- COLONNE 1 --}}
                <div class="space-y-4">

                    <a href="{{ route('buyer.home') }}" class="inline-block">

                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer" width="155" height="68"
                            class="w-[150px] h-auto object-contain">

                    </a>

                    <p class="text-sm leading-relaxed text-emerald-50/80">
                        La marketplace construite au Cameroun pour l'Afrique.
                        Achetez et vendez sans stress, en toute confiance.
                    </p>

                    <div class="flex flex-wrap gap-2">

                        <span
                            class="inline-flex items-center gap-1.5
                                 bg-white/10 text-emerald-100
                                 text-xs font-semibold px-3 py-1.5
                                 rounded-full border border-white/10">

                            🛡️ Transactions sécurisées
                        </span>

                        <span
                            class="inline-flex items-center gap-1.5
                                 bg-white/10 text-yellow-200
                                 text-xs font-semibold px-3 py-1.5
                                 rounded-full border border-white/10">

                            🇨🇲 100% Cameroun
                        </span>

                    </div>
                </div>


                {{-- COLONNE 2 --}}
                <div>

                    <h3
                        class="text-white font-extrabold text-sm
                           tracking-wide uppercase mb-4">

                        Navigation
                    </h3>

                    <ul class="space-y-3 text-sm">

                        <li>
                            <a href="{{ route('buyer.home') }}"
                                class="text-emerald-50/80 hover:text-[#FCD116] transition">
                                Accueil
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('tutorials.index') }}"
                                class="text-emerald-50/80 hover:text-[#FCD116] transition">
                                Tutoriels & Guides
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('about') }}"
                                class="text-emerald-50/80 hover:text-[#FCD116] transition">
                                À propos d'Ali-Kamer
                            </a>
                        </li>

                    </ul>
                </div>


                {{-- COLONNE 3 --}}
                <div>

                    <h3
                        class="text-white font-extrabold text-sm
                           tracking-wide uppercase mb-4">

                        Aide & confiance
                    </h3>

                    <ul class="space-y-3 text-sm">

                        <li>
                            <a href="{{ route('help') }}" class="text-emerald-50/80 hover:text-[#FCD116] transition">
                                Centre d'aide & FAQ
                            </a>
                        </li>

                        <li>
                            <a href="#" class="text-emerald-50/80 hover:text-[#FCD116] transition">
                                Comment fonctionne l'Escrow ?
                            </a>
                        </li>

                        <li>
                            <a href="#" class="text-emerald-50/80 hover:text-[#FCD116] transition">
                                Signaler un problème
                            </a>
                        </li>

                    </ul>
                </div>


                {{-- COLONNE 4 --}}
                <div>

                    <h3
                        class="text-white font-extrabold text-sm
                           tracking-wide uppercase mb-4">

                        Paiements locaux
                    </h3>

                    <p class="text-xs text-emerald-50/70 mb-4 leading-relaxed">
                        Payez facilement avec vos moyens de paiement locaux.
                    </p>

                    <div class="flex flex-wrap gap-2 text-xs font-bold">

                        <span
                            class="bg-[#FCD116] text-slate-900
                                 px-3 py-1.5 rounded-lg">
                            MTN MoMo
                        </span>

                        <span class="bg-[#CE1126] text-white
                                 px-3 py-1.5 rounded-lg">
                            Orange Money
                        </span>

                        <span
                            class="bg-white/10 text-white
                                 px-3 py-1.5 rounded-lg
                                 border border-white/10">
                            Carte
                        </span>

                    </div>
                </div>

            </div>
        </div>


        {{-- BAS FOOTER --}}
        <div class="border-t border-white/10">

            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
                    py-5 flex flex-col sm:flex-row
                    items-center justify-between gap-3 text-xs">

                <div class="text-emerald-50/70 text-center sm:text-left">
                    &copy; {{ date('Y') }} Ali-Kamer. Tous droits réservés.
                </div>

                <div class="flex flex-wrap justify-center gap-5">

                    <a href="#" class="text-emerald-50/70 hover:text-white transition">
                        Conditions Générales
                    </a>

                    <a href="#" class="text-emerald-50/70 hover:text-white transition">
                        Politique de Confidentialité
                    </a>

                    <span class="text-[#FCD116] font-semibold">
                        Construit au Cameroun pour l'Afrique 🇨🇲
                    </span>

                </div>
            </div>
        </div>

    </footer>


    {{-- =========================================================
     SCRIPT : RECHERCHE APPARAÎT APRÈS LE SCROLL
========================================================== --}}

    @stack('scripts')


</body>

</html>
