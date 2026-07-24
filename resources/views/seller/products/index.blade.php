@extends('base')

@section('title','Mes produits')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Mes produits
            </h1>

            <p class="text-gray-500 mt-1">
                Gérez votre catalogue et publiez vos produits sur ALI-KAMER.
            </p>
        </div>

        <a href="{{ route('seller.products.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow-lg transition">

            +

            Ajouter un produit

        </a>

    </div>

    {{-- MESSAGE --}}
    @if(session('success'))

        <div class="mb-6 bg-green-50 border border-green-300 text-green-700 rounded-xl px-5 py-4">

            {{ session('success') }}

        </div>

    @endif


    {{-- PETITES STATISTIQUES --}}

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <div class="bg-white rounded-2xl shadow p-5">

            <p class="text-gray-500 text-sm">
                Produits
            </p>

            <h2 class="text-3xl font-bold">
                {{ $products->total() }}
            </h2>

        </div>

        <div class="bg-white rounded-2xl shadow p-5">

            <p class="text-gray-500 text-sm">
                Visibles
            </p>

            <h2 class="text-3xl font-bold text-green-600">
                {{ $products->where('status','visible')->count() }}
            </h2>

        </div>

        <div class="bg-white rounded-2xl shadow p-5">

            <p class="text-gray-500 text-sm">
                Cachés
            </p>

            <h2 class="text-3xl font-bold text-orange-500">
                {{ $products->where('status','hidden')->count() }}
            </h2>

        </div>

        <div class="bg-white rounded-2xl shadow p-5">

            <p class="text-gray-500 text-sm">
                Stock faible
            </p>

            <h2 class="text-3xl font-bold text-red-600">
                {{ $products->where('stock','<',5)->count() }}
            </h2>

        </div>

    </div>


    {{-- TABLEAU --}}

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr class="text-left text-gray-600 text-sm">

                    <th class="px-6 py-4">Produit</th>

                    <th class="px-6 py-4">Prix</th>

                    <th class="px-6 py-4">Stock</th>

                    <th class="px-6 py-4">Statut</th>

                    <th class="px-6 py-4 text-center">Actions</th>

                </tr>

            </thead>

            <tbody>

            @forelse($products as $product)

                <tr class="border-t hover:bg-blue-50 transition">

                    <td class="px-6 py-4">

                        <div class="flex items-center gap-4">

                            @if($product->images->first())

                                <img
                                    src="{{ Storage::url($product->images->first()->url) }}"
                                    class="w-16 h-16 rounded-xl object-cover">

                            @else

                                <div class="w-16 h-16 rounded-xl bg-gray-200 flex items-center justify-center">

                                    📦

                                </div>

                            @endif

                            <div>

                                <h3 class="font-semibold">

                                    {{ $product->title }}

                                </h3>

                                <p class="text-sm text-gray-500">

                                    {{ $product->category->name }}

                                </p>

                            </div>

                        </div>

                    </td>

                    <td class="px-6 py-4">

                        <div class="font-bold text-blue-700">

                            {{ number_format($product->price,0,',',' ') }} FCFA

                        </div>

                        @if($product->hasDiscount())

                            <div class="text-gray-400 line-through text-sm">

                                {{ number_format($product->old_price,0,',',' ') }} FCFA

                            </div>

                        @endif

                    </td>

                    <td class="px-6 py-4">

                        @if($product->stock<5)

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">

                                {{ $product->stock }}

                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">

                                {{ $product->stock }}

                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-4">

                        @if($product->status=="visible")

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">

                                Visible

                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-600">

                                Caché

                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-4">

                        <div class="flex justify-center gap-3">

                            <a
                                href="{{ route('seller.products.edit',$product) }}"
                                class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-600 hover:text-white transition">

                                Modifier

                            </a>

                            <form
                                action="{{ route('seller.products.toggle',$product) }}"
                                method="POST">

                                @csrf

                                <button
                                    class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-500 hover:text-white transition">

                                    {{ $product->status=="visible" ? "Cacher" : "Publier" }}

                                </button>

                            </form>

                            <form
                                action="{{ route('seller.products.destroy',$product) }}"
                                method="POST"
                                onsubmit="return confirm('Supprimer définitivement ce produit ?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-600 hover:text-white transition">

                                    Supprimer

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="py-20 text-center">

                        <div class="text-6xl mb-3">

                            📦

                        </div>

                        <h2 class="text-xl font-bold">

                            Aucun produit

                        </h2>

                        <p class="text-gray-500 mt-2">

                            Commencez par créer votre premier produit.

                        </p>

                        <a
                            href="{{ route('seller.products.create') }}"
                            class="inline-block mt-5 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

                            Ajouter un produit

                        </a>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-8">

        {{ $products->links() }}

    </div>

</div>

@endsection