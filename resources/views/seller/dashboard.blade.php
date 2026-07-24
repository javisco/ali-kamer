@extends('base')

@section('title', 'Tableau de bord Vendeur')

@section('content')

    {{-- Style CSS d'animations personnalisées --}}
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.4s ease-out forwards;
        }
    </style>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6">

        <!-- Container Global avec Animation -->
        <div class="animate-fade-in space-y-8 ">

            <!-- En-tête / Header de la boutique -->
            <div
                class="relative overflow-hidden bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 rounded-2xl p-6 sm:p-8 text-blue-700 shadow-xl">
                {{-- Forme décorative d'arrière-plan --}}
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none">
                </div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div
                            class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-xs font-medium text-blue-700 mb-2 border border-white/10">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            Boutique Active
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                            {{ $shop->name }}
                        </h1>
                        <p class="text-blue-500 text-xs sm:text-sm mt-1">
                            Gérez vos produits, visualisez vos performances et ajustez vos paramètres.
                        </p>
                    </div>

                    {{-- Action rapide d'ajout --}}
                    <a href="{{ route('seller.products.create') ?? '#' }}"
                        class="inline-flex items-center justify-center gap-2 bg-gray-400 text-blue-700 hover:bg-blue-50 px-4 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all duration-200 hover:shadow-lg active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Nouveau produit</span>
                    </a>
                </div>
            </div>

            <!-- Grille des statistiques (KPIs) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                <!-- Card 1 : Produits Total -->
                <div
                    class="group bg-white border border-gray-100 rounded-2xl p-5 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-blue-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Produits</span>
                        <div
                            class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900 tracking-tight">{{ $productsCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">Articles enregistrés dans votre boutique</p>
                </div>

                <!-- Card 2 : Produits Visibles -->
                <div
                    class="group bg-white border border-gray-100 rounded-2xl p-5 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-emerald-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">En Ligne</span>
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-emerald-600 tracking-tight">{{ $visibleCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">Visibles et achetables par la clientèle</p>
                </div>

                <!-- Card 3 : Ville de la Boutique -->
                <div
                    class="group bg-white border border-gray-100 rounded-2xl p-5 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-amber-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Localisation</span>
                        <div
                            class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 transition-colors group-hover:bg-amber-500 group-hover:text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-gray-900 truncate tracking-tight">{{ $shop->city }}</p>
                    <p class="text-xs text-gray-500 mt-1">Ville principale de livraison/stock</p>
                </div>

            </div>

            <!-- Section des actions rapides -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-4">
                    Gestion Rapide
                </h2>

                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Bouton 1 : Gérer les produits -->
                    <a href="{{ route('seller.products.index') }}"
                        class="flex-1 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-xl text-sm font-semibold shadow-md transition-all duration-200 hover:shadow-lg active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span>Gérer mes produits</span>
                    </a>

                    <!-- Bouton 2 : Modifier la boutique -->
                    <a href="{{ route('seller.shop.edit') }}"
                        class="flex-1 flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3.5 rounded-xl text-sm font-semibold transition-all duration-200 active:scale-[0.98]">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Modifier ma boutique</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

@endsection
