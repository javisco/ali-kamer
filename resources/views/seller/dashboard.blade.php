@extends('base')

@section('title', 'Tableau de bord Vendeur - Ali-Kamer')

@section('content')

    {{-- Style d'animation d'entrée --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.35s ease-out forwards; }
    </style>

    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">

        <div class="animate-fade-in space-y-6">

            {{-- 1. EN-TÊTE / HERO BANNER BLEU VIBRANT --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 text-white rounded-3xl p-6 sm:p-8 shadow-xl">
                {{-- Décoration lumineuse d'arrière-plan --}}
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute left-1/3 -top-12 w-40 h-40 bg-indigo-400/20 rounded-full blur-xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold text-white mb-3 border border-white/20">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Boutique Active
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white drop-shadow-sm">
                            {{ $shop->name }}
                        </h1>
                        <p class="text-blue-100 text-xs sm:text-sm mt-1 max-w-xl font-medium">
                            Gérez vos produits, visualisez vos performances et ajustez vos paramètres.
                        </p>
                    </div>

                    {{-- Actions rapides --}}
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('seller.products.create') }}"
                            class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-100 active:scale-95 text-blue-700 px-5 py-3 rounded-xl text-xs sm:text-sm font-bold shadow-lg transition-all duration-200">
                            <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Nouveau produit</span>
                        </a>

                        <a href="{{ route('seller.shop.edit') }}" title="Paramètres de la boutique"
                            class="p-3 bg-white/15 hover:bg-white/25 text-white rounded-xl transition border border-white/20 backdrop-blur-md">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. STATISTIQUES RÉSULTATS (KPIs) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                <!-- Card 1 : Total Produits -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-blue-300 transition-all">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Catalogue</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-slate-900">{{ $productsCount }}</p>
                    <p class="text-[11px] font-medium text-slate-500 mt-1">Produits enregistrés au total</p>
                </div>

                <!-- Card 2 : Produits Visibles -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-emerald-300 transition-all">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">En Ligne</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-emerald-600">{{ $visibleCount }}</p>
                    <p class="text-[11px] font-medium text-slate-500 mt-1">Articles actuellement en vente</p>
                </div>

                <!-- Card 3 : Localisation -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-amber-300 transition-all">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Siège / Stock</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-slate-900 truncate">{{ $shop->city ?? 'Non définie' }}</p>
                    <p class="text-[11px] font-medium text-slate-500 mt-1">Ville d'expédition des colis</p>
                </div>

            </div>

            {{-- 3. ACCÈS RAPIDES & GESTION --}}
            <div>
                <h2 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-3 px-1">
                    Gestion de l'activité
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    
                    <!-- 1. Gérer mes produits -->
                    <a href="{{ route('seller.products.index') }}"
                        class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-blue-500 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Mes Produits</h3>
                                <p class="text-xs text-slate-400">Ajouter, modifier ou masquer</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-600 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- 2. Commandes Client -->
                    <a href="{{ route('seller.orders.index') }}"
                        class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-orange-500 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 group-hover:text-orange-600 transition-colors">Mes Commandes</h3>
                                <p class="text-xs text-slate-400">Traiter et expédier les colis</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-orange-600 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- 3. Portefeuille & Séquestre -->
                    <a href="{{ route('seller.wallet.index') }}"
                        class="group bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-500 transition-all flex items-center justify-between sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Mon Portefeuille</h3>
                                <p class="text-xs text-slate-400">Solde disponible & retraits</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-600 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                </div>
            </div>

        </div>

    </div>

@endsection