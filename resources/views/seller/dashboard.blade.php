@extends('layouts.seller')

@section('title', 'Tableau de bord Vendeur - Ali-Kamer')

@section('content')

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeInUp .3s cubic-bezier(.16,1,.3,1) forwards;
    }
</style>

<div class="min-h-screen bg-slate-50 p-4 sm:p-6 lg:p-7">


<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center
                sm:justify-between gap-4">

        <div>

            <p class="text-[11px] font-black uppercase
                      tracking-widest text-primary-600">
                Vue d'ensemble
            </p>

            <h1 class="mt-1 text-2xl sm:text-3xl font-black
                       tracking-tight text-slate-900">
                Bonjour, {{ auth()->user()->name ?? 'Vendeur' }} 👋
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Voici ce qui se passe actuellement dans votre boutique.
            </p>

        </div>

        <a
            href="{{ route('seller.products.create') }}"
            class="
                inline-flex items-center justify-center gap-2
                px-4 py-2.5 rounded-xl
                bg-primary-600
                hover:bg-primary-700
                text-white text-xs font-bold
                shadow-sm hover:shadow-md
                transition-all active:scale-95
            "
        >

            <svg class="w-4 h-4" fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2.5"
                    d="M12 4v16m8-8H4"/>

            </svg>

            Ajouter un produit

        </a>

    </div>


    {{-- =====================================================
        BOUTIQUE STATUS
    ====================================================== --}}
    <div
        class="
            relative overflow-hidden
            rounded-2xl
            bg-slate-900
            p-5 sm:p-6
            border border-slate-800
            shadow-sm
        "
    >

        {{-- Tricolore --}}
        <div class="absolute top-0 left-0 right-0 h-1 flex">
            <span class="w-1/3 bg-primary-600"></span>
            <span class="w-1/3 bg-[#CE1126]"></span>
            <span class="w-1/3 bg-[#FCD116]"></span>
        </div>

        <div class="relative flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-4">

            <div>

                <div class="flex items-center gap-2">

                    <span
                        class="w-2 h-2 rounded-full
                               bg-primary-600 animate-pulse"
                    ></span>

                    <span
                        class="text-[10px] font-bold uppercase
                               tracking-wider text-[#FCD116]"
                    >
                        Boutique active
                    </span>

                </div>

                <h2 class="text-lg sm:text-xl font-black
                           text-white mt-2">
                    {{ $shop->name ?? 'Ma boutique' }}
                </h2>

                <p class="text-xs text-slate-400 mt-1">
                    {{ $shop->city ?? 'Localisation non définie' }}
                </p>

            </div>


            <a
                href="{{ route('seller.shop.edit') }}"
                class="
                    inline-flex items-center justify-center gap-2
                    px-3.5 py-2 rounded-xl
                    bg-white/5 hover:bg-white/10
                    border border-white/10
                    text-xs font-bold text-slate-200
                    transition
                "
            >
                Configurer la boutique

                <svg class="w-3.5 h-3.5" fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"/>

                </svg>

            </a>

        </div>

    </div>


    {{-- =====================================================
        KPI
    ====================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Produits --}}
        <div
            class="
                bg-white rounded-2xl
                border border-slate-200/80
                p-5 shadow-sm
                hover:shadow-md transition
            "
        >

            <div class="flex items-center justify-between">

                <span class="text-[10px] font-black uppercase
                             tracking-wider text-slate-400">
                    Catalogue
                </span>

                <div
                    class="w-9 h-9 rounded-xl
                           bg-primary-600/10 text-primary-600
                           flex items-center justify-center"
                >
                    <svg class="w-5 h-5" fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M20 7l-8-4-8 4m16 0l-8 4
                            m8-4v10l-8 4m0-10L4 7
                            m8 4v10M4 7v10l8 4"/>

                    </svg>
                </div>

            </div>

            <p class="mt-4 text-3xl font-black text-slate-900">
                {{ $productsCount }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Produits enregistrés
            </p>

        </div>


        {{-- Actifs --}}
        <div
            class="
                bg-white rounded-2xl
                border border-slate-200/80
                p-5 shadow-sm
                hover:shadow-md transition
            "
        >

            <div class="flex items-center justify-between">

                <span class="text-[10px] font-black uppercase
                             tracking-wider text-slate-400">
                    En vente
                </span>

                <div
                    class="w-9 h-9 rounded-xl
                           bg-[#FCD116]/20 text-warning-800
                           flex items-center justify-center"
                >
                    <svg class="w-5 h-5" fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M2.458 12C3.732 7.943
                            7.523 5 12 5
                            c4.478 0 8.268 2.943
                            9.542 7-1.274 4.057
                            -5.064 7-9.542 7
                            -4.477 0-8.268-2.943
                            -9.542-7z"/>

                        <circle cx="12" cy="12" r="3"
                            stroke-width="1.8"/>

                    </svg>
                </div>

            </div>

            <p class="mt-4 text-3xl font-black text-primary-600">
                {{ $visibleCount }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Produits visibles
            </p>

        </div>


        {{-- Ville --}}
        <div
            class="
                bg-white rounded-2xl
                border border-slate-200/80
                p-5 shadow-sm
                hover:shadow-md transition
            "
        >

            <div class="flex items-center justify-between">

                <span class="text-[10px] font-black uppercase
                             tracking-wider text-slate-400">
                    Expédition
                </span>

                <div
                    class="w-9 h-9 rounded-xl
                           bg-[#CE1126]/10 text-[#CE1126]
                           flex items-center justify-center"
                >

                    <svg class="w-5 h-5" fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M17.657 16.657L13.414 20.9
                            a1.998 1.998 0 01-2.827 0
                            l-4.244-4.243
                            a8 8 0 1111.314 0z"/>

                        <circle cx="12" cy="11"
                            r="3"
                            stroke-width="1.8"/>

                    </svg>

                </div>

            </div>

            <p class="mt-4 text-2xl font-black text-slate-900 truncate">
                {{ $shop->city ?? 'Non définie' }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Ville principale
            </p>

        </div>


        {{-- Boutique --}}
        <div
            class="
                bg-white rounded-2xl
                border border-slate-200/80
                p-5 shadow-sm
                hover:shadow-md transition
            "
        >

            <div class="flex items-center justify-between">

                <span class="text-[10px] font-black uppercase
                             tracking-wider text-slate-400">
                    Statut
                </span>

                <div
                    class="w-9 h-9 rounded-xl
                           bg-primary-600/10 text-primary-600
                           flex items-center justify-center"
                >

                    <svg class="w-5 h-5" fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M5 13l4 4L19 7"/>

                    </svg>

                </div>

            </div>

            <p class="mt-4 text-xl font-black text-primary-600">
                Active
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Boutique opérationnelle
            </p>

        </div>

    </div>


    {{-- =====================================================
        ACTIONS + ALERTES
    ====================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Actions rapides --}}
        <section class="lg:col-span-1">

            <div class="flex items-center justify-between mb-3">

                <div>

                    <h2 class="text-sm font-black text-slate-900">
                        Actions rapides
                    </h2>

                    <p class="text-[11px] text-slate-400 mt-0.5">
                        Les tâches les plus utiles
                    </p>

                </div>

            </div>


            <div class="space-y-2.5">

                <a
                    href="{{ route('seller.products.create') }}"
                    class="
                        flex items-center gap-3
                        p-3.5 bg-white
                        border border-slate-200
                        rounded-xl
                        hover:border-primary-600
                        hover:shadow-sm
                        transition
                        group
                    "
                >

                    <div
                        class="w-9 h-9 rounded-lg
                               bg-primary-600/10
                               text-primary-600
                               flex items-center justify-center
                               group-hover:bg-primary-600
                               group-hover:text-white
                               transition"
                    >
                        <svg class="w-4 h-4" fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"/>

                        </svg>
                    </div>

                    <div class="flex-1">

                        <p class="text-xs font-bold text-slate-800">
                            Ajouter un produit
                        </p>

                        <p class="text-[10px] text-slate-400">
                            Enrichir votre catalogue
                        </p>

                    </div>

                    <span class="text-slate-300 group-hover:text-primary-600">
                        →
                    </span>

                </a>


                <a
                    href="{{ route('seller.orders.index') }}"
                    class="
                        flex items-center gap-3
                        p-3.5 bg-white
                        border border-slate-200
                        rounded-xl
                        hover:border-[#CE1126]
                        hover:shadow-sm
                        transition
                        group
                    "
                >

                    <div
                        class="w-9 h-9 rounded-lg
                               bg-[#CE1126]/10
                               text-[#CE1126]
                               flex items-center justify-center
                               group-hover:bg-[#CE1126]
                               group-hover:text-white
                               transition"
                    >

                        <svg class="w-4 h-4" fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M16 11V7a4 4 0 00-8 0v4
                                M5 9h14l1 12H4L5 9z"/>

                        </svg>

                    </div>

                    <div class="flex-1">

                        <p class="text-xs font-bold text-slate-800">
                            Gérer les commandes
                        </p>

                        <p class="text-[10px] text-slate-400">
                            Préparer et suivre vos ventes
                        </p>

                    </div>

                    <span class="text-slate-300 group-hover:text-[#CE1126]">
                        →
                    </span>

                </a>


                <a
                    href="{{ route('seller.wallet.index') }}"
                    class="
                        flex items-center gap-3
                        p-3.5 bg-white
                        border border-slate-200
                        rounded-xl
                        hover:border-[#FCD116]
                        hover:shadow-sm
                        transition
                        group
                    "
                >

                    <div
                        class="w-9 h-9 rounded-lg
                               bg-[#FCD116]/20
                               text-warning-800
                               flex items-center justify-center
                               group-hover:bg-[#FCD116]
                               transition"
                    >

                        <svg class="w-4 h-4" fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 10h18M7 15h1m4 0h1
                                m-7 4h12a2 2 0 002-2V8
                                a2 2 0 00-2-2H5a2 2 0 00-2 2v8
                                a2 2 0 002 2z"/>

                        </svg>

                    </div>

                    <div class="flex-1">

                        <p class="text-xs font-bold text-slate-800">
                            Voir le portefeuille
                        </p>

                        <p class="text-[10px] text-slate-400">
                            Solde et retraits
                        </p>

                    </div>

                    <span class="text-slate-300 group-hover:text-warning-800">
                        →
                    </span>

                </a>

            </div>

        </section>


        {{-- =================================================
            ACTIVITÉ RÉCENTE
        ================================================== --}}
        <section class="lg:col-span-2">

            <div class="flex items-center justify-between mb-3">

                <div>

                    <h2 class="text-sm font-black text-slate-900">
                        Activité récente
                    </h2>

                    <p class="text-[11px] text-slate-400 mt-0.5">
                        Les dernières opérations de votre boutique
                    </p>

                </div>

                <a
                    href="{{ route('seller.orders.index') }}"
                    class="text-[11px] font-bold text-primary-600
                           hover:underline"
                >
                    Voir tout
                </a>

            </div>


            <div
                class="
                    bg-white
                    border border-slate-200
                    rounded-2xl
                    overflow-hidden
                    shadow-sm
                "
            >

                {{-- Ligne 1 --}}
                <div
                    class="
                        p-4 flex items-center gap-3
                        border-b border-slate-100
                        hover:bg-slate-50
                        transition
                    "
                >

                    <div
                        class="w-9 h-9 rounded-xl
                               bg-primary-600/10
                               text-primary-600
                               flex items-center justify-center"
                    >

                        <svg class="w-4 h-4" fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M5 13l4 4L19 7"/>

                        </svg>

                    </div>

                    <div class="flex-1 min-w-0">

                        <p class="text-xs font-bold text-slate-800">
                            Boutique active
                        </p>

                        <p class="text-[10px] text-slate-400">
                            Votre boutique est actuellement disponible.
                        </p>

                    </div>

                    <span
                        class="text-[10px] font-bold
                               text-primary-600
                               bg-primary-600/10
                               px-2 py-1 rounded-full"
                    >
                        Active
                    </span>

                </div>


                {{-- Ligne 2 --}}
                <div
                    class="
                        p-4 flex items-center gap-3
                        border-b border-slate-100
                        hover:bg-slate-50
                        transition
                    "
                >

                    <div
                        class="w-9 h-9 rounded-xl
                               bg-[#FCD116]/20
                               text-warning-800
                               flex items-center justify-center"
                    >

                        <svg class="w-4 h-4" fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 8v4l3 3
                                m6-3a9 9 0 11-18 0
                                9 9 0 0118 0z"/>

                        </svg>

                    </div>

                    <div class="flex-1 min-w-0">

                        <p class="text-xs font-bold text-slate-800">
                            Catalogue
                        </p>

                        <p class="text-[10px] text-slate-400">
                            {{ $visibleCount }} produit(s) actuellement visible(s).
                        </p>

                    </div>

                    <span
                        class="text-[10px] font-bold
                               text-warning-800
                               bg-[#FCD116]/20
                               px-2 py-1 rounded-full"
                    >
                        Catalogue
                    </span>

                </div>


                {{-- Ligne 3 --}}
                <div
                    class="
                        p-4 flex items-center gap-3
                        hover:bg-slate-50
                        transition
                    "
                >

                    <div
                        class="w-9 h-9 rounded-xl
                               bg-[#CE1126]/10
                               text-[#CE1126]
                               flex items-center justify-center"
                    >

                        <svg class="w-4 h-4" fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M16 11V7a4 4 0 00-8 0v4
                                M5 9h14l1 12H4L5 9z"/>

                        </svg>

                    </div>

                    <div class="flex-1 min-w-0">

                        <p class="text-xs font-bold text-slate-800">
                            Commandes
                        </p>

                        <p class="text-[10px] text-slate-400">
                            Consultez vos commandes et traitez les nouvelles ventes.
                        </p>

                    </div>

                    <a
                        href="{{ route('seller.orders.index') }}"
                        class="text-[10px] font-bold text-[#CE1126]
                               hover:underline"
                    >
                        Ouvrir
                    </a>

                </div>

            </div>

        </section>

    </div>


    {{-- =====================================================
        FOOTER MARQUE
    ====================================================== --}}
    <div class="flex items-center justify-center gap-2 pt-1">

        <span class="w-5 h-1 rounded-full bg-primary-600"></span>
        <span class="w-5 h-1 rounded-full bg-[#CE1126]"></span>
        <span class="w-5 h-1 rounded-full bg-[#FCD116]"></span>

        <span class="text-[10px] font-semibold text-slate-400 ml-1">
            Ali-Kamer · Acheter et vendre sans stress
        </span>

    </div>

</div>


</div>

@endsection
