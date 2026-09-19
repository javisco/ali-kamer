@extends('layouts.buyer')

@section('title', 'Mes favoris')

@section('content')

<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="max-w-5xl mx-auto px-4">

        {{-- En-tête avec les touches Vert / Jaune / Rouge --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    {{-- Puces tricolores de la marque --}}
                    <span class="w-2.5 h-2.5 rounded-full bg-primary-600"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-danger"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-accent-500"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-accent-500 ml-1">
                        Ma sélection
                    </span>
                </div>

                <h1 class="text-2xl font-extrabold text-slate-900">
                    Mes favoris
                </h1>
            </div>

            {{-- Badge icône fond vert avec accent jaune --}}
            <div class="w-10 h-10 rounded-xl bg-primary-600 text-accent-500 flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </div>
        </div>

        @if($wishlist->isEmpty())

            {{-- État Vide : Accentuation Rouge & Bouton Vert --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10 sm:p-12 text-center">

                <div class="mx-auto w-14 h-14 rounded-2xl bg-danger/10 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 19.364 4.318 12.682a4.5 4.5 0 010-6.364z"/>
                    </svg>
                </div>

                <h2 class="text-base font-bold text-slate-900">
                    Votre liste est vide
                </h2>

                <p class="text-slate-500 text-sm mt-1">
                    Aucun favori pour l'instant.
                </p>

                <a href="{{ route('buyer.home') }}"
                   class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition shadow-sm">
                    Explorer le catalogue
                    <span class="text-accent-500">→</span>
                </a>
            </div>

        @else

            {{-- Grille des favoris --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4">

                @foreach($wishlist as $item)

                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition duration-200 group flex flex-col justify-between">

                        <div>
                            {{-- Visuel Produit --}}
                            <a href="{{ route('product.show', $item->product) }}"
                               class="block aspect-square bg-slate-50 overflow-hidden relative">

                                @if($item->product->images->first())
                                    <img src="{{ asset('storage/' . $item->product->images->first()->url) }}"
                                         alt="{{ $item->product->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Badge Jaune (Si promo ou variante) --}}
                                @if($item->product->hasVariants())
                                    <span class="absolute top-2 left-2 bg-accent-500 text-slate-900 text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm">
                                        VARIANTE
                                    </span>
                                @endif

                                {{-- Bouton SUPPRIMER (Rouge) --}}
                                <form method="POST" action="{{ route('buyer.wishlist.toggle', $item->product_id) }}" class="absolute top-2 right-2 z-10">
                                    @csrf
                                    <button type="submit" 
                                            class="w-8 h-8 rounded-full bg-white/90 shadow-sm flex items-center justify-center text-danger hover:bg-danger hover:text-white transition group/btn"
                                            title="Retirer des favoris">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </button>
                                </form>
                            </a>

                            <div class="p-3">
                                {{-- Titre Produit --}}
                                <a href="{{ route('product.show', $item->product) }}"
                                   class="block font-bold text-slate-900 text-sm line-clamp-2 hover:text-primary-600 transition leading-snug">
                                    {{ $item->product->title }}
                                </a>

                                {{-- Prix (Vert principal, texte d'accroche Jaune/Avertissement) --}}
                                <div class="mt-2">
                                    @if($item->product->hasVariants())
                                        <span class="text-[10px] font-extrabold uppercase text-accent-500 block">
                                            À partir de
                                        </span>
                                        <p class="text-primary-600 font-black text-base">
                                            {{ number_format($item->product->minPrice(), 0, ',', ' ') }} <span class="text-xs">FCFA</span>
                                        </p>
                                    @else
                                        <p class="text-primary-600 font-black text-base">
                                            {{ number_format($item->product->price, 0, ',', ' ') }} <span class="text-xs">FCFA</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Action Principale (Boutons Verts) --}}
                        <div class="p-3 pt-0 mt-auto">
                            @if($item->product->hasVariants())
                                <a href="{{ route('product.show', $item->product) }}"
                                   class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-3 rounded-xl text-xs text-center transition flex items-center justify-center gap-1.5 shadow-sm">
                                    <span>Option(s) disponible(s)</span>
                                    <span class="text-accent-500">→</span>
                                </a>
                            @else
                                <form method="POST" action="{{ route('buyer.cart.add', $item->product_id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-3 rounded-xl text-xs text-center transition flex items-center justify-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                        <span>Ajouter au panier</span>
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>

                @endforeach

            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $wishlist->links() }}
            </div>

        @endif

    </div>
</div>

@endsection