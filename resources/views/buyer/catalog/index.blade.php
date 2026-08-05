@extends('base')

@section('title', 'Ali-Kamer — Achetez et vendez en toute confiance au Cameroun')

@section('content')
<div class="bg-slate-50 min-h-screen pb-16">

    {{-- 1. HERO BANNER & RECHERCHE (Séquestre & Réassurance) --}}
    <section class="relative bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-white pt-10 pb-16 px-4 sm:px-6 lg:px-8 overflow-hidden rounded-b-3xl shadow-lg">
        {{-- Décoration de fond --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>

        <div class="max-w-4xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-2 bg-blue-500/20 text-blue-300 text-xs font-semibold px-3 py-1 rounded-full border border-blue-400/30 mb-4 backdrop-blur-md">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Marketplace Sécurisée au Cameroun
            </span>

            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white mb-3 leading-tight">
                Bienvenue sur <span class="text-orange-500">Ali-</span><span class="text-emerald-400">Kamer</span>
            </h1>
            <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto mb-8 font-medium">
                La plateforme qui protège vos transactions grâce au système de paiement séquestre.
            </p>

            {{-- Barre de recherche principale --}}
            <form method="GET" action="{{ route('buyer.home') }}" class="max-w-2xl mx-auto mb-8">
                <div class="relative flex items-center group shadow-2xl">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Rechercher un produit, une catégorie, une ville (ex: Yaoundé, Douala)..."
                        class="w-full pl-11 pr-32 py-4 bg-white text-slate-900 border-0 rounded-2xl text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/30 shadow-inner">

                    <button type="submit"
                        class="absolute right-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold px-6 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 shadow-md">
                        Rechercher
                    </button>
                </div>
            </form>

            {{-- Badges de Réassurance Hero --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 max-w-2xl mx-auto text-xs text-slate-300 pt-2 border-t border-slate-700/50">
                <div class="flex items-center justify-center gap-2">
                    <span class="text-emerald-400 font-bold">🛡</span>
                    <span>Paiement 100% Séquestre</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="text-orange-400 font-bold">🚚</span>
                    <span>Livraison partout au Cameroun</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="text-yellow-400 font-bold">⭐</span>
                    <span>Vendeurs vérifiés & certifiés</span>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-12">

        {{-- 2. RACCOURCIS CATÉGORIES --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900">Explorer par catégorie</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @php
                    $categories = [
                        ['name' => 'Électronique', 'icon' => '💻', 'slug' => 'electronique'],
                        ['name' => 'Mode & Beauté', 'icon' => '👕', 'slug' => 'mode'],
                        ['name' => 'Maison & Bureau', 'icon' => '🏠', 'slug' => 'maison'],
                        ['name' => 'Agriculture & Alimentation', 'icon' => '🌾', 'slug' => 'agriculture'],
                    ];
                @endphp

                @foreach($categories as $cat)
                    <a href="{{ route('buyer.home', ['category' => $cat['slug']]) }}"
                       class="flex items-center gap-3 p-3.5 bg-white border border-slate-200 rounded-2xl hover:border-blue-500 hover:shadow-md transition-all group">
                        <span class="text-2xl p-2 bg-slate-100 rounded-xl group-hover:bg-blue-50 transition-colors">{{ $cat['icon'] }}</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $cat['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- 3. SECTION PRODUITS POPULAIRES / RÉSULTATS --}}
        <section>
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-200">
                <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                    @if (request('q'))
                        Résultats de recherche pour <span class="text-blue-600 font-black">"{{ request('q') }}"</span>
                    @else
                        Produits Populaires & Récents
                    @endif
                </h2>
                <span class="text-xs font-bold text-slate-600 bg-slate-200/70 border border-slate-300/50 px-3 py-1 rounded-full">
                    {{ $products->total() }} {{ Str::plural('produit', $products->total()) }}
                </span>
            </div>

            {{-- Grille Produits --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @forelse($products as $product)
                    <a href="{{ route('product.show', $product) }}"
                        class="group relative bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-blue-400/60 hover:-translate-y-1.5 transition-all duration-300 ease-out flex flex-col justify-between">

                        <div>
                            {{-- Zone Image --}}
                            <div class="w-full h-48 bg-slate-100 overflow-hidden border-b border-slate-100 relative">
                                @if ($product->images->first())
                                    <img src="{{ Storage::url($product->images->first()->url) }}" alt="{{ $product->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 gap-1 bg-slate-50">
                                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-[11px] font-medium text-slate-400">Pas d'image</span>
                                    </div>
                                @endif

                                {{-- Tag Transport --}}
                                @if ($product->shipping_included)
                                    <span class="absolute top-2.5 left-2.5 z-10 bg-emerald-600/95 backdrop-blur-md text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider">
                                        Transport inclus
                                    </span>
                                @else
                                    <span class="absolute top-2.5 left-2.5 z-10 bg-slate-700/80 backdrop-blur-md text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider">
                                        Transport non inclus
                                    </span>
                                @endif
                            </div>

                            {{-- Contenu Carte --}}
                            <div class="px-4 py-3">
                                <h3 class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors duration-200 line-clamp-1 leading-snug">
                                    {{ $product->title }}
                                </h3>

                                <p class="text-[11px] font-medium text-slate-400 mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $product->city ?? 'Ville non précisée' }}</span>
                                </p>

                                {{-- Tarification & Réduction --}}
                                <div class="mt-3 flex items-center gap-2 flex-wrap">
                                    <span class="text-base font-extrabold text-emerald-600">
                                        {{ number_format($product->price, 0, ',', ' ') }} <span class="text-[11px] font-bold">FCFA</span>
                                    </span>

                                    @php
                                        $hasDiscount = (method_exists($product, 'hasDiscount') && $product->hasDiscount()) 
                                            || ($product->old_price && $product->old_price > $product->price);
                                    @endphp

                                    @if ($hasDiscount)
                                        <span class="text-[11px] font-medium text-slate-400 line-through">
                                            {{ number_format($product->old_price, 0, ',', ' ') }}
                                        </span>

                                        @php
                                            $discountPercent = round((($product->old_price - $product->price) / $product->old_price) * 100);
                                        @endphp
                                        <span class="text-[10px] font-black text-red-600 bg-red-50 border border-red-200 px-1.5 py-0.5 rounded">
                                            -{{ $discountPercent }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Bouton d'action --}}
                        <div class="p-4 pt-0 mt-2">
                            <span class="w-full flex items-center justify-center gap-1.5 bg-blue-600 group-hover:bg-blue-700 text-white font-semibold py-2 rounded-xl text-xs transition-all duration-200 shadow-sm">
                                <span>Voir le produit</span>
                                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600 font-semibold">Aucun produit trouvé pour le moment.</p>
                        @if (request('q'))
                            <a href="{{ route('buyer.home') }}" class="inline-block mt-2 text-xs text-blue-600 font-bold hover:underline">
                                Voir tous les produits
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($products->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        </section>

        {{-- 4. BOUTIQUES RECOMMANDÉES --}}
        @if(isset($recommendedShops) && count($recommendedShops) > 0)
            <section class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span>🏪</span> Boutiques Recommandées
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($recommendedShops as $shop)
                        <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-200/60 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white font-black flex items-center justify-center text-base shadow-sm">
                                    {{ strtoupper(substr($shop->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">{{ $shop->name }}</h3>
                                    <div class="flex items-center gap-3 text-xs text-slate-500 mt-0.5">
                                        <span class="text-yellow-500 font-bold flex items-center gap-1">★ 4.9</span>
                                        <span>•</span>
                                        <span>{{ $shop->products_count ?? 0 }} produits</span>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-xl transition">
                                Voir boutique
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- 5. POURQUOI ALI-KAMER ? (Réassurance & Piliers) --}}
        <section class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-8 rounded-3xl shadow-md">
            <div class="text-center max-w-xl mx-auto mb-8">
                <h2 class="text-xl font-extrabold mb-2">Pourquoi choisir Ali-Kamer ?</h2>
                <p class="text-xs text-slate-300">Votre confiance est notre priorité absolue lors de chaque transaction.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white/5 border border-white/10 p-5 rounded-2xl backdrop-blur-sm">
                    <div class="text-3xl mb-3">🔒</div>
                    <h3 class="text-sm font-bold text-white mb-1">Système Séquestre</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">L'argent est conservé en sécurité et versé au vendeur uniquement après réception et validation du colis.</p>
                </div>

                <div class="bg-white/5 border border-white/10 p-5 rounded-2xl backdrop-blur-sm">
                    <div class="text-3xl mb-3">🚚</div>
                    <h3 class="text-sm font-bold text-white mb-1">Livraison Nationale</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">Réseau de transport partenaires assurant la livraison dans les 10 régions du Cameroun.</p>
                </div>

                <div class="bg-white/5 border border-white/10 p-5 rounded-2xl backdrop-blur-sm">
                    <div class="text-3xl mb-3">💰</div>
                    <h3 class="text-sm font-bold text-white mb-1">Garantie Remboursement</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">En cas de non-conformité ou litige, notre service client intervient pour effectuer un remboursement rapide.</p>
                </div>

                <div class="bg-white/5 border border-white/10 p-5 rounded-2xl backdrop-blur-sm">
                    <div class="text-3xl mb-3">⭐</div>
                    <h3 class="text-sm font-bold text-white mb-1">Vendeurs Vérifiés</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">Processus d'authentification KYC strict garantissant des vendeurs professionnels sérieux.</p>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection