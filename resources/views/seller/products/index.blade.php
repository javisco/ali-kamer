@extends('layouts.seller')
@section('title', 'Mes Produits - Ali-Kamer')

@section('content')

    {{-- Style d'animation d'entrée --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.3s ease-out forwards; }
    </style>

    <div class="min-h-screen bg-slate-50 py-6 px-3 sm:px-6">
        <div class="max-w-6xl mx-auto animate-fade-in space-y-5">

            {{-- 1. HERO BANNER ALI-KAMER --}}
            <div class="bg-primary-600 text-white rounded-2xl p-5 sm:p-6 shadow-xs relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-3 py-1 rounded-full text-xs font-semibold text-white mb-2 border border-white/15">
                            <span class="w-2 h-2 rounded-full bg-accent-500"></span>
                            Gestion du Catalogue
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                            Mes Produits
                        </h1>
                        <p class="text-white/80 text-xs sm:text-sm mt-0.5 max-w-lg font-medium">
                            Gérez votre catalogue, contrôlez la visibilité et publiez vos articles sur Ali-Kamer.
                        </p>
                    </div>

                    <a href="{{ route('seller.products.create') }}"
                        class="inline-flex items-center justify-center gap-2 bg-accent-500 hover:bg-accent-600 active:scale-95 text-slate-900 font-black px-4 py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Ajouter un produit</span>
                    </a>
                </div>
            </div>

            {{-- 2. ALERTE DE SUCCÈS --}}
            @if (session('success'))
                <div class="bg-primary-600/10 border border-primary-600/20 text-primary-600 rounded-xl px-4 py-3 text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-xs">
                    <svg class="w-5 h-5 shrink-0 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- 3. CARTES STATISTIQUES --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Articles</p>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">
                        {{ $totalCount ?? $products->total() }}
                    </h2>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">En Ligne</p>
                    <h2 class="text-xl sm:text-2xl font-black text-primary-600 mt-0.5">
                        {{ $visibleCount ?? $products->where('status', 'visible')->count() }}
                    </h2>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Masqués</p>
                    <h2 class="text-xl sm:text-2xl font-black text-accent-500 mt-0.5">
                        {{ $hiddenCount ?? $products->where('status', 'hidden')->count() }}
                    </h2>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Stock Faible (< 5)</p>
                    <h2 class="text-xl sm:text-2xl font-black text-danger mt-0.5">
                        {{ $lowStockCount ?? $products->where('stock', '<', 5)->count() }}
                    </h2>
                </div>

            </div>

            {{-- 4. LISTE & TABLEAU DES PRODUITS --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                
                {{-- VUE MOBILE (Cartes fluides) --}}
                <div class="block sm:hidden divide-y divide-slate-100">
                    @forelse($products as $product)
                        <div class="p-3.5 space-y-3">
                            <div class="flex items-center gap-3">
                                @if ($product->images->first())
                                    <img src="{{ Storage::url($product->images->first()->url) }}" alt="{{ $product->title }}"
                                        class="w-12 h-12 rounded-lg object-cover border border-slate-200 shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                <div class="min-w-0 flex-1">
                                    <h3 class="font-bold text-slate-900 text-xs truncate">
                                        {{ $product->title }}
                                    </h3>
                                    <p class="text-[11px] text-slate-400">
                                        {{ $product->category->name ?? 'Sans catégorie' }}
                                    </p>
                                    <div class="font-black text-primary-600 text-xs mt-0.5">
                                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-50">
                                <div>
                                    @if ($product->availableStock() < 5)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-danger/10 text-danger">
                                            ⚠️ {{ $product->availableStock() }} stock
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary-600/10 text-primary-600">
                                            {{ $product->availableStock() }} stock
                                        </span>
                                    @endif
                                </div>

                                {{-- Actions Mobile --}}
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('seller.products.view', $product->id) }}" title="Voir"
                                        class="p-1.5 bg-slate-50 text-slate-900 hover:bg-primary-600 hover:text-white rounded-lg transition border border-slate-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('seller.products.edit', $product) }}" title="Modifier"
                                        class="p-1.5 bg-slate-50 text-slate-900 hover:bg-primary-600 hover:text-white rounded-lg transition border border-slate-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    {{-- Bascule Visibilité Mobile --}}
                                    <form action="{{ route('seller.products.toggle', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                            title="{{ $product->status == 'visible' ? 'Masquer le produit' : 'Rendre visible' }}"
                                            class="p-1.5 {{ $product->status == 'visible' ? 'bg-accent-500/20 text-slate-900 hover:bg-accent-500' : 'bg-primary-600/10 text-primary-600 hover:bg-primary-600 hover:text-white' }} rounded-lg transition border border-slate-200">
                                            @if($product->status == 'visible')
                                                {{-- Icône Œil Barré (Masquer) --}}
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.982 8.982 0 013.682-.763c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18" />
                                                </svg>
                                            @else
                                                {{-- Icône Œil Ouvert (Afficher) --}}
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <form action="{{ route('seller.products.destroy', $product) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ce produit ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 bg-danger/10 text-danger hover:bg-danger hover:text-white rounded-lg transition border border-danger-200">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <p class="text-xs text-slate-500">Aucun produit dans le catalogue</p>
                        </div>
                    @endforelse
                </div>

                {{-- VUE TABLEAU (Écrans moyens & grands) --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-black uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-3.5">Produit</th>
                                <th class="px-5 py-3.5">Prix</th>
                                <th class="px-5 py-3.5">Stock</th>
                                <th class="px-5 py-3.5">Statut</th>
                                <th class="px-5 py-3.5 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                            @forelse($products as $product)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    
                                    {{-- Produit --}}
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            @if ($product->images->first())
                                                <img src="{{ Storage::url($product->images->first()->url) }}" alt="{{ $product->title }}"
                                                    class="w-11 h-11 rounded-lg object-cover border border-slate-200 shrink-0">
                                            @else
                                                <div class="w-11 h-11 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <h3 class="font-bold text-slate-900 text-xs sm:text-sm truncate max-w-xs">
                                                    {{ $product->title }}
                                                </h3>
                                                <p class="text-xs text-slate-400 font-medium">
                                                    {{ $product->category->name ?? 'Sans catégorie' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Prix --}}
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="font-black text-primary-600">
                                            {{ number_format($product->price, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span>
                                        </div>
                                        @if (method_exists($product, 'hasDiscount') ? $product->hasDiscount() : ($product->old_price && $product->old_price > $product->price))
                                            <div class="text-slate-400 line-through text-[11px] font-medium">
                                                {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Stock --}}
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        @if ($product->availableStock() < 5)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-black bg-danger/10 text-danger border border-danger/20">
                                                ⚠️ {{ $product->availableStock() }} en stock
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-primary-600/10 text-primary-600 border border-primary-600/20">
                                                {{ $product->availableStock() }} en stock
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Statut (Badge informatif) --}}
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        @if ($product->status == 'visible')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-600/10 text-primary-600 border border-primary-600/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-primary-600"></span>
                                                Visible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Masqué
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            
                                            <a href="{{ route('seller.products.view', $product->id) }}" title="Aperçu public"
                                                class="p-1.5 bg-slate-50 text-slate-900 hover:bg-primary-600 hover:text-white rounded-lg transition border border-slate-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>

                                            <a href="{{ route('seller.products.edit', $product) }}" title="Modifier"
                                                class="p-1.5 bg-slate-50 text-slate-900 hover:bg-primary-600 hover:text-white rounded-lg transition border border-slate-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- Bascule Visibilité Écran Large --}}
                                            <form action="{{ route('seller.products.toggle', $product) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                    title="{{ $product->status == 'visible' ? 'Masquer le produit' : 'Publier le produit' }}"
                                                    class="p-1.5 {{ $product->status == 'visible' ? 'bg-accent-500/20 text-slate-900 border-accent-500/30 hover:bg-accent-500' : 'bg-primary-600/10 text-primary-600 border-primary-600/20 hover:bg-primary-600 hover:text-white' }} rounded-lg transition border">
                                                    @if($product->status == 'visible')
                                                        {{-- Icône Œil Barré (Masquer) --}}
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.982 8.982 0 013.682-.763c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18" />
                                                        </svg>
                                                    @else
                                                        {{-- Icône Œil Ouvert (Afficher) --}}
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce produit ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Supprimer"
                                                    class="p-1.5 bg-danger/10 text-danger hover:bg-danger hover:text-white rounded-lg transition border border-danger/20">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="w-12 h-12 bg-primary-600/10 text-primary-600 rounded-xl flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900">Aucun produit dans le catalogue</h3>
                                        <p class="text-xs text-slate-400 mt-0.5 max-w-sm mx-auto">
                                            Commencez dès maintenant en ajoutant votre premier article pour le mettre en vente.
                                        </p>
                                        <a href="{{ route('seller.products.create') }}"
                                            class="inline-flex items-center gap-1.5 mt-4 bg-primary-600 hover:bg-primary-700 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-xs transition">
                                            <span>Ajouter un premier produit</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 5. PAGINATION --}}
            @if($products->hasPages())
                <div class="mt-4 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection