```blade
{{-- resources/views/seller/layout.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Espace vendeur - Ali-Kamer')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

</head>


<body class="bg-slate-50 text-slate-900 antialiased">

<div
    x-data="{ mobileSidebarOpen: false }"
    class="min-h-screen bg-slate-50"
>


    {{-- =========================================================
         MOBILE OVERLAY
    ========================================================== --}}

    <div
        x-show="mobileSidebarOpen"
        x-transition.opacity
        @click="mobileSidebarOpen = false"
        class="fixed inset-0 z-40
               bg-slate-950/60
               backdrop-blur-sm
               lg:hidden"
        style="display: none;"
    ></div>



    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    <aside
        class="fixed inset-y-0 left-0 z-50
               w-[245px]
               bg-gradient-to-b from-blue-950 via-blue-950 to-slate-950
               border-r border-blue-900/40
               flex flex-col
               transform transition-transform duration-300
               lg:translate-x-0"
        :class="mobileSidebarOpen
            ? 'translate-x-0'
            : '-translate-x-full lg:translate-x-0'"
    >


        {{-- =====================================================
             LOGO
        ====================================================== --}}

        <div
            class="h-[72px]
                   px-5
                   flex items-center
                   border-b border-white/10
                   flex-shrink-0"
        >

            <a
                href="{{ route('buyer.home') }}"
                class="flex items-center gap-3 group"
            >

                <div
                    class="w-9 h-9
                           rounded-xl
                           bg-gradient-to-br
                           from-blue-500
                           to-emerald-500
                           flex items-center justify-center
                           shadow-lg
                           shadow-blue-900/30
                           group-hover:scale-105
                           transition"
                >

                    <span
                        class="text-white
                               font-black
                               text-lg"
                    >
                        A
                    </span>

                </div>


                <div>

                    <p
                        class="text-[17px]
                               font-black
                               text-white
                               leading-none"
                    >
                        Ali-<span class="text-emerald-400">Kamer</span>
                    </p>


                    <p
                        class="text-[9px]
                               uppercase
                               tracking-[0.18em]
                               text-blue-300/50
                               mt-1"
                    >
                        Espace vendeur
                    </p>

                </div>

            </a>


            {{-- Fermeture mobile --}}

            <button
                type="button"
                @click="mobileSidebarOpen = false"
                class="ml-auto
                       lg:hidden
                       w-8 h-8
                       rounded-lg
                       text-blue-200/60
                       hover:text-white
                       hover:bg-white/10
                       flex items-center justify-center
                       transition"
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



        {{-- =====================================================
             SHOP PROFILE
        ====================================================== --}}

        <div class="px-3 pt-4">

            <div
                class="rounded-xl
                       bg-white/[0.05]
                       border border-white/[0.08]
                       p-3"
            >

                <div class="flex items-center gap-3">

                    <div
                        class="w-10 h-10
                               rounded-xl
                               bg-gradient-to-br
                               from-blue-500
                               to-emerald-500
                               flex items-center justify-center
                               text-white
                               font-black
                               flex-shrink-0"
                    >

                        {{ strtoupper(substr($shop->name ?? auth()->user()->name ?? 'V', 0, 1)) }}

                    </div>


                    <div class="min-w-0">

                        <p
                            class="text-xs
                                   font-bold
                                   text-white
                                   truncate"
                        >
                            {{ $shop->name ?? 'Ma boutique' }}
                        </p>


                        <div
                            class="flex items-center gap-1.5
                                   mt-1"
                        >

                            <span
                                class="w-1.5 h-1.5
                                       rounded-full
                                       bg-emerald-400
                                       animate-pulse"
                            ></span>


                            <span
                                class="text-[10px]
                                       text-blue-200/50"
                            >
                                Boutique active
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
             NAVIGATION
        ====================================================== --}}

        <nav
            class="flex-1
                   overflow-y-auto
                   px-3
                   py-5
                   space-y-5"
        >


            {{-- =================================================
                 PRINCIPAL
            ================================================== --}}

            <div>

                <p
                    class="px-3
                           mb-2
                           text-[9px]
                           font-black
                           uppercase
                           tracking-[0.18em]
                           text-blue-300/30"
                >
                    Principal
                </p>


                {{-- Dashboard --}}

                <a
                    href="{{ route('seller.dashboard') }}"
                    class="group
                           flex items-center gap-3
                           px-3 py-2.5
                           rounded-xl
                           transition-all duration-200
                           {{ request()->routeIs('seller.dashboard')
                                ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40'
                                : 'text-blue-100/60 hover:text-white hover:bg-blue-500/10' }}"
                >

                    <span
                        class="w-8 h-8
                               rounded-lg
                               flex items-center justify-center
                               {{ request()->routeIs('seller.dashboard')
                                    ? 'bg-white/15'
                                    : 'bg-blue-500/10 group-hover:bg-blue-500/20' }}"
                    >

                        <svg
                            class="w-[17px] h-[17px]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"
                            />

                        </svg>

                    </span>


                    <span class="text-xs font-bold">
                        Tableau de bord
                    </span>

                </a>

            </div>



            {{-- =================================================
                 ACTIVITÉ
            ================================================== --}}

            <div>

                <p
                    class="px-3
                           mb-2
                           text-[9px]
                           font-black
                           uppercase
                           tracking-[0.18em]
                           text-blue-300/30"
                >
                    Activité
                </p>


                {{-- Produits --}}

                <a
                    href="{{ route('seller.products.index') }}"
                    class="group
                           flex items-center gap-3
                           px-3 py-2.5
                           rounded-xl
                           transition-all duration-200
                           {{ request()->routeIs('seller.products.*')
                                ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40'
                                : 'text-blue-100/60 hover:text-white hover:bg-blue-500/10' }}"
                >

                    <span
                        class="w-8 h-8
                               rounded-lg
                               flex items-center justify-center
                               {{ request()->routeIs('seller.products.*')
                                    ? 'bg-white/15'
                                    : 'bg-blue-500/10 group-hover:bg-blue-500/20' }}"
                    >

                        <svg
                            class="w-[17px] h-[17px]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                            />

                        </svg>

                    </span>


                    <span class="text-xs font-bold">
                        Mes produits
                    </span>

                </a>


                {{-- Commandes --}}

                <a
                    href="{{ route('seller.orders.index') }}"
                    class="group
                           flex items-center gap-3
                           px-3 py-2.5 mt-1
                           rounded-xl
                           transition-all duration-200
                           {{ request()->routeIs('seller.orders.*')
                                ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40'
                                : 'text-blue-100/60 hover:text-white hover:bg-blue-500/10' }}"
                >

                    <span
                        class="w-8 h-8
                               rounded-lg
                               flex items-center justify-center
                               {{ request()->routeIs('seller.orders.*')
                                    ? 'bg-white/15'
                                    : 'bg-blue-500/10 group-hover:bg-blue-500/20' }}"
                    >

                        <svg
                            class="w-[17px] h-[17px]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                            />

                        </svg>

                    </span>


                    <span class="text-xs font-bold">
                        Mes commandes
                    </span>

                </a>

            </div>



            {{-- =================================================
                 FINANCES
            ================================================== --}}

            <div>

                <p
                    class="px-3
                           mb-2
                           text-[9px]
                           font-black
                           uppercase
                           tracking-[0.18em]
                           text-blue-300/30"
                >
                    Finances
                </p>


                {{-- Portefeuille --}}

                <a
                    href="{{ route('seller.wallet.index') }}"
                    class="group
                           flex items-center gap-3
                           px-3 py-2.5
                           rounded-xl
                           transition-all duration-200
                           {{ request()->routeIs('seller.wallet.*')
                                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-950/40'
                                : 'text-blue-100/60 hover:text-white hover:bg-emerald-500/10' }}"
                >

                    <span
                        class="w-8 h-8
                               rounded-lg
                               flex items-center justify-center
                               {{ request()->routeIs('seller.wallet.*')
                                    ? 'bg-white/15'
                                    : 'bg-emerald-500/10 group-hover:bg-emerald-500/20' }}"
                    >

                        <svg
                            class="w-[17px] h-[17px]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z"
                            />

                        </svg>

                    </span>


                    <span class="text-xs font-bold">
                        Mon portefeuille
                    </span>

                </a>

            </div>



            {{-- =================================================
                 BOUTIQUE
            ================================================== --}}

            <div>

                <p
                    class="px-3
                           mb-2
                           text-[9px]
                           font-black
                           uppercase
                           tracking-[0.18em]
                           text-blue-300/30"
                >
                    Boutique
                </p>


                {{-- Paramètres --}}

                <a
                    href="{{ route('seller.shop.edit') }}"
                    class="group
                           flex items-center gap-3
                           px-3 py-2.5
                           rounded-xl
                           transition-all duration-200
                           {{ request()->routeIs('seller.shop.*')
                                ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40'
                                : 'text-blue-100/60 hover:text-white hover:bg-blue-500/10' }}"
                >

                    <span
                        class="w-8 h-8
                               rounded-lg
                               flex items-center justify-center
                               {{ request()->routeIs('seller.shop.*')
                                    ? 'bg-white/15'
                                    : 'bg-blue-500/10 group-hover:bg-blue-500/20' }}"
                    >

                        <svg
                            class="w-[17px] h-[17px]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />

                        </svg>

                    </span>


                    <span class="text-xs font-bold">
                        Paramètres
                    </span>

                </a>

            </div>

        </nav>



        {{-- =====================================================
             BOTTOM ACTIONS
        ====================================================== --}}

        <div
            class="p-3
                   border-t
                   border-white/10
                   flex-shrink-0"
        >


            {{-- Voir la boutique --}}

            <a
                href="{{ route('buyer.home') }}"
                class="group
                       flex items-center gap-3
                       px-3 py-2.5
                       rounded-xl
                       text-blue-100/60
                       hover:text-white
                       hover:bg-blue-500/10
                       transition"
            >

                <span
                    class="w-8 h-8
                           rounded-lg
                           bg-blue-500/10
                           group-hover:bg-blue-500/20
                           flex items-center justify-center"
                >

                    <svg
                        class="w-[16px] h-[16px]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"
                        />

                    </svg>

                </span>


                <span class="text-xs font-bold">
                    Voir la boutique
                </span>

            </a>


            {{-- Déconnexion --}}

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="mt-1"
            >

                @csrf

                <button
                    type="submit"
                    class="group
                           w-full
                           flex items-center gap-3
                           px-3 py-2.5
                           rounded-xl
                           text-blue-100/40
                           hover:text-red-400
                           hover:bg-red-500/10
                           transition"
                >

                    <span
                        class="w-8 h-8
                               rounded-lg
                               bg-blue-500/10
                               group-hover:bg-red-500/10
                               flex items-center justify-center"
                    >

                        <svg
                            class="w-[16px] h-[16px]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"
                            />

                        </svg>

                    </span>


                    <span class="text-xs font-bold">
                        Déconnexion
                    </span>

                </button>

            </form>

        </div>

    </aside>



    {{-- =========================================================
         MAIN AREA
    ========================================================== --}}

    <div class="lg:ml-[245px] min-h-screen">


        {{-- =====================================================
             TOPBAR
        ====================================================== --}}

        <header
            class="sticky top-0 z-30
                   h-[64px]
                   bg-white/95
                   backdrop-blur-xl
                   border-b border-slate-200"
        >

            <div
                class="h-full
                       px-4 sm:px-6
                       flex items-center
                       justify-between"
            >


                {{-- Partie gauche --}}

                <div class="flex items-center gap-3">


                    {{-- Menu mobile --}}

                    <button
                        type="button"
                        @click="mobileSidebarOpen = true"
                        class="lg:hidden
                               w-9 h-9
                               rounded-xl
                               bg-blue-50
                               text-blue-600
                               flex items-center justify-center
                               hover:bg-blue-100
                               transition"
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
                                d="M4 6h16M4 12h16M4 18h16"
                            />

                        </svg>

                    </button>


                    <div>

                        <p
                            class="text-[10px]
                                   font-bold
                                   uppercase
                                   tracking-wider
                                   text-slate-400"
                        >
                            Espace vendeur
                        </p>


                        <h2
                            class="text-sm
                                   font-black
                                   text-slate-800"
                        >
                            @yield('page-title', 'Tableau de bord')
                        </h2>

                    </div>

                </div>



                {{-- Partie droite --}}

                <div class="flex items-center gap-3">


                    {{-- Statut boutique --}}

                    <div
                        class="hidden sm:flex
                               items-center gap-2
                               px-3 py-1.5
                               rounded-lg
                               bg-emerald-50
                               border border-emerald-100"
                    >

                        <span
                            class="w-1.5 h-1.5
                                   rounded-full
                                   bg-emerald-500
                                   animate-pulse"
                        ></span>


                        <span
                            class="text-[10px]
                                   font-bold
                                   text-emerald-700"
                        >
                            Boutique active
                        </span>

                    </div>


                    {{-- Avatar vendeur --}}

                    <div
                        class="w-9 h-9
                               rounded-full
                               bg-gradient-to-br
                               from-blue-600
                               to-emerald-500
                               flex items-center justify-center
                               text-white
                               text-xs
                               font-black
                               shadow-sm"
                    >

                        {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}

                    </div>

                </div>

            </div>

        </header>



        {{-- =====================================================
             PAGE CONTENT
        ====================================================== --}}

        <main>

            @yield('seller-content')

        </main>

    </div>

</div>


@stack('scripts')

</body>

</html>

