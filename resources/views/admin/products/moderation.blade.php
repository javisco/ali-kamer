@extends('layouts.admin')

@section('title', 'Modération des produits')

@section('content')

    <div class="min-h-screen bg-gray-50 py-8">


        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">

                <div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-700">
                            ←
                        </a>

                        <h1 class="text-2xl font-bold text-gray-900">
                            Modération des produits
                        </h1>
                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        Vue avancée réservée aux administrateurs.
                    </p>
                </div>

            </div>

            {{-- Statistiques --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">

                @foreach ([['label' => 'Total', 'value' => $stats['total'], 'color' => 'gray'], ['label' => 'Visibles', 'value' => $stats['visible'], 'color' => 'green'], ['label' => 'Masqués', 'value' => $stats['hidden'], 'color' => 'yellow'], ['label' => 'Rupture', 'value' => $stats['sold_out'], 'color' => 'orange'], ['label' => 'Bannis', 'value' => $stats['banned'], 'color' => 'red'], ['label' => 'Supprimés', 'value' => $stats['deleted'], 'color' => 'gray']] as $stat)
                    <div class="bg-white border border-gray-200 rounded-2xl p-4">

                        <p class="text-xs text-gray-500">
                            {{ $stat['label'] }}
                        </p>

                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $stat['value'] }}
                        </p>

                    </div>
                @endforeach

            </div>

            {{-- Barre de Modération Optimisée --}}
            <form method="GET" action="{{ route('admin.products.moderation') }}"
                class="bg-white border border-gray-200 rounded-2xl p-4 mb-6 shadow-sm">
                <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-4">

                    {{-- Barre de recherche principale renforcée et plus grosse --}}
                    <div class="flex-1 relative">
                        <label for="search-input" class="sr-only">Rechercher</label>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input type="text" id="search-input" name="q" value="{{ request('q') }}"
                            placeholder="Rechercher par produit, référence, ID..."
                            class="w-full pl-11 pr-4 h-11 bg-white border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#016837] focus:ring-4 focus:ring-[#016837]/10 transition-all shadow-inner">
                    </div>

                    {{-- Conteneur des Sélecteurs de Filtres --}}
                    <div class="flex flex-wrap items-center gap-2 max-lg:grid max-lg:grid-cols-1 sm:max-lg:grid-cols-3">

                        {{-- Filtre : Statut Produit --}}
                        <div class="relative min-w-[150px]">
                            <select name="product_status"
                                class="w-full h-11 pl-3 pr-9 bg-gray-50 border border-gray-200 hover:bg-gray-100/80 rounded-xl text-xs font-semibold text-gray-600 focus:outline-none focus:bg-white focus:border-[#016837] focus:ring-4 focus:ring-[#016837]/5 transition-all appearance-none cursor-pointer">
                                <option value="">📦 Produit (Tout)</option>
                                <option value="visible" @selected(request('product_status') === 'visible')>🟢 En ligne</option>
                                <option value="hidden" @selected(request('product_status') === 'hidden')>⚫ Masqué</option>
                                <option value="sold_out" @selected(request('product_status') === 'sold_out')>🟠 Rupture</option>
                                <option value="banned" @selected(request('product_status') === 'banned')>🔴 Banni</option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>

                        {{-- Filtre : Statut Boutique --}}
                        <div class="relative min-w-[150px]">
                            <select name="shop_status"
                                class="w-full h-11 pl-3 pr-9 bg-gray-50 border border-gray-200 hover:bg-gray-100/80 rounded-xl text-xs font-semibold text-gray-600 focus:outline-none focus:bg-white focus:border-[#016837] focus:ring-4 focus:ring-[#016837]/5 transition-all appearance-none cursor-pointer">
                                <option value="">🏪 Boutique (Tout)</option>
                                <option value="active" @selected(request('shop_status') === 'active')>🟢 Active</option>
                                <option value="pending" @selected(request('shop_status') === 'pending')>🔵 En attente</option>
                                <option value="suspended" @selected(request('shop_status') === 'suspended')>🟠 Suspendue</option>
                                <option value="rejected" @selected(request('shop_status') === 'rejected')>🔴 Rejetée</option>
                                <option value="banned" @selected(request('shop_status') === 'banned')>🚫 Bannie</option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>

                        {{-- Filtre : Statut Vendeur --}}
                        <div class="relative min-w-[150px]">
                            <select name="seller_status"
                                class="w-full h-11 pl-3 pr-9 bg-gray-50 border border-gray-200 hover:bg-gray-100/80 rounded-xl text-xs font-semibold text-gray-600 focus:outline-none focus:bg-white focus:border-[#016837] focus:ring-4 focus:ring-[#016837]/5 transition-all appearance-none cursor-pointer">
                                <option value="">👤 Vendeur (Tout)</option>
                                <option value="active" @selected(request('seller_status') === 'active')>🟢 Actif</option>
                                <option value="candidate" @selected(request('seller_status') === 'candidate')>🟡 Candidat</option>
                                <option value="suspended" @selected(request('seller_status') === 'suspended')>🟠 Suspendu</option>
                                <option value="banned" @selected(request('seller_status') === 'banned')>🔴 Banni</option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>

                    </div>

                    {{-- Actions d'envoi et réinitialisation --}}
                    <div class="flex items-center gap-2 max-lg:w-full border-t lg:border-t-0 border-gray-100 max-lg:pt-3">

                        @if (request()->filled('q') ||
                                request()->filled('product_status') ||
                                request()->filled('shop_status') ||
                                request()->filled('seller_status'))
                            <a href="{{ route('admin.products.moderation') }}"
                                class="h-11 px-4 flex items-center justify-center text-xs font-bold text-gray-400 hover:text-gray-700 rounded-xl hover:bg-gray-100 transition-all max-lg:flex-1">
                                Effacer
                            </a>
                        @endif

                        <button type="submit"
                            class="h-11 px-6 bg-[#016837] hover:bg-[#004d28] text-white font-bold text-xs tracking-wider uppercase rounded-xl shadow-sm shadow-[#016837]/10 active:scale-[0.97] transition-all flex items-center justify-center gap-2 max-lg:flex-1">
                            <span>Filtrer</span>
                        </button>
                    </div>

                </div>
            </form>




            {{-- Tableau --}}
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-50 border-b border-gray-200">

                            <tr>

                                <th class="text-left px-5 py-4 font-semibold text-gray-600">
                                    Produit
                                </th>

                                <th class="text-left px-5 py-4 font-semibold text-gray-600">
                                    Produit
                                </th>

                                <th class="text-left px-5 py-4 font-semibold text-gray-600">
                                    Boutique
                                </th>

                                <th class="text-left px-5 py-4 font-semibold text-gray-600">
                                    Vendeur
                                </th>

                                <th class="text-left px-5 py-4 font-semibold text-gray-600">
                                    Statuts
                                </th>

                                <th class="text-right px-5 py-4 font-semibold text-gray-600">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-5 py-4">

                                        @php
                                            $image = $product->images->first();
                                        @endphp

                                        <div class="flex items-center gap-3">

                                            <div class="w-14 h-14 rounded-xl bg-gray-100 overflow-hidden shrink-0">

                                                @if ($image)
                                                    <img src="{{ asset('storage/' . $image->url) }}"
                                                        class="w-full h-full object-cover">
                                                @endif

                                            </div>

                                        </div>

                                    </td>

                                    <td class="px-5 py-4">

                                        <div class="font-semibold text-gray-900">
                                            {{ $product->title }}
                                        </div>

                                        <div class="text-xs text-gray-500 mt-1">
                                            #{{ $product->id }}
                                        </div>

                                    </td>

                                    <td class="px-5 py-4">

                                        <div class="font-medium text-gray-900">
                                            {{ $product->shop?->name ?? '—' }}
                                        </div>

                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $product->shop?->status ?? '—' }}
                                        </div>

                                    </td>

                                    <td class="px-5 py-4">

                                        <div class="font-medium text-gray-900">
                                            {{ $product->shop?->user?->name ?? '—' }}
                                        </div>

                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $product->shop?->user?->status ?? '—' }}
                                        </div>

                                    </td>

                                    <td class="px-5 py-4">

                                        <div class="space-y-1">

                                            <span
                                                class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $product->status === 'visible'
                                            ? 'bg-green-100 text-green-700'
                                            : ($product->status === 'banned'
                                                ? 'bg-red-100 text-red-700'
                                                : 'bg-yellow-100 text-yellow-700') }}">
                                                Produit : {{ $product->status }}
                                            </span>

                                            @if ($product->shop)
                                                <br>

                                                <span
                                                    class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                                    Boutique : {{ $product->shop->status }}
                                                </span>
                                            @endif

                                        </div>

                                    </td>

                                    <td class="px-5 py-4 text-right">

                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="inline-flex px-4 py-2 rounded-lg bg-[#016837] text-white font-semibold hover:bg-[#004d28]">
                                            Gérer
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                                        Aucun produit trouvé.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>

        </div>


    </div>

@endsection
