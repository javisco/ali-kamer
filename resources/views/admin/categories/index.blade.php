@extends('base')

@section('title', 'Tableau de bord Vendeur - Ali-Kamer')

@section('content')


    {{-- CSS d'animation --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="min-h-screen bg-slate-50/50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-8 animate-fade-in">

            {{-- 1. HERO BANNER --}}
            <div class="relative overflow-hidden bg-slate-900 rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-800">
                {{-- Light leaks decoratifs --}}
                <div class="absolute -right-12 -top-12 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute left-1/2 -bottom-12 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 bg-slate-800/80 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold text-slate-200 border border-slate-700/60">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Boutique active
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                            {{ $shop->name }}
                        </h1>
                        <p class="text-slate-400 text-xs sm:text-sm max-w-xl">
                            Pilotez l'ensemble de votre catalogue, suivez vos commandes et gérez votre trésorerie.
                        </p>
                    </div>

                    {{-- Actions rapides --}}
                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ route('seller.products.create') }}"
                            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 active:scale-95 text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold shadow-sm transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Nouveau produit</span>
                        </a>

                        <a href="{{ route('seller.shop.edit') }}" title="Paramètres de la boutique"
                            class="p-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl transition border border-slate-700">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. KPIs METRICS --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                <!-- Card 1 : Total Produits -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Catalogue</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-black text-slate-900 tracking-tight">{{ $productsCount }}</p>
                        <p class="text-xs text-slate-500 mt-1">Articles enregistrés</p>
                    </div>
                </div>

                <!-- Card 2 : Produits Visibles -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">En vente</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-black text-emerald-600 tracking-tight">{{ $visibleCount }}</p>
                        <p class="text-xs text-slate-500 mt-1">Produits actifs en boutique</p>
                    </div>
                </div>

                <!-- Card 3 : Localisation -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Localisation</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-2xl font-black text-slate-900 truncate tracking-tight">{{ $shop->city ?? 'Non définie' }}</p>
                        <p class="text-xs text-slate-500 mt-1">Ville d'expédition principale</p>
                    </div>
                </div>

            </div>

            {{-- 3. NAVIGATION TECHNIQUE / GESTION --}}
            <div class="space-y-4">
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">
                    Gestion du commerce
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <!-- Produits -->
                    <a href="{{ route('seller.products.index') }}"
                        class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-blue-500 transition-all flex flex-col justify-between h-32">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-600 transform group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Mes Produits</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Gérer le catalogue</p>
                        </div>
                    </a>

                    <!-- Commandes -->
                    <a href="{{ route('seller.orders.index') }}"
                        class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-orange-500 transition-all flex flex-col justify-between h-32">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-orange-600 transform group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-orange-600 transition-colors">Commandes</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Traiter les ventes</p>
                        </div>
                    </a>

                    <!-- Portefeuille -->
                    <a href="{{ route('seller.wallet.index') }}"
                        class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-500 transition-all flex flex-col justify-between h-32">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-600 transform group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Portefeuille</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Solde & demandes de retrait</p>
                        </div>
                    </a>

                    <!-- Historique Transactions -->
                    <a href="{{ route('seller.wallet.history') }}"
                        class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-indigo-500 transition-all flex flex-col justify-between h-32">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-indigo-600 transform group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Transactions</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Historique des mouvements</p>
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </div>

@endsection