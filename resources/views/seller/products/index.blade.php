@extends('base')

@section('title', 'Mes Produits - Ali-Kamer')

@section('content')

    {{-- Style d'animation d'entrée --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.35s ease-out forwards; }
    </style>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">

        <div class="animate-fade-in space-y-6">

            {{-- 1. HERO BANNER BLEU --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 text-white rounded-3xl p-6 sm:p-8 shadow-xl">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold text-white mb-3 border border-white/20">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            Gestion du Catalogue
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                            Mes Produits
                        </h1>
                        <p class="text-blue-100 text-xs sm:text-sm mt-1 max-w-lg font-medium">
                            Gérez votre catalogue, contrôlez la visibilité et publiez vos articles sur Ali-Kamer.
                        </p>
                    </div>

                    <a href="{{ route('seller.products.create') }}"
                        class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-100 active:scale-95 text-blue-700 font-bold px-5 py-3 rounded-xl text-xs sm:text-sm shadow-lg transition-all duration-200 shrink-0">
                        <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Ajouter un produit</span>
                    </a>
                </div>
            </div>

            {{-- 2. ALERTE DE SUCCÈS --}}
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-5 py-4 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- 3. CARTES STATISTIQUES --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-blue-300 transition">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Articles</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">
                        {{ $products->total() }}
                    </h2>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-emerald-300 transition">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">En Ligne</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1">
                        {{ $products->where('status', 'visible')->count() }}
                    </h2>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-amber-300 transition">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Masqués</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-amber-500 mt-1">
                        {{ $products->where('status', 'hidden')->count() }}
                    </h2>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-red-300 transition">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Stock Faible (< 5)</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-red-600 mt-1">
                        {{ $products->where('stock', '<', 5)->count() }}
                    </h2>
                </div>

            </div>

            {{-- 4. TABLEAU DES PRODUITS --}}
            <div class="bg-white border border-slate-200/80 rounded-3xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-extrabold uppercase tracking-wider text-slate-400">
                                <th class="px-6 py-4">Produit</th>
                                <th class="px-6 py-4">Prix</th>
                                <th class="px-6 py-4">Stock</th>
                                <th class="px-6 py-4">Statut</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($products as $product)
                                <tr class="hover:bg-blue-50/40 transition-colors">
                                    
                                    {{-- Produit --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            @if ($product->images->first())
                                                <img src="{{ Storage::url($product->images->first()->url) }}" alt="{{ $product->title }}"
                                                    class="w-14 h-14 rounded-xl object-cover border border-slate-200 shrink-0">
                                            @else
                                                <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <h3 class="font-bold text-slate-800 text-sm truncate max-w-xs">
                                                    {{ $product->title }}
                                                </h3>
                                                <p class="text-xs text-slate-400 font-medium">
                                                    {{ $product->category->name ?? 'Sans catégorie' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Prix --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-extrabold text-blue-700">
                                            {{ number_format($product->price, 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                                        </div>
                                        @if (method_exists($product, 'hasDiscount') ? $product->hasDiscount() : ($product->old_price && $product->old_price > $product->price))
                                            <div class="text-slate-400 line-through text-xs font-medium">
                                                {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Stock --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($product->stock < 5)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-red-100 text-red-700 border border-red-200">
                                                ⚠️ {{ $product->stock }} en stock
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                {{ $product->stock }} en stock
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Statut --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($product->status == 'visible')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Visible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Masqué
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            
                                            <a href="{{ route('seller.products.view', $product->id) }}" title="Voir l'annonce"
                                                class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition border border-blue-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            <a href="{{ route('seller.products.edit', $product) }}" title="Modifier"
                                                class="p-2 bg-slate-100 text-slate-700 hover:bg-slate-800 hover:text-white rounded-xl transition border border-slate-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <form action="{{ route('seller.products.toggle', $product) }}" method="POST">
                                                @csrf
                                                <button type="submit" title="{{ $product->status == 'visible' ? 'Masquer' : 'Publier' }}"
                                                    class="p-2 {{ $product->status == 'visible' ? 'bg-amber-50 text-amber-600 border-amber-200 hover:bg-amber-500' : 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-600' }} hover:text-white rounded-xl transition border">
                                                    @if($product->status == 'visible')
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.982 8.982 0 013.682-.763c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18" />
                                                        </svg>
                                                    @else
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
                                                    class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition border border-red-200">
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
                                    <td colspan="5" class="py-16 text-center">
                                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-800">Aucun produit dans le catalogue</h3>
                                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                            Commencez dès maintenant en ajoutant votre premier article pour le mettre en vente.
                                        </p>
                                        <a href="{{ route('seller.products.create') }}"
                                            class="inline-flex items-center gap-2 mt-5 bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-blue-600/25 transition">
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
                <div class="mt-6 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif

        </div>

    </div>

@endsection