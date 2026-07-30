@extends('base')

@section('title', 'Accueil - Produits')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-slate-50 min-h-screen">

    {{-- Section Recherche Interactive --}}
    <div class="mb-8">
        <form method="GET" action="{{ route('buyer.home') }}" class="max-w-2xl mx-auto">
            <div class="relative flex items-center group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Rechercher un produit, une ville..."
                       class="w-full pl-11 pr-28 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 shadow-sm transition-all duration-200">

                <button type="submit" 
                        class="absolute right-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold px-5 py-2 rounded-xl text-xs transition-all duration-200 shadow-md shadow-blue-500/20">
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    {{-- En-tête des résultats --}}
    <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-200">
        <h1 class="text-base font-bold text-slate-900 flex items-center gap-2">
            @if(request('q'))
                Résultats pour <span class="text-blue-600 font-extrabold">"{{ request('q') }}"</span>
            @else
                Dernières annonces
            @endif
        </h1>
        <span class="text-xs font-bold text-slate-600 bg-slate-200/70 border border-slate-300/50 px-3 py-1 rounded-full">
            {{ $products->total() }} {{ Str::plural('produit', $products->total()) }}
        </span>
    </div>

    {{-- Grille Produits (4 par ligne sur grand écran, entièrement cliquable) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($products as $product)
            <a href="{{ route('product.show', $product) }}" 
               class="group relative bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-blue-400/60 hover:-translate-y-1.5 transition-all duration-300 ease-out flex flex-col justify-between">
                
                <div>
                    {{-- Zone Image avec Zoom fluide au survol --}}
                    <div class="w-full h-48 bg-slate-100 overflow-hidden border-b border-slate-100 relative">
                        @if ($product->images->first())
                            <img src="{{ Storage::url($product->images->first()->url) }}" 
                                 alt="{{ $product->title }}"
                                 class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500 ease-out">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 gap-1 bg-slate-50">
                                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[11px] font-medium text-slate-400">Pas d'image</span>
                            </div>
                        @endif

                        {{-- Tag Transport avec flou de fond (backdrop-blur) --}}
                        @if ($product->shipping_included)
                            <span class="absolute top-2.5 left-2.5 bg-emerald-600/95 backdrop-blur-md text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider">
                                Transport inclus
                            </span>
                        @endif
                    </div>

                    {{-- Contenu de la Carte --}}
                    <div class="p-4">
                        {{-- Titre --}}
                        <h2 class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors duration-200 line-clamp-1 leading-snug">
                            {{ $product->title }}
                        </h2>

                        {{-- Ville / Localisation --}}
                        <p class="text-[11px] font-medium text-slate-400 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate">{{ $product->city ?? 'Ville non précisée' }}</span>
                        </p>

                        {{-- Prix mis en évidence --}}
                        <div class="mt-3 flex items-baseline gap-2">
                            <span class="text-base font-extrabold text-emerald-600">
                                {{ number_format($product->price, 0, ',', ' ') }} <span class="text-[11px] font-bold">FCFA</span>
                            </span>

                            @if (method_exists($product, 'hasDiscount') && $product->hasDiscount())
                                <span class="text-[11px] font-medium text-slate-400 line-through">
                                    {{ number_format($product->old_price, 0, ',', ' ') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Bouton d'action interactif avec flèche dynamique --}}
                <div class="p-4 pt-0">
                    <span class="w-full flex items-center justify-center gap-1.5 bg-blue-600 group-hover:bg-blue-700 text-white font-semibold py-2 rounded-xl text-xs transition-all duration-200 shadow-sm shadow-blue-500/10">
                        <span>Voir le produit</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </span>
                </div>

            </a>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-600 font-semibold">Aucun produit trouvé.</p>
                @if(request('q'))
                    <a href="{{ route('buyer.home') }}" class="inline-block mt-2 text-xs text-blue-600 font-bold hover:underline">
                        Réinitialiser la recherche
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection