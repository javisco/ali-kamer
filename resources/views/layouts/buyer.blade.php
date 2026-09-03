<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#F7F9F7]">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ali-Kamer — Espace Acheteur')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Scrollbar très discrète — uniquement en cas de très petit écran */
        .sidebar-scroll::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .15);
            border-radius: 10px;
        }

        /* Animation légère */
        @keyframes sidebarFade {
            from {
                opacity: 0;
                transform: translateX(-5px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar-item {
            animation: sidebarFade .25s ease-out;
        }
    </style>

    @stack('styles')
</head>

<body class="h-full text-slate-800 antialiased">

<div
    x-data="{ sidebarOpen: false }"
    class="min-h-screen"
>

    {{-- =========================================================
        OVERLAY MOBILE
    ========================================================== --}}
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-[2px] md:hidden"
    ></div>


    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 flex w-[258px] flex-col
               bg-[#0A1B12] text-white shadow-2xl
               transform transition-transform duration-300 ease-in-out
               md:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >

        {{-- =====================================================
            LOGO / HEADER
        ====================================================== --}}
        <div class="flex h-[62px] shrink-0 items-center border-b border-white/10 px-4">

            <a href="{{ route('buyer.home') }}"
               class="flex items-center gap-3 min-w-0">

                <div class="relative flex h-9 w-9 shrink-0 items-center justify-center
                            overflow-hidden rounded-xl bg-white shadow-sm">

                    <img
                        src="{{ asset('images/afrique.png') }}"
                        alt="Ali-Kamer"
                        class="h-8 w-8 object-contain"
                    >
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="text-[15px] font-extrabold tracking-tight">
                            ALI-KAMER
                        </span>

                        <span class="h-1.5 w-1.5 rounded-full bg-[#F9A01B]"></span>
                    </div>

                    <p class="mt-0.5 text-[9px] font-medium uppercase tracking-[0.16em] text-white/45">
                        Espace acheteur
                    </p>
                </div>
            </a>

            {{-- Fermeture mobile --}}
            <button
                @click="sidebarOpen = false"
                class="ml-auto flex h-7 w-7 items-center justify-center rounded-lg
                       text-white/50 hover:bg-white/10 hover:text-white md:hidden"
                aria-label="Fermer le menu"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>


        {{-- =====================================================
            PROFIL ACHETEUR
        ====================================================== --}}
        <div class="shrink-0 px-3 pt-3 pb-2">

            <div class="rounded-xl border border-white/10 bg-white/[0.045] p-2.5">

                <div class="flex items-center gap-2.5">

                    {{-- Avatar --}}
                    <div class="relative flex h-9 w-9 shrink-0 items-center justify-center
                                rounded-full bg-[#016837] text-[12px] font-bold text-white
                                ring-2 ring-[#016837]/30">

                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}

                        <span class="absolute -right-0.5 -bottom-0.5 h-2.5 w-2.5
                                     rounded-full border-2 border-[#0A1B12] bg-emerald-400">
                        </span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-[11.5px] font-semibold text-white">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="truncate text-[9px] text-white/40">
                            Acheteur
                        </p>
                    </div>

                    <span class="shrink-0 rounded-md bg-[#F9A01B]/10 px-1.5 py-1
                                 text-[8px] font-bold uppercase tracking-wide text-[#F9A01B]">
                        Client
                    </span>
                </div>
            </div>
        </div>


        {{-- =====================================================
            MENU
        ====================================================== --}}
        <nav class="sidebar-scroll flex-1 overflow-y-auto px-3 pb-2">

            {{-- VUE GLOBALE --}}
            <div class="mb-2">

                <p class="mb-1.5 px-2 text-[8px] font-bold uppercase tracking-[0.16em] text-white/30">
                    Vue globale
                </p>

                <a href="{{ route('buyer.dashboard') }}"
                   @click="sidebarOpen = false"
                   class="sidebar-item group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5
                          text-[11.5px] font-medium transition
                          {{ request()->routeIs('buyer.home')
                              ? 'bg-[#016837] text-white shadow-sm'
                              : 'text-white/65 hover:bg-white/[0.06] hover:text-white' }}">

                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md
                                 {{ request()->routeIs('buyer.dashboard')
                                     ? 'bg-white/10'
                                     : 'bg-white/[0.04] group-hover:bg-white/10' }}">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"/>
                        </svg>
                    </span>

                    <span class="flex-1">Tableau de bord</span>

                    @if(request()->routeIs('buyer.home'))
                        <span class="h-1.5 w-1.5 rounded-full bg-[#F9A01B]"></span>
                    @endif
                </a>
            </div>


            {{-- MES ACHATS --}}
            <div class="mb-2">

                <p class="mb-1.5 px-2 text-[8px] font-bold uppercase tracking-[0.16em] text-white/30">
                    Mes achats
                </p>

                <a href="{{ route('buyer.orders.index') }}"
                   @click="sidebarOpen = false"
                   class="sidebar-item group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5
                          text-[11.5px] font-medium transition
                          {{ request()->routeIs('buyer.orders.*')
                              ? 'bg-[#016837] text-white shadow-sm'
                              : 'text-white/65 hover:bg-white/[0.06] hover:text-white' }}">

                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md
                                 {{ request()->routeIs('buyer.orders.*')
                                     ? 'bg-white/10'
                                     : 'bg-white/[0.04] group-hover:bg-white/10' }}">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 7h18M5 7l1.5 12h11L19 7M9 7V5a3 3 0 016 0v2"/>
                        </svg>
                    </span>

                    <span class="flex-1">Mes commandes</span>

                    @if(isset($stats['active_orders']) && $stats['active_orders'] > 0)
                        <span class="min-w-[18px] rounded-full bg-[#F9A01B] px-1.5 py-0.5
                                     text-center text-[8px] font-bold text-[#0A1B12]">
                            {{ $stats['active_orders'] }}
                        </span>
                    @endif
                </a>
            </div>


            {{-- FINANCES --}}
            <div class="mb-2">

                <p class="mb-1.5 px-2 text-[8px] font-bold uppercase tracking-[0.16em] text-white/30">
                    Finances
                </p>

                <a href="{{ route('buyer.wallet.history') }}"
                   @click="sidebarOpen = false"
                   class="sidebar-item group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5
                          text-[11.5px] font-medium transition
                          {{ request()->routeIs('buyer.wallet.*')
                              ? 'bg-[#016837] text-white shadow-sm'
                              : 'text-white/65 hover:bg-white/[0.06] hover:text-white' }}">

                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md
                                 {{ request()->routeIs('buyer.wallet.*')
                                     ? 'bg-white/10'
                                     : 'bg-white/[0.04] group-hover:bg-white/10' }}">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 7h18v12H3zM3 10h18M16 15h2"/>
                        </svg>
                    </span>

                    <span class="flex-1">Historique des dépenses</span>
                </a>
            </div>


            {{-- MON COMPTE --}}
            <div class="mb-2">

                <p class="mb-1.5 px-2 text-[8px] font-bold uppercase tracking-[0.16em] text-white/30">
                    Mon compte
                </p>

                <a href="{{ route('buyer.profile') }}"
                   @click="sidebarOpen = false"
                   class="sidebar-item group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5
                          text-[11.5px] font-medium transition
                          {{ request()->routeIs('buyer.profile')
                              ? 'bg-[#016837] text-white shadow-sm'
                              : 'text-white/65 hover:bg-white/[0.06] hover:text-white' }}">

                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md
                                 {{ request()->routeIs('buyer.profile')
                                     ? 'bg-white/10'
                                     : 'bg-white/[0.04] group-hover:bg-white/10' }}">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M20 21a8 8 0 00-16 0M12 13a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                    </span>

                    <span class="flex-1">Mon profil</span>
                </a>
            </div>


            {{-- COMMUNICATION --}}
            <div class="mb-2">

                <p class="mb-1.5 px-2 text-[8px] font-bold uppercase tracking-[0.16em] text-white/30">
                    Communication
                </p>

                {{-- Messages --}}
                <a href="{{ route('messaging.index') }}"
                   @click="sidebarOpen = false"
                   class="sidebar-item group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5
                          text-[11.5px] font-medium transition
                          {{ request()->routeIs('messaging.*')
                              ? 'bg-[#016837] text-white shadow-sm'
                              : 'text-white/65 hover:bg-white/[0.06] hover:text-white' }}">

                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md
                                 bg-white/[0.04] group-hover:bg-white/10">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M4 5h16v11H8l-4 4V5z"/>
                        </svg>
                    </span>

                    <span class="flex-1">Messages</span>
                </a>


                {{-- Notifications --}}
                <a href="#"
                   @click="sidebarOpen = false"
                   class="sidebar-item group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5
                          text-[11.5px] font-medium transition
                          {{ request()->routeIs('notifications.*')
                              ? 'bg-[#016837] text-white shadow-sm'
                              : 'text-white/65 hover:bg-white/[0.06] hover:text-white' }}">

                    <span class="relative flex h-7 w-7 shrink-0 items-center justify-center rounded-md
                                 bg-white/[0.04] group-hover:bg-white/10">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
                        </svg>

                        {{-- Badge notifications --}}
                        <span
                            id="buyer-notification-badge"
                            class="absolute -right-1 -top-1 hidden min-w-[14px] h-[14px]
                                   items-center justify-center rounded-full
                                   bg-[#E30613] px-1 text-[7px] font-bold text-white">
                        </span>
                    </span>

                    <span class="flex-1">Notifications</span>
                </a>

            </div>


            {{-- ASSISTANCE --}}
            <div class="mb-1">

                <p class="mb-1.5 px-2 text-[8px] font-bold uppercase tracking-[0.16em] text-white/30">
                    Assistance
                </p>

                <a href="{{ route('help') }}"
                   @click="sidebarOpen = false"
                   class="sidebar-item group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5
                          text-[11.5px] font-medium text-white/65 transition
                          hover:bg-white/[0.06] hover:text-white">

                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md
                                 bg-white/[0.04] group-hover:bg-white/10">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M9.5 9a2.5 2.5 0 115 0c0 2-2.5 2-2.5 2v2M12 17h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>

                    <span class="flex-1">Centre d'aide</span>
                </a>

                <a href="{{ route('tutorials.index') }}"
                   @click="sidebarOpen = false"
                   class="sidebar-item group flex items-center gap-2.5 rounded-lg px-2.5 py-1.5
                          text-[11.5px] font-medium text-white/65 transition
                          hover:bg-white/[0.06] hover:text-white">

                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md
                                 bg-white/[0.04] group-hover:bg-white/10">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M4 5h16v14H4zM8 9h8M8 13h5"/>
                        </svg>
                    </span>

                    <span class="flex-1">Tutoriels</span>
                </a>

            </div>

        </nav>


        {{-- =====================================================
            FOOTER SIDEBAR
        ====================================================== --}}
        <div class="shrink-0 border-t border-white/10 px-3 py-2.5">

            {{-- Marketplace --}}
            <a href="/"
               class="group mb-1.5 flex items-center gap-2.5 rounded-lg px-2.5 py-1.5
                      text-[11px] font-medium text-white/60 transition
                      hover:bg-white/[0.06] hover:text-white">

                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md
                             bg-[#F9A01B]/10 text-[#F9A01B]">

                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 12h18M12 3v18"/>
                    </svg>
                </span>

                <span>Retour à la Marketplace</span>
            </a>


            {{-- Déconnexion --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="group flex w-full items-center gap-2.5 rounded-lg px-2.5 py-1.5
                           text-[11px] font-medium text-white/45 transition
                           hover:bg-[#E30613]/10 hover:text-[#ff6670]"
                >

                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md
                                 bg-white/[0.04] group-hover:bg-[#E30613]/10">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M15 17l5-5-5-5M20 12H9M13 5V4a2 2 0 00-2-2H5a2 2 0 00-2 2v16a2 2 0 002 2h6a2 2 0 002-2v-1"/>
                        </svg>
                    </span>

                    <span>Déconnexion</span>
                </button>
            </form>

        </div>

    </aside>


    {{-- =========================================================
        ZONE PRINCIPALE
    ========================================================== --}}
    <div class="min-h-screen md:pl-[258px]">

        {{-- =====================================================
            TOPBAR
        ====================================================== --}}
        <header class="sticky top-0 z-30 h-[58px] border-b border-slate-200/80
                       bg-white/95 backdrop-blur-md">

            <div class="flex h-full items-center justify-between px-4 sm:px-6">

                <div class="flex items-center gap-3">

                    {{-- Hamburger mobile --}}
                    <button
                        @click="sidebarOpen = true"
                        class="flex h-8 w-8 items-center justify-center rounded-lg
                               border border-slate-200 bg-white text-slate-600
                               shadow-sm hover:bg-slate-50 md:hidden"
                        aria-label="Ouvrir le menu"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div>
                        <div class="flex items-center gap-2">

                            <span class="h-2 w-2 rounded-full bg-[#016837]"></span>

                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#016837]">
                                Espace acheteur
                            </span>
                        </div>

                        <p class="hidden text-[9px] text-slate-400 sm:block">
                            Achetez en toute confiance sur Ali-Kamer
                        </p>
                    </div>
                </div>


                {{-- Partie droite --}}
                <div class="flex items-center gap-2">

                    {{-- Notifications rapides --}}
                    <a href="{{ route('notifications.index') }}"
                       class="relative flex h-8 w-8 items-center justify-center rounded-lg
                              text-slate-500 transition hover:bg-slate-100 hover:text-[#016837]">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
                        </svg>

                        <span
                            id="top-notification-badge"
                            class="absolute right-1 top-1 hidden h-3.5 min-w-[14px]
                                   items-center justify-center rounded-full
                                   bg-[#E30613] px-1 text-[7px] font-bold text-white">
                        </span>
                    </a>


                    {{-- Séparateur --}}
                    <div class="hidden h-6 w-px bg-slate-200 sm:block"></div>


                    {{-- Utilisateur --}}
                    <div class="flex items-center gap-2">

                        <div class="hidden text-right sm:block">
                            <p class="max-w-[130px] truncate text-[10px] font-semibold text-slate-700">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-[8px] text-slate-400">
                                Acheteur
                            </p>
                        </div>

                        <div class="flex h-8 w-8 items-center justify-center rounded-full
                                    bg-[#016837] text-[11px] font-bold text-white
                                    ring-2 ring-[#016837]/10">

                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}

                        </div>

                    </div>

                </div>

            </div>
        </header>


        {{-- =====================================================
            CONTENU
        ====================================================== --}}
        <main class="min-h-[calc(100vh-58px)] bg-[#F7F9F7]">

            @yield('content')

        </main>

    </div>

</div>

@stack('scripts')

</body>
</html>