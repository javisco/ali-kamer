@extends('base')

@section('title', 'Ali-Kamer — Acheter et vendre sans stress')

@section('content')

@php
    $categoriesList = [
        ['name' => 'Agriculture', 'slug' => 'agriculture', 'icon' => '🌿', 'color' => 'green'],
        ['name' => 'Élevage', 'slug' => 'elevage', 'icon' => '🐄', 'color' => 'amber'],
        ['name' => 'Alimentation', 'slug' => 'alimentation', 'icon' => '🧺', 'color' => 'orange'],
        ['name' => 'Téléphonie', 'slug' => 'telephonie', 'icon' => '📱', 'color' => 'green'],
        ['name' => 'Informatique', 'slug' => 'informatique', 'icon' => '💻', 'color' => 'blue'],
        ['name' => 'Maison', 'slug' => 'maison', 'icon' => '🏠', 'color' => 'green'],
        ['name' => 'Mode', 'slug' => 'mode', 'icon' => '👕', 'color' => 'rose'],
        ['name' => 'Équipements', 'slug' => 'equipements', 'icon' => '⚙️', 'color' => 'amber'],
        ['name' => 'Livres', 'slug' => 'livres', 'icon' => '📚', 'color' => 'blue'],
        ['name' => 'Autres', 'slug' => 'autres', 'icon' => '•••', 'color' => 'slate'],
    ];
@endphp

<style>
    [x-cloak] {
        display: none !important;
    }

    .ak-scrollbar::-webkit-scrollbar {
        height: 4px;
    }

    .ak-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .ak-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    .ak-hide-scrollbar {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .ak-hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    @keyframes ak-float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-5px);
        }
    }

    @keyframes ak-pulse-soft {
        0%, 100% {
            opacity: .65;
            transform: scale(1);
        }
        50% {
            opacity: 1;
            transform: scale(1.08);
        }
    }

    .ak-float {
        animation: ak-float 4s ease-in-out infinite;
    }

    .ak-pulse-soft {
        animation: ak-pulse-soft 2.5s ease-in-out infinite;
    }

    .ak-line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ak-line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<div class="bg-[#FAF9F6] text-slate-800">

    {{-- =========================================================
        1. BARRE DE CONFIANCE
    ========================================================== --}}
    <div class="bg-[#004D2A] text-white">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="min-h-[34px] flex items-center justify-center sm:justify-between gap-4">

                <div class="flex items-center gap-2 text-[10px] sm:text-[11px] font-bold">
                    <span class="w-4 h-4 rounded-full bg-[#FCD116] text-[#004D2A] flex items-center justify-center text-[9px] font-black">
                        ✓
                    </span>

                    <span>
                        Achetez et vendez sans stress
                    </span>
                </div>

                <div class="hidden sm:flex items-center gap-5 text-[10px] text-emerald-100">
                    <span>🔒 Paiement sécurisé</span>
                    <span>🚚 Livraison interurbaine</span>
                    <span>📱 Mobile Money</span>
                </div>

            </div>
        </div>
    </div>


    {{-- =========================================================
        2. HERO
    ========================================================== --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-[#F0FAF4] via-white to-[#FAF9F6] border-b border-slate-200/70">

        {{-- Décoration --}}
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-[#00843D]/5 pointer-events-none"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-[#FCD116]/10 pointer-events-none"></div>

        <div class="relative max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-7 sm:py-9 lg:py-12">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-7 items-stretch">

                {{-- HERO PRINCIPAL --}}
                <div class="lg:col-span-8 relative overflow-hidden rounded-[28px] bg-gradient-to-br from-[#004D2A] via-[#006B32] to-[#00843D] text-white shadow-xl shadow-emerald-950/10">

                    {{-- Motifs --}}
                    <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full border-[30px] border-white/5"></div>
                    <div class="absolute -right-28 bottom-[-100px] w-80 h-80 rounded-full border-[40px] border-[#FCD116]/10"></div>

                    <div class="relative z-10 p-6 sm:p-8 lg:p-10 xl:p-12">

                        <div class="max-w-2xl">

                            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/15 px-3 py-1.5 text-[10px] sm:text-[11px] font-extrabold backdrop-blur-sm">
                                <span class="w-2 h-2 rounded-full bg-[#FCD116] ak-pulse-soft"></span>
                                Marketplace camerounaise
                            </div>

                            <h1 class="mt-5 text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-[1.08]">
                                Achetez et vendez
                                <span class="text-[#FCD116]">
                                    sans stress.
                                </span>
                            </h1>

                            <p class="mt-4 text-sm sm:text-base text-emerald-50/90 leading-relaxed max-w-xl">
                                Découvrez des produits de vendeurs au Cameroun,
                                payez avec votre Mobile Money et profitez d'un
                                parcours d'achat conçu pour sécuriser chaque étape.
                            </p>

                            {{-- RECHERCHE PRINCIPALE DE L'ACCUEIL --}}
                            <form action="{{ route('buyer.home') }}"
                                  method="GET"
                                  class="mt-6 max-w-xl">

                                @if (request('category'))
                                    <input type="hidden"
                                           name="category"
                                           value="{{ request('category') }}">
                                @endif

                                <div class="flex items-center gap-2 p-1.5 rounded-2xl
                                            bg-white shadow-lg shadow-emerald-950/10
                                            border border-white/70
                                            focus-within:ring-4 focus-within:ring-[#FCD116]/20">

                                    <div class="flex-1 min-w-0 flex items-center gap-2 px-2">
                                        <svg class="w-5 h-5 shrink-0 text-[#00843D]"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1 8 8" />
                                        </svg>

                                        <input type="text"
                                               name="q"
                                               value="{{ request('q') }}"
                                               placeholder="Que recherchez-vous ?"
                                               autocomplete="off"
                                               class="w-full h-11 bg-transparent border-0 outline-none
                                                      text-sm text-slate-800
                                                      placeholder:text-slate-400">
                                    </div>

                                    <button type="submit"
                                            class="shrink-0 h-11 px-5 sm:px-6 rounded-xl
                                                   bg-[#00843D] hover:bg-[#006B32]
                                                   text-white text-xs sm:text-sm font-black
                                                   transition-all duration-200
                                                   hover:-translate-y-0.5
                                                   flex items-center gap-2">

                                        <span>Rechercher</span>

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1 8 8" />
                                        </svg>

                                    </button>
                                </div>
                            </form>

                            {{-- CTA --}}
                            <div class="mt-5 flex flex-wrap gap-3">

                                <a href="#produits"
                                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-white text-[#006B32] px-5 sm:px-6 py-3 text-xs sm:text-sm font-black shadow-lg hover:bg-[#FCD116] hover:text-[#004D2A] transition-all duration-200 hover:-translate-y-0.5">

                                    <svg class="w-4 h-4"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2.5"
                                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>

                                    Découvrir les produits
                                </a>

                                @if (Route::has('register.seller'))
                                    <a href="{{ route('register.seller') }}"
                                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/10 border border-white/20 text-white px-5 py-3 text-xs sm:text-sm font-black hover:bg-white/15 transition">

                                        <svg class="w-4 h-4 text-[#FCD116]"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M3 21h18M5 21V8l7-4 7 4v13M9 21v-6h6v6"/>
                                        </svg>

                                        Devenir vendeur
                                    </a>
                                @endif

                            </div>

                            {{-- Micro garanties --}}
                            <div class="mt-8 pt-5 border-t border-white/10 flex flex-wrap gap-x-5 gap-y-2">

                                <div class="flex items-center gap-2 text-[10px] sm:text-[11px] font-bold text-emerald-50">
                                    <span class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center">
                                        ✓
                                    </span>
                                    Paiement Mobile Money
                                </div>

                                <div class="flex items-center gap-2 text-[10px] sm:text-[11px] font-bold text-emerald-50">
                                    <span class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center">
                                        ✓
                                    </span>
                                    Suivi de commande
                                </div>

                                <div class="flex items-center gap-2 text-[10px] sm:text-[11px] font-bold text-emerald-50">
                                    <span class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center">
                                        ✓
                                    </span>
                                    Vérification à réception
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


                {{-- COLONNE DROITE --}}
                <div class="lg:col-span-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-5">

                    {{-- Carte sécurité --}}
                    <div class="relative overflow-hidden rounded-[24px] bg-white border border-slate-200 p-5 sm:p-6 shadow-sm hover:shadow-md transition">

                        <div class="absolute right-[-25px] top-[-25px] w-24 h-24 rounded-full bg-emerald-50"></div>

                        <div class="relative">

                            <div class="flex items-center justify-between">

                                <span class="w-11 h-11 rounded-2xl bg-emerald-50 text-[#00843D] flex items-center justify-center text-xl">
                                    🛡️
                                </span>

                                <span class="text-[9px] font-black uppercase tracking-wider text-[#00843D] bg-emerald-50 px-2 py-1 rounded-full">
                                    Sécurité
                                </span>

                            </div>

                            <h2 class="mt-4 text-base sm:text-lg font-black text-slate-900">
                                Votre achat reste encadré
                            </h2>

                            <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                                Le paiement et la livraison suivent les étapes prévues par Ali-Kamer avant la finalisation de la commande.
                            </p>

                            <a href="#fonctionnement"
                               class="mt-4 inline-flex items-center gap-1.5 text-xs font-black text-[#00843D] hover:text-[#004D2A]">
                                Comprendre le fonctionnement
                                <svg class="w-3.5 h-3.5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                        </div>
                    </div>


                    {{-- Carte vendeur --}}
                    <div class="relative overflow-hidden rounded-[24px] bg-gradient-to-br from-[#FFFBEB] via-white to-[#FFF7ED] border border-amber-200 p-5 sm:p-6 shadow-sm hover:shadow-md transition">

                        <div class="flex items-center justify-between">

                            <span class="w-11 h-11 rounded-2xl bg-[#FCD116]/30 flex items-center justify-center text-xl">
                                🏪
                            </span>

                            <span class="text-[9px] font-black uppercase tracking-wider text-amber-700 bg-amber-100 px-2 py-1 rounded-full">
                                Vendeur
                            </span>

                        </div>

                        <h2 class="mt-4 text-base sm:text-lg font-black text-slate-900">
                            Développez votre boutique
                        </h2>

                        <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                            Présentez vos produits et atteignez des acheteurs au Cameroun grâce à Ali-Kamer.
                        </p>

                        @if (Route::has('register.seller'))
                            <a href="{{ route('register.seller') }}"
                               class="mt-4 inline-flex items-center gap-1.5 text-xs font-black text-[#00843D] hover:text-[#004D2A]">
                                Créer ma boutique
                                <svg class="w-3.5 h-3.5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endif

                    </div>

                </div>

            </div>
        </div>
    </section>


    {{-- =========================================================
        3. BARRE DE RÉASSURANCE
    ========================================================== --}}
    <section class="bg-white border-b border-slate-200/70">

        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-4">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5">

                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#00843D] flex items-center justify-center shrink-0">
                        🔒
                    </div>

                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs font-black text-slate-900">
                            Paiement sécurisé
                        </p>
                        <p class="mt-0.5 text-[9px] sm:text-[10px] text-slate-500">
                            Mobile Money
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        🚚
                    </div>

                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs font-black text-slate-900">
                            Livraison interurbaine
                        </p>
                        <p class="mt-0.5 text-[9px] sm:text-[10px] text-slate-500">
                            Via agences partenaires
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        📱
                    </div>

                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs font-black text-slate-900">
                            MTN MoMo & Orange
                        </p>
                        <p class="mt-0.5 text-[9px] sm:text-[10px] text-slate-500">
                            Paiement en FCFA
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                        🔑
                    </div>

                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs font-black text-slate-900">
                            Vérification à réception
                        </p>
                        <p class="mt-0.5 text-[9px] sm:text-[10px] text-slate-500">
                            Processus avec OTP
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- =========================================================
        4. CATÉGORIES
    ========================================================== --}}
    <section class="py-8 sm:py-10 bg-[#FAF9F6]">

        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-end justify-between gap-4 mb-5">

                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-[#00843D]">
                        Explorer
                    </p>

                    <h2 class="mt-1 text-xl sm:text-2xl font-black tracking-tight text-slate-900">
                        Qu'allez-vous acheter aujourd'hui ?
                    </h2>

                    <p class="mt-1 text-xs sm:text-sm text-slate-500">
                        Explorez les principales catégories de la marketplace.
                    </p>
                </div>

                <a href="{{ route('buyer.home') }}"
                   class="hidden sm:inline-flex items-center gap-1.5 text-xs font-black text-[#00843D] hover:text-[#004D2A]">
                    Tout voir

                    <svg class="w-3.5 h-3.5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

            </div>


            <div class="flex overflow-x-auto gap-2.5 pb-2 ak-hide-scrollbar">

                @foreach ($categoriesList as $cat)

                    @php
                        $isActive = request('category') === $cat['slug'];

                        $colorClasses = match ($cat['color']) {
                            'amber' => 'bg-amber-50 text-amber-700 group-hover:bg-amber-100',
                            'orange' => 'bg-orange-50 text-orange-700 group-hover:bg-orange-100',
                            'blue' => 'bg-blue-50 text-blue-700 group-hover:bg-blue-100',
                            'rose' => 'bg-rose-50 text-rose-700 group-hover:bg-rose-100',
                            'slate' => 'bg-slate-100 text-slate-600 group-hover:bg-slate-200',
                            default => 'bg-emerald-50 text-[#00843D] group-hover:bg-emerald-100',
                        };
                    @endphp

                    <a href="{{ route('buyer.home', ['category' => $cat['slug']]) }}#produits"
                       class="group shrink-0 w-[92px] sm:w-[105px] rounded-2xl bg-white border {{ $isActive ? 'border-[#00843D] ring-2 ring-emerald-100 shadow-sm' : 'border-slate-200' }} p-2.5 hover:border-[#00843D] hover:-translate-y-0.5 hover:shadow-md transition-all duration-200">

                        <div class="flex justify-center">

                            <span class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl flex items-center justify-center text-xl sm:text-2xl {{ $colorClasses }}">
                                {{ $cat['icon'] }}
                            </span>

                        </div>

                        <p class="mt-2 text-[10px] sm:text-[11px] text-center font-extrabold text-slate-700 group-hover:text-[#00843D] ak-line-clamp-1">
                            {{ $cat['name'] }}
                        </p>

                    </a>

                @endforeach

            </div>
        </div>
    </section>


    {{-- =========================================================
        5. CATALOGUE
    ========================================================== --}}
    <section id="produits"
             class="bg-white border-y border-slate-200/70 py-8 sm:py-10 scroll-mt-20">

        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header catalogue --}}
            <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-4 mb-6">

                <div>

                    <div class="flex items-center gap-2">

                        <span class="w-2 h-2 rounded-full bg-[#00843D]"></span>

                        <span class="text-[10px] font-black uppercase tracking-[0.15em] text-[#00843D]">
                            Marketplace
                        </span>

                    </div>

                    <h2 class="mt-1.5 text-xl sm:text-2xl font-black tracking-tight text-slate-900">

                        @if (request('q'))

                            Résultats pour
                            <span class="text-[#00843D]">
                                "{{ request('q') }}"
                            </span>

                        @elseif(request('category'))

                            {{ ucfirst(request('category')) }}

                        @else

                            Produits disponibles

                        @endif

                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        {{ $products->total() }}
                        {{ $products->total() > 1 ? 'produits disponibles' : 'produit disponible' }}
                    </p>

                </div>


                {{-- Outils --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">

                    {{-- Catégories rapides --}}
                    <div class="flex items-center gap-1.5 overflow-x-auto ak-hide-scrollbar">

                        <a href="{{ route('buyer.home', request()->except(['category', 'page'])) }}#produits"
                           class="shrink-0 px-3 py-1.5 rounded-full text-[10px] sm:text-[11px] font-black transition
                           {{ !request('category')
                                ? 'bg-[#00843D] text-white'
                                : 'bg-slate-50 border border-slate-200 text-slate-600 hover:border-[#00843D] hover:text-[#00843D]' }}">
                            Tous
                        </a>

                        @foreach ([
                            'agriculture' => '🌿 Agriculture',
                            'elevage' => '🐄 Élevage',
                            'informatique' => '💻 Tech',
                            'mode' => '👕 Mode',
                        ] as $slug => $label)

                            <a href="{{ route('buyer.home', array_merge(request()->except(['category', 'page']), ['category' => $slug])) }}#produits"
                               class="shrink-0 px-3 py-1.5 rounded-full text-[10px] sm:text-[11px] font-black transition
                               {{ request('category') === $slug
                                    ? 'bg-[#00843D] text-white'
                                    : 'bg-slate-50 border border-slate-200 text-slate-600 hover:border-[#00843D] hover:text-[#00843D]' }}">
                                {{ $label }}
                            </a>

                        @endforeach

                    </div>


                    {{-- Tri --}}
                    <form method="GET"
                          action="{{ route('buyer.home') }}"
                          class="shrink-0">

                        @if (request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        @if (request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        @if (request('city'))
                            <input type="hidden" name="city" value="{{ request('city') }}">
                        @endif

                        @if (request('shipping'))
                            <input type="hidden" name="shipping" value="{{ request('shipping') }}">
                        @endif

                        <select name="sort"
                                onchange="this.form.submit()"
                                class="w-full sm:w-auto bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-[11px] font-bold text-slate-700 outline-none focus:border-[#00843D] focus:ring-2 focus:ring-emerald-100 cursor-pointer">

                            <option value="recent" {{ request('sort', 'recent') === 'recent' ? 'selected' : '' }}>
                                Nouveautés
                            </option>

                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>
                                Plus populaires
                            </option>

                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                Prix croissant
                            </option>

                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                Prix décroissant
                            </option>

                        </select>

                    </form>

                </div>

            </div>


            {{-- PRODUITS --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2.5 sm:gap-3">

                @forelse ($products as $product)

                    @php

                        $availableStock = $product->availableStock();

                        $hasDiscount =
                            $product->old_price &&
                            $product->old_price > $product->price;

                        $discountPercent = 0;

                        if ($hasDiscount && $product->old_price > 0) {
                            $discountPercent = (int) round(
                                (($product->old_price - $product->price) / $product->old_price) * 100
                            );
                        }

                        $minQuantity = max(
                            1,
                            (int) ($product->min_quantity ?? 1)
                        );

                        $sellerAge = null;

                        if ($product->shop?->user?->created_at) {

                            $createdAt = $product->shop->user->created_at;
                            $now = now();

                            $years = (int) $createdAt->diffInYears($now);
                            $months = (int) $createdAt->diffInMonths($now);
                            $days = (int) $createdAt->diffInDays($now);

                            if ($years >= 1) {
                                $sellerAge = $years . ' ' .
                                    ($years > 1 ? 'ans' : 'an');
                            } elseif ($months >= 1) {
                                $sellerAge = $months . ' mois';
                            } elseif ($days >= 1) {
                                $sellerAge = $days . ' j';
                            } else {
                                $sellerAge = 'récent';
                            }
                        }

                        /*
                         * Calcul conservé selon ta structure actuelle.
                         * Idéalement, ce calcul devra ensuite être déplacé
                         * dans le Controller/Service pour éviter une requête
                         * par produit.
                         */
                        $salesCount = \App\Models\OrderItem::query()
                            ->where('product_id', $product->id)
                            ->whereHas('order', function ($query) {
                                $query->whereIn('status', [
                                    \App\Models\Order::STATUS_COMPLETED,
                                    \App\Models\Order::STATUS_AUTO_COMPLETED,
                                ]);
                            })
                            ->sum('quantity');

                    @endphp


                    {{-- CARTE PRODUIT --}}
                    <a href="{{ route('product.show', $product) }}"
                       class="group min-w-0 bg-white rounded-2xl overflow-hidden border border-slate-200 hover:border-[#00843D] hover:shadow-lg hover:shadow-slate-900/5 hover:-translate-y-0.5 transition-all duration-200 flex flex-col">

                        {{-- IMAGE --}}
                        <div class="relative aspect-square bg-slate-100 overflow-hidden">

                            @if ($product->images && $product->images->first())

                                <img src="{{ Storage::url($product->images->first()->url) }}"
                                     alt="{{ $product->title }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-[1.045] transition-transform duration-500">

                            @else

                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 text-slate-300">

                                    <svg class="w-9 h-9"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2v12a2 2 0 002 2z"/>

                                    </svg>

                                </div>

                            @endif


                            {{-- Transport --}}
                            <div class="absolute top-2 left-2">

                                @if ($product->shipping_included)

                                    <span class="inline-flex items-center gap-1 px-1.5 py-1 rounded-lg bg-[#00843D] text-white text-[8px] sm:text-[9px] font-black shadow-sm">
                                        🚚 Inclus
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1 px-1.5 py-1 rounded-lg bg-white/95 text-slate-600 text-[8px] sm:text-[9px] font-black shadow-sm">
                                        🚚 À prévoir
                                    </span>

                                @endif

                            </div>


                            {{-- Promotion --}}
                            @if ($hasDiscount && $discountPercent > 0)

                                <span class="absolute top-2 right-2 px-1.5 py-1 rounded-lg bg-[#CE1126] text-white text-[9px] font-black shadow-sm">
                                    -{{ $discountPercent }}%
                                </span>

                            @endif


                            {{-- Stock --}}
                            @if ($availableStock <= 0 || $product->status === 'sold_out')

                                <span class="absolute bottom-2 right-2 px-2 py-1 rounded-lg bg-slate-950/85 text-white text-[8px] font-black backdrop-blur-sm">
                                    Épuisé
                                </span>

                            @elseif ($availableStock <= 5)

                                <span class="absolute bottom-2 right-2 px-2 py-1 rounded-lg bg-[#FCD116] text-slate-950 text-[8px] font-black shadow-sm">
                                    {{ $availableStock }} restant{{ $availableStock > 1 ? 's' : '' }}
                                </span>

                            @endif

                        </div>


                        {{-- INFORMATIONS --}}
                        <div class="p-2.5 flex flex-col flex-1">

                            {{-- Boutique --}}
                            @if ($product->shop)

                                <div class="flex items-center justify-between gap-2">

                                    <div class="flex items-center gap-1 min-w-0">

                                        <span class="text-[9px] sm:text-[10px] font-black text-slate-600 truncate group-hover:text-[#00843D] transition">
                                            {{ $product->shop->name }}
                                        </span>

                                        @if ($product->shop->verified_at)

                                            <svg class="w-3 h-3 text-[#00843D] shrink-0"
                                                 fill="currentColor"
                                                 viewBox="0 0 20 20">

                                                <path fill-rule="evenodd"
                                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414 0L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5a1 1 0 000-1.414z"
                                                      clip-rule="evenodd"/>

                                            </svg>

                                        @endif

                                    </div>

                                    <span class="shrink-0 text-[8px] text-slate-400">
                                        📍 {{ $product->city ?? ($product->shop->city ?? 'Cameroun') }}
                                    </span>

                                </div>

                                @if ($sellerAge)

                                    <p class="mt-0.5 text-[8px] text-slate-400">
                                        Vendeur depuis {{ $sellerAge }}
                                    </p>

                                @endif

                            @endif


                            {{-- Titre --}}
                            <h3 class="mt-1.5 text-[11px] sm:text-[12px] font-bold text-slate-900 leading-[1.25] ak-line-clamp-2 min-h-[2.5em] group-hover:text-[#00843D] transition">
                                {{ $product->title }}
                            </h3>


                            {{-- Ventes --}}
                            <div class="mt-1.5 flex items-center justify-between gap-1">

                                <span class="text-[9px] text-slate-500 truncate">
                                    <strong class="text-slate-700">
                                        {{ number_format($salesCount, 0, ',', ' ') }}
                                    </strong>

                                    {{ $salesCount > 1 ? 'ventes' : 'vente' }}
                                </span>

                                <span class="shrink-0 rounded-md bg-emerald-50 text-[#00843D] px-1.5 py-0.5 text-[8px] font-black">
                                    Min. {{ $minQuantity }}
                                </span>

                            </div>


                            {{-- PRIX --}}
                            <div class="mt-2 pt-2 border-t border-slate-100 flex items-end justify-between gap-2">

                                <div class="min-w-0">

                                    @if ($product->hasVariants() && $product->minPrice() !== $product->maxPrice())

                                        <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-wide">
                                            À partir de
                                        </span>

                                    @endif

                                    <div class="flex items-baseline gap-1">

                                        <span class="text-[13px] sm:text-[14px] font-black text-[#00843D]">
                                            {{ number_format($product->minPrice(), 0, ',', ' ') }}
                                        </span>

                                        <span class="text-[8px] font-black text-slate-500">
                                            FCFA
                                        </span>

                                    </div>

                                    @if ($hasDiscount)

                                        <div class="text-[8px] text-[#CE1126] line-through font-bold">
                                            {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                                        </div>

                                    @endif

                                </div>


                                <span class="w-7 h-7 rounded-xl bg-emerald-50 text-[#00843D] flex items-center justify-center shrink-0 group-hover:bg-[#00843D] group-hover:text-white transition">

                                    <svg class="w-3.5 h-3.5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2.5"
                                              d="M9 5l7 7-7 7"/>

                                    </svg>

                                </span>

                            </div>

                        </div>

                    </a>

                @empty

                    <div class="col-span-full py-12">

                        <div class="max-w-lg mx-auto text-center">

                            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-2xl">
                                🔎
                            </div>

                            <h3 class="mt-4 text-base font-black text-slate-900">
                                Aucun produit trouvé
                            </h3>

                            <p class="mt-1 text-xs sm:text-sm text-slate-500">
                                Essayez une autre recherche ou explorez toutes les catégories disponibles.
                            </p>

                            <a href="{{ route('buyer.home') }}"
                               class="mt-5 inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#00843D] hover:bg-[#006B32] text-white text-xs font-black transition">
                                Voir tous les produits
                            </a>

                        </div>

                    </div>

                @endforelse

            </div>


            {{-- PAGINATION --}}
            @if ($products->hasPages())

                <div class="mt-8 flex justify-center ">
                    {{ $products->links() }}
                </div>

            @endif

        </div>
    </section>


    {{-- =========================================================
        6. COMMENT ÇA MARCHE
    ========================================================== --}}
    <section id="fonctionnement"
             class="bg-[#FAF9F6] py-10 sm:py-14 scroll-mt-20">

        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-2xl mx-auto">

                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-[#00843D] text-[10px] font-black uppercase tracking-wider">
                    Comment ça marche ?
                </span>

                <h2 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
                    Acheter devient simple.
                </h2>

                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Ali-Kamer organise les principales étapes entre l'achat,
                    le paiement, l'expédition et la réception.
                </p>

            </div>


            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Étape 1 --}}
                <div class="relative bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <span class="w-9 h-9 rounded-xl bg-emerald-50 text-[#00843D] flex items-center justify-center text-sm font-black">
                            01
                        </span>

                        <span class="text-xl">
                            🛒
                        </span>

                    </div>

                    <h3 class="mt-4 text-sm font-black text-slate-900">
                        Choisissez
                    </h3>

                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Trouvez un produit, consultez ses informations et passez votre commande.
                    </p>

                </div>


                {{-- Étape 2 --}}
                <div class="relative bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-black">
                            02
                        </span>

                        <span class="text-xl">
                            📱
                        </span>

                    </div>

                    <h3 class="mt-4 text-sm font-black text-slate-900">
                        Payez
                    </h3>

                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Réglez votre commande avec les moyens de paiement Mobile Money disponibles.
                    </p>

                </div>


                {{-- Étape 3 --}}
                <div class="relative bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <span class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-black">
                            03
                        </span>

                        <span class="text-xl">
                            🚚
                        </span>

                    </div>

                    <h3 class="mt-4 text-sm font-black text-slate-900">
                        Expédition
                    </h3>

                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Le vendeur prépare et remet le colis au point d'expédition prévu.
                    </p>

                </div>


                {{-- Étape 4 --}}
                <div class="relative bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-black">
                            04
                        </span>

                        <span class="text-xl">
                            🔑
                        </span>

                    </div>

                    <h3 class="mt-4 text-sm font-black text-slate-900">
                        Réception
                    </h3>

                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                        Vérifiez votre colis au retrait et utilisez le processus de confirmation prévu.
                    </p>

                </div>

            </div>


            {{-- CTA --}}
            <div class="mt-6 rounded-2xl bg-gradient-to-r from-[#004D2A] to-[#00843D] p-5 sm:p-6 text-white flex flex-col md:flex-row md:items-center justify-between gap-4">

                <div>

                    <p class="text-sm font-black">
                        Besoin de comprendre chaque étape ?
                    </p>

                    <p class="mt-1 text-[11px] text-emerald-100">
                        Consultez les guides Ali-Kamer avant votre première commande.
                    </p>

                </div>

                <div class="flex flex-wrap gap-2">

                    @if (Route::has('tutorials.index'))

                        <a href="{{ route('tutorials.index') }}"
                           class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white text-[#004D2A] text-xs font-black hover:bg-[#FCD116] transition">
                            Guide d'utilisation
                        </a>

                    @endif

                    @if (Route::has('about'))

                        <a href="{{ route('about') }}"
                           class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white text-xs font-black hover:bg-white/15 transition">
                            En savoir plus
                        </a>

                    @endif

                </div>

            </div>

        </div>
    </section>


    {{-- =========================================================
        7. SECTION VENDEUR
    ========================================================== --}}
    @if (Route::has('register.seller'))

        <section class="bg-white py-10 sm:py-12 border-t border-slate-200/70">

            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

                <div class="relative overflow-hidden rounded-[28px] bg-[#FFFBEB] border border-amber-200">

                    <div class="absolute right-[-60px] top-[-100px] w-72 h-72 rounded-full bg-[#FCD116]/20"></div>

                    <div class="relative z-10 p-6 sm:p-8 lg:p-10 flex flex-col lg:flex-row lg:items-center justify-between gap-7">

                        <div class="max-w-2xl">

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-amber-200 text-amber-700 text-[10px] font-black uppercase tracking-wider">
                                Espace vendeur
                            </span>

                            <h2 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
                                Vous avez des produits à vendre ?
                            </h2>

                            <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed max-w-xl">
                                Créez votre boutique Ali-Kamer, présentez vos produits
                                et développez votre activité auprès de nouveaux acheteurs.
                            </p>

                        </div>

                        <a href="{{ route('register.seller') }}"
                           class="inline-flex items-center justify-center gap-2 shrink-0 px-6 py-3 rounded-xl bg-[#00843D] hover:bg-[#006B32] text-white text-xs sm:text-sm font-black shadow-md transition hover:-translate-y-0.5">

                            Créer ma boutique

                            <svg class="w-4 h-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2.5"
                                      d="M9 5l7 7-7 7"/>
                            </svg>

                        </a>

                    </div>

                </div>

            </div>

        </section>

    @endif


    {{-- =========================================================
        8. IDENTITÉ ALI-KAMER
    ========================================================== --}}
    <section class="bg-[#004D2A] text-white py-9 sm:py-11">

        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid md:grid-cols-3 gap-7 md:gap-10 items-center">

                <div class="md:col-span-2">

                    <div class="flex items-center gap-3">

                        <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center">
                            <span class="text-xl">
                                🇨🇲
                            </span>
                        </div>

                        <div>

                            <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-200 font-black">
                                Notre ambition
                            </p>

                            <h2 class="text-xl sm:text-2xl font-black">
                                Construit au Cameroun pour l'Afrique.
                            </h2>

                        </div>

                    </div>

                    <p class="mt-4 text-xs sm:text-sm text-emerald-100/80 leading-relaxed max-w-2xl">
                        Ali-Kamer veut faciliter le commerce entre acheteurs et vendeurs
                        tout en construisant progressivement un environnement de confiance,
                        adapté aux réalités du marché camerounais.
                    </p>

                </div>


                <div class="grid grid-cols-2 gap-3">

                    <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                        <p class="text-xl font-black text-[#FCD116]">
                            🇨🇲
                        </p>
                        <p class="mt-1 text-[10px] font-bold text-emerald-100">
                            Pensé pour le Cameroun
                        </p>
                    </div>

                    <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                        <p class="text-xl font-black text-[#FCD116]">
                            🌍
                        </p>
                        <p class="mt-1 text-[10px] font-bold text-emerald-100">
                            Vision africaine
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        9. PETIT FOOTER DE CONFIANCE AVANT LE FOOTER GLOBAL
    ========================================================== --}}
    <section class="bg-white border-t border-slate-200">

        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-5">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">

                <div>

                    <p class="text-xs font-black text-slate-900">
                        Ali-Kamer
                    </p>

                    <p class="mt-0.5 text-[10px] text-slate-500">
                        Acheter et vendre sans stress.
                    </p>

                </div>

                <div class="flex flex-wrap items-center gap-4 text-[10px] font-bold text-slate-500">

                    <span class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#00843D]"></span>
                        Paiement Mobile Money
                    </span>

                    <span class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#FCD116]"></span>
                        Livraison interurbaine
                    </span>

                    <span class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#CE1126]"></span>
                        Commerce local
                    </span>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection