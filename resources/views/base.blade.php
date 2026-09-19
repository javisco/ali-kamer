<!DOCTYPE html>

<html lang="fr" class="h-full bg-slate-50">

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
            --ak-teal: #0D9488;
            --ak-teal-dark: #0F766E;
            --ak-teal-deep: #134E4A;
            --ak-accent: #EA580C;
            --ak-accent-light: #F97316;
            --ak-bg: #F8FAFC;
        }

        html {
            background: var(--ak-bg);
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
            border: 1px solid #E2E8F0;
            border-radius: 999px;
            box-shadow:
                0 8px 25px rgba(13, 148, 136, .10),
                0 2px 8px rgba(0, 0, 0, .05);
            backdrop-filter: blur(16px);
            overflow: hidden;
        }

        .ak-scroll-search-box:focus-within {
            border-color: var(--ak-teal);
            box-shadow:
                0 8px 25px rgba(13, 148, 136, .15),
                0 0 0 3px rgba(13, 148, 136, .12);
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
            color: #0F172A;
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
            background: var(--ak-teal);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: .2s ease;
        }

        .ak-scroll-search button:hover {
            background: var(--ak-teal-dark);
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
                    var(--ak-teal) 0 60%,
                    var(--ak-accent) 60% 100%);
            opacity: .8;
        }

        /* =========================================================
       BARRE DE DÉFILEMENT
    ========================================================== */

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #99f6e4;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--ak-teal);
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

<body class="min-h-screen flex flex-col bg-slate-50 text-slate-900 antialiased">


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
        class="fixed top-0 left-0 right-0 z-50 bg-primary-600 backdrop-blur-xl border-b border-primary-700 shadow-sm">

        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- =====================================================
             LIGNE PRINCIPALE
        ====================================================== --}}

            <div class="h-[72px] flex items-center gap-3 sm:gap-4">

                {{-- LOGO --}}
                <a href="{{ route('buyer.home') }}" class="flex items-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 420 120" class="h-10 sm:h-12 w-auto">
                        <!-- Icone : Carte / Marqueur teal et orange avec caddie -->
                        <g transform="translate(10, 10)">
                            <!-- Cercle teal -->
                            <path d="M 15 50 A 35 35 0 0 1 75 25" fill="none" stroke="#0D9488" stroke-width="8"
                                stroke-linecap="round" />
                            <!-- Cercle orange accent -->
                            <path d="M 75 25 A 35 35 0 0 1 65 85" fill="none" stroke="#EA580C" stroke-width="8"
                                stroke-linecap="round" />
                            <!-- Forme A stylisee -->
                            <path d="M 25 75 L 45 20 L 65 75" fill="none" stroke="#0D9488" stroke-width="10"
                                stroke-linejoin="round" stroke-linecap="round" />
                            <path d="M 33 55 L 57 55" stroke="#EA580C" stroke-width="8" stroke-linecap="round" />
                            <!-- Caddie minimaliste -->
                            <path d="M 38 42 H 52 L 48 50 H 40 Z" fill="#EA580C" />
                        </g>

                        <!-- Texte : Ali-Kamer -->
                        <text x="105" y="65" font-family="'Plus Jakarta Sans', sans-serif" font-weight="900"
                            font-size="46" fill="#EA580C">Ali-</text>
                        <text x="185" y="65" font-family="'Plus Jakarta Sans', sans-serif" font-weight="900"
                            font-size="46" fill="#FFFFFF">Kamer</text>

                        <!-- Slogan sous le logo -->
                        <text x="106" y="85" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700"
                            font-size="12" fill="#CCFBF1">Achetez et vendez sans stress</text>

                        <!-- Tagline Cameroun -->
                        <g transform="translate(290, 20)">
                            <rect x="0" y="0" width="115" height="24" rx="12" fill="#F0FDFA"
                                stroke="#CCFBF1" stroke-width="1" />
                            <text x="10" y="16" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700"
                                font-size="9" fill="#0D9488">Construit au Cameroun</text>
                            <!-- Drapeau discret -->
                            <rect x="95" y="7" width="3" height="10" fill="#0D9488" />
                            <rect x="98" y="7" width="3" height="10" fill="#EF4444" />
                            <rect x="101" y="7" width="3" height="10" fill="#F59E0B" />
                        </g>
                    </svg>
                </a>



                {{-- RECHERCHE NORMALE AU SOMMET
                 Petite et discrète : elle disparaît sur scroll.
            --}}
                <form action="{{ $searchAction }}" method="GET" class="hidden sm:flex flex-1 max-w-lg mx-auto">

                    <div
                        class="relative w-full h-9 bg-white border border-white/80 rounded-full overflow-hidden
                            focus-within:border-white
                            focus-within:ring-4
                            focus-within:ring-white/25
                            transition">

                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="{{ $searchPlaceholder }}" autocomplete="off"
                            class="w-full h-full bg-transparent border-0 outline-none
                               pl-4 pr-12 text-xs text-slate-900
                               placeholder:text-slate-400">

                        <button type="submit" aria-label="Rechercher"
                            class="absolute right-1 top-1 w-7 h-7 rounded-full
                               bg-primary-600 hover:bg-primary-700
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
                                   text-white hover:text-accent-200 hover:bg-white/10 transition">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>

                                @if ($wishlistCount > 0)
                                    <span
                                        class="absolute -top-0.5 -right-0.5 min-w-[17px] h-[17px] px-1
                                             bg-accent-600 text-white text-[9px] font-black rounded-full
                                             flex items-center justify-center border-2 border-white">

                                        {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                                    </span>
                                @endif
                            </a>


                            {{-- PANIER --}}
                            <a href="{{ route('buyer.cart.index') }}" title="Mon panier" aria-label="Mon panier"
                                class="relative w-10 h-10 rounded-full flex items-center justify-center
                                   text-white hover:text-primary-100 hover:bg-white/10 transition">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1 5h13M9 21h.01M18 21h.01" />
                                </svg>

                                @if ($cartCount > 0)
                                    <span
                                        class="absolute -top-0.5 -right-0.5 min-w-[17px] h-[17px] px-1
                                             bg-accent-600 text-white text-[9px] font-black rounded-full
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
                               bg-white hover:bg-primary-50
                               text-primary-700 text-xs font-extrabold shadow-sm transition">

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
                                    class="w-10 h-10 rounded-full bg-primary-600 text-white font-black text-xs
                                        flex items-center justify-center uppercase border-2 border-white
                                        shadow-sm hover:scale-105 transition">

                                    {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 2)) }}
                                </div>
                            </a>
                        @endif


                        <form action="{{ route('logout') }}" method="POST" class="hidden lg:block">

                            @csrf

                            <button type="submit" title="Se déconnecter"
                                class="h-10 px-4 rounded-full border border-danger-100
                                   bg-danger-50 text-danger
                                   hover:bg-danger hover:text-white
                                   text-xs font-extrabold transition">

                                Déconnexion
                            </button>
                        </form>

                    @endauth


                    @guest

                        <a href="{{ route('login.show') }}"
                            class="hidden sm:flex h-10 px-4 items-center
                               text-sm font-bold text-white
                               hover:text-primary-100 transition">

                            Connexion
                        </a>

                        <a href="{{ route('register.show') }}"
                            class="hidden sm:flex h-10 px-5 items-center
                               rounded-full bg-white
                               hover:bg-primary-50
                               text-primary-700 text-sm font-extrabold shadow-sm transition">

                            Inscription
                        </a>

                    @endguest


                    {{-- HAMBURGER MOBILE --}}
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen; categoriesOpen = false"
                        :aria-expanded="mobileMenuOpen.toString()" aria-label="Ouvrir le menu"
                        class="lg:hidden w-10 h-10 rounded-full
                           bg-white/15 text-white
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

            <div class="hidden lg:flex h-[46px] items-center border-t border-white/15">

                {{-- CATÉGORIES --}}
                <div class="relative h-full shrink-0">

                    <button type="button" @click="categoriesOpen = !categoriesOpen"
                        :aria-expanded="categoriesOpen.toString()"
                        class="h-full flex items-center gap-2.5 pr-6
                           text-sm font-extrabold text-white
                           hover:text-primary-100 transition">

                        <span
                            class="w-8 h-8 rounded-lg bg-white text-primary-700
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


                <div class="w-px h-6 bg-white/20 mx-3"></div>


                {{-- LIENS --}}
                <nav class="flex items-center gap-1 text-[13px] font-bold whitespace-nowrap">

                    <a href="{{ route('buyer.home') }}"
                        class="px-3.5 py-2 rounded-lg text-white
                           bg-white/15 hover:bg-white/25 transition">

                        Accueil
                    </a>

                    <a href="{{ route('buyer.home') }}#produits"
                        class="px-3.5 py-2 rounded-lg text-white/80
                           hover:text-white hover:bg-white/10 transition">

                        Boutiques
                    </a>

                    <a href="{{ route('buyer.home') }}#produits"
                        class="px-3.5 py-2 rounded-lg text-white/80
                           hover:text-white hover:bg-white/10 transition">

                        Promotions
                    </a>

                    <a href="{{ route('tutorials.index') }}"
                        class="px-3.5 py-2 rounded-lg text-white/80
                           hover:text-white hover:bg-white/10 transition">

                        Comment ça marche ?
                    </a>

                    <a href="{{ route('buyer.home') }}#agences"
                        class="px-3.5 py-2 rounded-lg text-white/80
                           hover:text-white hover:bg-white/10 transition">

                        Nos agences
                    </a>

                    {{-- À PROPOS --}}
                    <a href="{{ route('about') }}"
                        class="px-3.5 py-2 rounded-lg text-white/80
                           hover:text-white hover:bg-white/10 transition">

                        À propos
                    </a>

                    <a href="{{ route('help') }}"
                        class="px-3.5 py-2 rounded-lg text-white/80
                           hover:text-white hover:bg-white/10 transition">

                        Aide
                    </a>

                </nav>
            </div>


            {{-- =====================================================
             PETITE NAVBAR MOBILE
        ====================================================== --}}

            <div
                class="lg:hidden flex items-center gap-2 h-[42px]
                    border-t border-white/15 overflow-x-auto scrollbar-hide">

                <button type="button" @click="categoriesOpen = !categoriesOpen; mobileMenuOpen = false"
                    :aria-expanded="categoriesOpen.toString()"
                    class="shrink-0 flex items-center gap-1.5 px-3 py-1.5
                       rounded-lg bg-white text-primary-700
                       text-[11px] font-extrabold">

                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    Catégories
                </button>


                <a href="{{ route('buyer.home') }}"
                    class="shrink-0 px-3 py-1.5 rounded-lg
                       bg-white/15 text-white
                       text-[11px] font-extrabold">

                    Accueil
                </a>


                <a href="{{ route('buyer.home') }}#produits"
                    class="shrink-0 px-3 py-1.5 text-white/80 hover:text-white text-[11px] font-bold transition">

                    Boutiques
                </a>


                <a href="{{ route('buyer.home') }}#produits"
                    class="shrink-0 px-3 py-1.5 text-white/80 hover:text-white text-[11px] font-bold transition">

                    Promotions
                </a>


                <a href="{{ route('tutorials.index') }}"
                    class="shrink-0 px-3 py-1.5 text-white/80 hover:text-white text-[11px] font-bold transition">

                    Comment ça marche ?
                </a>


                <a href="{{ route('buyer.home') }}#agences"
                    class="shrink-0 px-3 py-1.5 text-white/80 hover:text-white text-[11px] font-bold transition">

                    Nos agences
                </a>


                {{-- À PROPOS MOBILE --}}
                <a href="{{ route('about') }}"
                    class="shrink-0 px-3 py-1.5 text-white/80
                       hover:text-white
                       text-[11px] font-bold transition">

                    À propos
                </a>


                <a href="{{ route('help') }}" class="shrink-0 px-3 py-1.5 text-white/80 hover:text-white text-[11px] font-bold transition">

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
                                  font-black text-primary-600">

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
                                   border border-slate-100 bg-slate-50/60
                                   hover:bg-primary-50
                                   hover:border-primary-200 transition">

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
                                             group-hover:text-primary-600 transition-colors">

                                        {{ $category['name'] }}
                                    </span>

                                    <span class="block text-[9px] text-slate-400 mt-0.5">
                                        Découvrir
                                    </span>

                                </span>

                                <svg class="w-3.5 h-3.5 ml-auto
                                        text-slate-300
                                        group-hover:text-primary-600
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
                   bg-white border-t border-slate-200 shadow-xl">

                <div class="max-w-[1440px] mx-auto px-4 py-4">

                    <div class="grid grid-cols-2 gap-2">

                        <a href="{{ route('buyer.home') }}"
                            class="px-4 py-3 rounded-xl bg-primary-50
                               text-primary-600 text-sm font-bold">
                            Accueil
                        </a>

                        <a href="{{ route('buyer.home') }}#produits"
                            class="px-4 py-3 rounded-xl bg-slate-50
                               text-slate-700 text-sm font-bold">
                            Boutiques
                        </a>

                        <a href="{{ route('buyer.home') }}#produits"
                            class="px-4 py-3 rounded-xl bg-accent-50
                               text-accent-600 text-sm font-bold">
                            Promotions
                        </a>

                        <a href="{{ route('tutorials.index') }}"
                            class="px-4 py-3 rounded-xl bg-primary-50
                               text-primary-600 text-sm font-bold">
                            Comment ça marche ?
                        </a>

                        <a href="{{ route('buyer.home') }}#agences"
                            class="px-4 py-3 rounded-xl bg-slate-50
                               text-slate-700 text-sm font-bold">
                            Nos agences
                        </a>

                        {{-- À PROPOS --}}
                        <a href="{{ route('about') }}"
                            class="px-4 py-3 rounded-xl bg-slate-50
                               text-slate-700 text-sm font-bold">
                            À propos
                        </a>

                        <a href="{{ route('help') }}"
                            class="px-4 py-3 rounded-xl bg-slate-50
                               text-slate-700 text-sm font-bold">
                            Aide
                        </a>


                        @auth

                            <a href="{{ $dashboardRoute }}"
                                class="px-4 py-3 rounded-xl bg-primary-600
                                   text-white text-sm font-bold">
                                Mon espace
                            </a>

                            @if (auth()->user()->isBuyer())
                                <a href="{{ route('buyer.wishlist.index') }}"
                                    class="px-4 py-3 rounded-xl bg-accent-50
                                       text-accent-600 text-sm font-bold">
                                    Mes favoris
                                </a>

                                <a href="{{ route('buyer.cart.index') }}"
                                    class="px-4 py-3 rounded-xl bg-primary-50
                                       text-primary-600 text-sm font-bold">
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
                                       bg-danger-50 text-danger
                                       text-sm font-bold hover:bg-danger hover:text-white transition">

                                    Déconnexion
                                </button>
                            </form>

                        @endauth


                        @guest

                            <a href="{{ route('login.show') }}"
                                class="px-4 py-3 rounded-xl border
                                   border-slate-200 text-slate-700
                                   text-sm font-bold hover:border-primary-500">

                                Connexion
                            </a>

                            <a href="{{ route('register.show') }}"
                                class="px-4 py-3 rounded-xl bg-primary-600
                                   text-white text-sm font-bold hover:bg-primary-700 transition">

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
                    border border-success-200 bg-success-50 p-4
                    text-sm text-success-800 shadow-sm mb-4">

                <svg class="h-5 w-5 shrink-0 text-success" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>

                <span>{{ session('success') }}</span>
            </div>
        @endif


        @if (session('fail') || session('error'))
            <div
                class="flex items-center gap-3 rounded-2xl
                    border border-danger-200 bg-danger-50 p-4
                    text-sm text-danger-800 shadow-sm mb-4">

                <svg class="h-5 w-5 shrink-0 text-danger" fill="none" viewBox="0 0 24 24"
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

    <footer class="bg-primary-900 text-white border-t border-primary-800 mt-16">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- COLONNE 1 --}}
                <div class="space-y-4">

                    <a href="{{ route('buyer.home') }}" class="inline-block">

                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer" width="155" height="68"
                            class="w-[150px] h-auto object-contain brightness-0 invert">

                    </a>

                    <p class="text-sm leading-relaxed text-primary-100/80">
                        La marketplace construite au Cameroun pour l'Afrique.
                        Achetez et vendez sans stress, en toute confiance.
                    </p>

                    <div class="flex flex-wrap gap-2">

                        <span
                            class="inline-flex items-center gap-1.5
                                 bg-white/10 text-primary-100
                                 text-xs font-semibold px-3 py-1.5
                                 rounded-full border border-white/10">

                            🛡️ Transactions sécurisées
                        </span>

                        <span
                            class="inline-flex items-center gap-1.5
                                 bg-white/10 text-accent-500
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
                                class="text-primary-100/80 hover:text-accent-500 transition">
                                Accueil
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('tutorials.index') }}"
                                class="text-primary-100/80 hover:text-accent-500 transition">
                                Tutoriels & Guides
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('about') }}"
                                class="text-primary-100/80 hover:text-accent-500 transition">
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
                            <a href="{{ route('help') }}" class="text-primary-100/80 hover:text-accent-500 transition">
                                Centre d'aide & FAQ
                            </a>
                        </li>

                        <li>
                            <a href="#" class="text-primary-100/80 hover:text-accent-500 transition">
                                Comment fonctionne l'Escrow ?
                            </a>
                        </li>

                        <li>
                            <a href="#" class="text-primary-100/80 hover:text-accent-500 transition">
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

                    <p class="text-xs text-primary-100/70 mb-4 leading-relaxed">
                        Payez facilement avec vos moyens de paiement locaux.
                    </p>

                    <div class="flex flex-wrap gap-2 text-xs font-bold">

                        <span
                            class="bg-accent-500 text-slate-900
                                 px-3 py-1.5 rounded-lg font-extrabold shadow-xs">
                            MTN MoMo
                        </span>

                        <span class="bg-accent-600 text-white
                                 px-3 py-1.5 rounded-lg font-bold shadow-xs">
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

                <div class="text-primary-100/70 text-center sm:text-left">
                    &copy; {{ date('Y') }} Ali-Kamer. Tous droits réservés.
                </div>

                <div class="flex flex-wrap justify-center gap-5">

                    <a href="#" class="text-primary-100/70 hover:text-white transition">
                        Conditions Générales
                    </a>

                    <a href="#" class="text-primary-100/70 hover:text-white transition">
                        Politique de Confidentialité
                    </a>

                    <span class="text-accent-500 font-semibold">
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
