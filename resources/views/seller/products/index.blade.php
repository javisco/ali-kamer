@extends('base')
@section('title','index')
@section('content')
    <div class="max-w-5xl mx-auto py-8">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Mes produits</h1>
            <a href="{{ route('seller.products.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium">
                + Ajouter un produit
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 text-sm text-gray-500">
                    <tr>
                        <th class="text-left px-4 py-3">Produit</th>
                        <th class="text-left px-4 py-3">Prix</th>
                        <th class="text-left px-4 py-3">Stock</th>
                        <th class="text-left px-4 py-3">Statut</th>
                        <th class="text-left px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($product->images->first())
                                        <img src="{{ Storage::url($product->images->first()->url) }}"
                                            class="w-12 h-12 rounded-lg object-cover">
                                    @endif
                                    <div>
                                        <p class="font-medium text-sm">{{ $product->title }}</p>
                                        <p class="text-xs text-gray-400">{{ $product->category->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ number_format($product->price, 0, ',', ' ') }} FCFA</p>
                                @if ($product->hasDiscount())
                                    <p class="text-xs text-gray-400 line-through">
                                        {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                                    </p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $product->stock }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded text-xs font-medium
                                {{ $product->status === 'visible' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $product->status === 'visible' ? 'Visible' : 'Caché' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('seller.products.edit', $product) }}"
                                        class="text-blue-600 hover:underline text-sm">Modifier</a>

                                    <form method="POST" action="{{ route('seller.products.toggle', $product) }}">
                                        @csrf
                                        <button class="text-gray-500 hover:underline text-sm">
                                            {{ $product->status === 'visible' ? 'Cacher' : 'Publier' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('seller.products.destroy', $product) }}"
                                        onsubmit="return confirm('Supprimer ce produit ?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:underline text-sm">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                Aucun produit. <a href="{{ route('seller.products.create') }}"
                                    class="text-blue-600 hover:underline">Créer votre premier produit</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
@endsection
