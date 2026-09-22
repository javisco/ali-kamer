@extends('layouts.admin')

@section('title', 'Produits')

@section('content')

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- En-tête --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Produits
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Catalogue visible actuellement sur la marketplace.
                    </p>
                </div>

                <a href="{{ route('admin.products.moderation') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#0a1b12] text-white text-sm font-semibold hover:bg-[#016837] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                    </svg>

                    Vue avancée / Modération
                </a>

            </div>

            {{-- Recherche --}}

            {{-- Recherche & Filtres Avancés --}}
            <form method="GET" action="{{ url()->current() }}"
                class="bg-gray-50/70 border border-gray-200/80 rounded-2xl p-3 mb-6">
                <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-2.5">

                    {{-- Barre de recherche principale --}}
                    <div class="flex-1 relative">
                        <label for="search-input" class="sr-only">Rechercher un produit</label>
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input type="text" id="search-input" name="q" value="{{ request('q') }}"
                            placeholder="Rechercher par nom, référence..."
                            class="w-full pl-10 pr-4 h-10 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/10 transition-all">
                    </div>

                    {{-- Sélecteur de statut stylisé --}}
                    <div class="w-full lg:w-52 relative">
                        <label for="status-select" class="sr-only">Statut</label>
                        <select id="status-select" name="status"
                            class="w-full h-10 pl-3 pr-8 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/10 transition-all appearance-none cursor-pointer">
                            <option value="" class="text-gray-500 font-normal">Tous les statuts</option>
                            <option value="visible" @selected(request('status') === 'visible') class="text-emerald-700 font-medium">🟢
                                Visible</option>
                            <option value="hidden" @selected(request('status') === 'hidden') class="text-gray-600">⚫ Masqué</option>
                            <option value="sold_out" @selected(request('status') === 'sold_out') class="text-amber-700 font-medium">🟠
                                Rupture</option>
                            <option value="banned" @selected(request('status') === 'banned') class="text-red-700 font-medium">🔴 Banni
                            </option>
                        </select>
                        {{-- Flèche personnalisée pour le select --}}
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    </div>

                    {{-- Zone de boutons d'action --}}
                    <div class="flex items-center gap-2 max-lg:w-full">
                        {{-- Lien de réinitialisation discret --}}
                        @if (request()->filled('q') || request()->filled('status'))
                            <a href="{{ url()->current() }}"
                                class="h-10 px-3.5 flex items-center justify-center text-xs font-semibold text-gray-500 hover:text-gray-800 rounded-xl hover:bg-gray-200/50 transition-all max-lg:flex-1">
                                Effacer
                            </a>
                        @endif

                        {{-- Bouton Soumettre principal --}}
                        <button type="submit"
                            class="h-10 px-5 bg-[#016837] hover:bg-[#00522b] active:scale-[0.98] text-white font-semibold text-sm rounded-xl tracking-wide shadow-sm shadow-[#016837]/10 transition-all flex items-center justify-center gap-1.5 max-lg:flex-1">
                            <span>Filtrer</span>
                        </button>
                    </div>

                </div>
            </form>



            {{-- Produits --}}
            @if ($products->count())

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                    @foreach ($products as $product)
                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition">

                            <div class="aspect-square bg-gray-100">

                                @if ($product->images && $product->images->first())
                                    <img src="{{ Storage::url($product->images->first()->url) }}"
                                        alt="{{ $product->title }}" loading="lazy"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[#FAF9F6] text-slate-300">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                            </div>

                            <div class="p-5">

                                <div class="flex items-start justify-between gap-3">

                                    <h2 class="font-bold text-gray-900 line-clamp-2">
                                        {{ $product->title }}
                                    </h2>

                                    <span
                                        class="shrink-0 px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Visible
                                    </span>

                                </div>

                                <p class="text-sm text-gray-500 mt-2">
                                    {{ $product->shop?->name ?? 'Boutique inconnue' }}
                                </p>

                                <div class="mt-4 flex items-center justify-between">

                                    <span class="font-bold text-[#016837]">
                                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                    </span>

                                    <span class="text-xs text-gray-500">
                                        Stock : {{ $product->stock }}
                                    </span>

                                </div>

                                <div class="grid grid-cols-2 gap-2 mt-5">

                                    <a href="{{ route('product.show', $product) }}" target="_blank"
                                        class="text-center px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium hover:bg-gray-50">
                                        Voir
                                    </a>

                                    <a href="{{ route('admin.products.show', $product) }}"
                                        class="text-center px-3 py-2 rounded-lg bg-[#016837] text-white text-sm font-semibold hover:bg-[#004d28]">
                                        Gérer
                                    </a>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">

                    <div class="text-gray-400 text-5xl mb-4">
                        📦
                    </div>

                    <h2 class="text-lg font-bold text-gray-900">
                        Aucun produit trouvé
                    </h2>

                    <p class="text-sm text-gray-500 mt-2">
                        Aucun produit ne correspond aux critères.
                    </p>

                </div>

            @endif

        </div>


    </div>

@endsection
