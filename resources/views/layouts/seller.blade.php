<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Espace vendeur') — {{ config('app.name', 'Ali-Kamer') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /*
        |--------------------------------------------------------------------------
        | SIDEBAR VENDEUR
        |--------------------------------------------------------------------------
        | Même densité que la sidebar Admin.
        | Le scroll reste uniquement un filet de sécurité sur les très petits
        | écrans. Sur une hauteur desktop normale, tout est visible.
        */

        [x-cloak] { display: none !important; }

        .seller-sidebar-nav::-webkit-scrollbar {
            display: none;
        }

        @media (min-width: 768px) and (min-height: 680px) {
            .seller-sidebar-nav {
                overflow-y: hidden;
            }
        }
    </style>

    @stack('styles')
</head>


<body
    class="h-full font-sans antialiased text-slate-800 bg-slate-50"
    x-data="{ sidebarOpen: false }"
    @keydown.escape.window="sidebarOpen = false"
>

<div class="flex h-screen overflow-hidden">


    {{-- =========================================================
         OVERLAY MOBILE
    ========================================================== --}}

    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/80 z-40 md:hidden"
    ></div>


    {{-- =========================================================
         1. SIDEBAR VENDEUR
    ========================================================== --}}

    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a1b12] text-white flex flex-col transition-transform duration-300 ease-in-out md:static md:translate-x-0 border-r border-slate-800 shrink-0"
    >

        <div class="flex flex-col h-full min-h-0">


            {{-- =================================================
                 LOGO
            ================================================== --}}

            <div
                class="px-4 py-3 border-b border-white/10 flex items-center justify-between shrink-0 bg-black/20"
            >

                <div class="flex items-center gap-3">

                    <div
                        class="w-10 h-10 bg-white rounded-xl shadow-md p-1 flex items-center justify-center shrink-0"
                    >
                        <img
                            src="{{ asset('images/afrique.png') }}"
                            alt="Ali-Kamer Logo"
                            class="h-full w-auto object-contain"
                        >
                    </div>

                    <div class="leading-tight">

                        <h2 class="font-black text-sm tracking-wide text-white uppercase">
                            Ali-Kamer
                        </h2>

                        <span
                            class="inline-block mt-1 text-[9px] font-bold text-[#F9A01B] bg-[#F9A01B]/10 px-2 py-0.5 rounded border border-[#F9A01B]/30"
                        >
                            Espace vendeur
                        </span>

                    </div>

                </div>


                {{-- Fermeture mobile --}}

                <button
                    @click="sidebarOpen = false"
                    class="md:hidden text-slate-400 hover:text-white p-1"
                    type="button"
                >

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>


            {{-- =================================================
                 NAVIGATION
            ================================================== --}}

            <nav
                class="seller-sidebar-nav flex-1 px-3 py-2 overflow-y-auto text-xs"
            >


                {{-- =============================================
                     VUE GLOBALE
                ============================================== --}}

                <div class="px-3 pt-1 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                    Vue globale
                </div>


                {{-- Tableau de bord --}}

                <a
                    href="{{ route('seller.dashboard') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-bold transition-all
                    {{ request()->routeIs('seller.dashboard')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40 ring-1 ring-emerald-400/30'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0 text-[#F9A01B]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                        />
                    </svg>

                    <span>Tableau de bord</span>

                </a>


                {{-- =============================================
                     COMMERCE
                ============================================== --}}

                <div class="px-3 pt-3 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                    Commerce
                </div>


                {{-- Mes produits --}}

                <a
                    href="{{ route('seller.products.index') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('seller.products.index')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"
                        />
                    </svg>

                    <span>Mes produits</span>

                </a>


                {{-- Ajouter un produit --}}

                <a
                    href="{{ route('seller.products.create') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('seller.products.create')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0 text-[#F9A01B]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>

                    <span>Ajouter un produit</span>

                </a>


                {{-- Mes commandes --}}

                <a
                    href="{{ route('seller.orders.index') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('seller.orders.*')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 7h18M5 7l1 13h12l1-13M9 7V5a3 3 0 016 0v2"
                        />
                    </svg>

                    <span>Mes commandes</span>

                </a>


                {{-- =============================================
                     FINANCES
                ============================================== --}}

                <div class="px-3 pt-3 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                    Finances
                </div>


                {{-- Portefeuille --}}

                <a
                    href="{{ route('seller.wallet.index') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('seller.wallet.index')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 7h18v12H3V7zm0 0V5a2 2 0 012-2h14a2 2 0 012 2v2M16 13h2"
                        />
                    </svg>

                    <span>Portefeuille</span>

                </a>


                {{-- Retirer des fonds --}}

                <a
                    href="{{ route('seller.wallet.withdraw') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('seller.wallet.withdraw')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0 text-[#F9A01B]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 3v14m0 0l-5-5m5 5l5-5M5 21h14"
                        />
                    </svg>

                    <span>Retirer des fonds</span>

                </a>


                {{-- Historique --}}

                {{-- <a
                    href="{{ route('seller.wallet.history') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('seller.wallet.history')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                    <span>Historique</span>

                </a> --}}


                {{-- =============================================
                     MA BOUTIQUE
                ============================================== --}}

                <div class="px-3 pt-3 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                    Ma boutique
                </div>


                {{-- Configuration boutique --}}

                <a
                    href="{{ route('seller.shop.edit') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('seller.shop.edit')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 8a4 4 0 100 8 4 4 0 000-8zm8 4a7.9 7.9 0 00-.2-1.8l2-1.5-2-3.4-2.4 1a8 8 0 00-3.1-1.8L14 2h-4l-.3 2.5A8 8 0 006.6 6L4.2 5 2.2 8.4l2 1.5A7.9 7.9 0 004 12c0 .6.1 1.2.2 1.8l-2 1.5 2 3.4 2.4-1a8 8 0 003.1 1.8L10 22h4l.3-2.5a8 8 0 003.1-1.8l2.4 1 2-3.4-2-1.5c.1-.6.2-1.2.2-1.8z"
                        />
                    </svg>

                    <span>Configuration boutique</span>

                </a>


                {{-- =============================================
                     RELATION CLIENT
                ============================================== --}}

                <div class="px-3 pt-3 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                    Relation client
                </div>


                {{-- Messages --}}

                <a
                    href="{{ route('messaging.index') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('messaging.*')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M21 11.5a8.4 8.4 0 01-9 8.4 9.6 9.6 0 01-4.1-.9L3 21l1.7-4.2A8.3 8.3 0 013 11.5a8.5 8.5 0 1118 0z"
                        />
                    </svg>

                    <span>Messages</span>

                </a>


                {{-- Litiges --}}

                <a
                    href="{{ route('seller.disputes.index') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('seller.disputes.*')
                        ? 'bg-[#E30613] text-white shadow-lg shadow-red-600/30'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z"
                        />
                    </svg>

                    <span>Litiges</span>

                </a>


                {{-- =============================================
                     RESSOURCES
                ============================================== --}}

                <div class="px-3 pt-3 pb-1 text-[9px] font-black uppercase tracking-wider text-emerald-500/80">
                    Ressources
                </div>


                {{-- Tutoriels --}}

                <a
                    href="{{ route('tutorials.index') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('tutorials.*')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
                        />
                    </svg>

                    <span>Tutoriels vidéo</span>

                </a>


                {{-- Centre d'aide --}}

                <a
                    href="{{ route('help') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold transition-all
                    {{ request()->routeIs('help')
                        ? 'bg-[#016837] text-white shadow-lg shadow-[#016837]/40'
                        : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                >

                    <svg
                        class="w-4 h-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M8.2 9a4 4 0 117.6 2c-.9.9-1.8 1.2-2.3 2.2-.2.4-.3.8-.3 1.3M12 18h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                    <span>Centre d'aide</span>

                </a>

            </nav>


            {{-- =================================================
                 PIED DE SIDEBAR
            ================================================== --}}

            <div
                class="p-3 border-t border-white/10 bg-black/20 shrink-0"
            >

                {{-- Retour Marketplace --}}

                <a
                    href="{{ route('buyer.home') }}"
                    class="flex items-center justify-center gap-2 w-full px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-200 text-xs font-bold transition-all border border-white/10 group"
                >

                    <svg
                        class="w-4 h-4 text-[#F9A01B] group-hover:-translate-x-1 transition-transform"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>

                    <span>Retour à la Marketplace</span>

                </a>


                {{-- Déconnexion --}}

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="mt-1"
                >
                    @csrf

                    <button
                        type="submit"
                        class="flex items-center justify-center gap-2 w-full px-3 py-2 rounded-xl text-red-300 hover:text-white hover:bg-[#E30613] transition-all text-xs font-bold"
                    >

                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>

                        <span>Déconnexion</span>

                    </button>

                </form>

            </div>

        </div>

    </aside>


    {{-- =========================================================
         2. CONTENU PRINCIPAL
    ========================================================== --}}

    <div class="flex-1 flex flex-col h-screen overflow-y-auto">


        {{-- =====================================================
             TOPBAR
        ====================================================== --}}

        <header
            class="bg-white border-b border-slate-200/80 px-4 sm:px-6 py-3.5 sticky top-0 z-30 flex items-center justify-between shadow-xs"
        >

            <div class="flex items-center gap-3">


                {{-- Hamburger mobile --}}

                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none border border-slate-200"
                    type="button"
                >

                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>

                </button>


                <div class="flex items-center gap-2">

                    <span
                        class="w-2.5 h-2.5 rounded-full bg-[#016837] animate-pulse"
                    ></span>

                    <span
                        class="text-xs font-black text-slate-700 uppercase tracking-wider"
                    >
                        Espace vendeur
                    </span>

                </div>

            </div>


            {{-- Profil --}}

            <div class="flex items-center gap-3">

                <div class="flex items-center gap-3 pl-3 border-l border-slate-200">

                    <div
                        class="w-9 h-9 rounded-xl bg-[#016837] text-[#F9A01B] flex items-center justify-center font-black text-xs shadow-md"
                    >
                        {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}
                    </div>

                    <div class="hidden sm:block text-left leading-tight">

                        <p class="text-xs font-bold text-slate-800">
                            {{ auth()->user()->name ?? 'Vendeur' }}
                        </p>

                        <p class="text-[10px] text-slate-400">
                            {{ auth()->user()->email ?? '' }}
                        </p>

                    </div>

                </div>


                {{-- Déconnexion rapide --}}

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="inline"
                >

                    @csrf

                    <button
                        type="submit"
                        title="Déconnexion"
                        class="p-2 rounded-xl text-red-400 hover:text-[#E30613] hover:bg-red-50 transition"
                    >

                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>

                    </button>

                </form>

            </div>

        </header>


        {{-- =====================================================
             CONTENU
        ====================================================== --}}

        <main
            class="flex-1 p-4 sm:p-6 max-w-[1600px] w-full mx-auto"
        >

            @if(session('success'))

                <div
                    class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"
                >
                    {{ session('success') }}
                </div>

            @endif


            @if(session('error'))

                <div
                    class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                >
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