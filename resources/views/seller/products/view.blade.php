@extends('layouts.seller')

@section('title', 'Détails du produit - ALI-KAMER')

@section('content')
    <div class="w-full max-w-4xl mx-auto py-8 px-4 sm:px-6">

        {{-- Carte Principale --}}
        <div class="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden">

            {{-- En-tête ALI-KAMER --}}
            <div
                class="bg-blue-600 text-white py-6 px-6 sm:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <span class="text-xs uppercase font-extrabold tracking-widest text-blue-200">ALI-KAMER • Aperçu
                        Vendeur</span>
                    <h1 class="text-xl sm:text-2xl font-black mt-0.5">{{ $product->title }}</h1>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('seller.products.edit', $product) }}"
                        class="bg-white text-blue-600 hover:bg-blue-50 font-bold text-xs sm:text-sm px-4 py-2.5 rounded-xl transition-colors shadow-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </a>
                    <a href="{{ route('seller.products.index') }}"
                        class="bg-blue-700 hover:bg-blue-800 text-white font-semibold text-xs sm:text-sm px-4 py-2.5 rounded-xl transition-colors">
                        Retour
                    </a>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">

                {{-- 1. Galerie d'images --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-blue-600 rounded-full inline-block"></span>
                        Galerie Photos ({{ $product->images->count() }})
                    </h2>

                    @if ($product->images->count())
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @foreach ($product->images as $index => $image)
                                <div
                                    class="relative aspect-square rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 group">
                                    <img src="{{ Storage::url($image->url) }}" alt="{{ $product->title }}"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    @if ($loop->first)
                                        <span
                                            class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow">
                                            Principale
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-400 text-sm">
                            Aucune image enregistrée pour ce produit.
                        </div>
                    @endif
                </div>

                <hr class="border-slate-100">

                {{-- 2. Métriques clés (Prix, Stock, Statut) --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <span class="block text-xs font-semibold text-slate-400">Prix de vente</span>
                        <span class="block text-xl font-extrabold text-blue-600 mt-1">
                            {{ number_format($product->price, 0, ',', ' ') }} <span class="text-xs">FCFA</span>
                        </span>
                        @if ($product->old_price)
                            <span class="block text-xs text-slate-400 line-through mt-0.5">
                                {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                            </span>
                        @endif
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <span class="block text-xs font-semibold text-slate-400">Stock disponible</span>
                        <span
                            class="block text-xl font-extrabold {{ $product->stock > 0 ? 'text-slate-800' : 'text-red-600' }} mt-1">
                            {{ $product->stock }} <span class="text-xs font-normal text-slate-500">unité(s)</span>
                        </span>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <span class="block text-xs font-semibold text-slate-400">Quantité min. commande</span>
                        <span class="block text-xl font-extrabold text-slate-800 mt-1">
                            {{ $product->min_quantity ?? 1 }}
                        </span>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <span class="block text-xs font-semibold text-slate-400">Catégorie</span>
                        <span class="block text-sm font-bold text-slate-800 mt-2 truncate">
                            {{ $product->category->name ?? 'Non spécifiée' }}
                        </span>
                    </div>
                </div>

                {{-- 3. Détails de livraison --}}
                <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 space-y-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-blue-900">Modalités de livraison</h3>
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-slate-700">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-900">Mode :</span>
                            @if ($product->shipping_included)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                    Transport inclus dans le prix
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                    Transport exclu (À la charge de l'acheteur)
                                </span>
                            @endif
                        </div>

                        @if ($product->shipping_threshold_qty)
                            <div class="text-xs text-slate-600">
                                • Transport offert dès <strong
                                    class="text-slate-900">{{ $product->shipping_threshold_qty }}</strong> article(s)
                                acheté(s).
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 4. Description complète --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-2">Description du produit</h2>
                    <div
                        class="bg-slate-50 p-5 rounded-2xl border border-slate-100 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
