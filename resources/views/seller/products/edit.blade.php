@extends('base')
@section('title', 'edit product')
@section('content')
    <div class="max-w-2xl mx-auto py-8">

        <h1 class="text-2xl font-bold mb-6">Modifier le produit</h1>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Images existantes --}}
        @if ($product->images->count())
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Photos actuelles</label>
                <div class="flex gap-3 flex-wrap">
                    @foreach ($product->images as $image)
                        <div class="relative">
                            <img src="{{ $image->full_url }}"
                                class="w-20 h-20 rounded-lg object-cover border border-gray-200">
                            <form method="POST" action="{{ route('seller.products.image.delete', $image) }}"
                                onsubmit="return confirm('Supprimer cette photo ?')" class="absolute -top-2 -right-2">
                                @csrf @method('DELETE')
                                <button
                                    class="bg-red-500 text-white rounded-full w-5 h-5 text-xs leading-none hover:bg-red-600">×</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $product->title) }}" required maxlength="80"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Catégorie <span class="text-red-500">*</span>
                </label>
                <select name="category_id" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @foreach ($cat->children as $child)
                            <option value="{{ $child->id }}"
                                {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                &nbsp;&nbsp;↳ {{ $child->name }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Prix actuel (FCFA) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required
                        min="100" max="10000000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Ancien prix barré <span class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <input type="number" name="old_price" value="{{ old('old_price', $product->old_price) }}"
                        min="100"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Stock disponible <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                        min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Quantité minimale <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="min_quantity" value="{{ old('min_quantity', $product->min_quantity) }}"
                        required min="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                <label class="block text-sm font-medium">
                    Transport <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="shipping_included" value="1"
                            {{ old('shipping_included', $product->shipping_included) == '1' ? 'checked' : '' }}>
                        <span class="text-sm">Transport inclus</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="shipping_included" value="0"
                            {{ old('shipping_included', $product->shipping_included) == '0' ? 'checked' : '' }}>
                        <span class="text-sm">Transport exclu (payé par l'acheteur)</span>
                    </label>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">
                        À partir de quelle quantité vous gérez le transport ? (optionnel)
                    </label>
                    <input type="number" name="shipping_threshold_qty"
                        value="{{ old('shipping_threshold_qty', $product->shipping_threshold_qty) }}" min="1"
                        class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" required rows="5"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Ajouter des photos <span class="text-gray-400 font-normal">(optionnel)</span>
                </label>
                <p class="text-xs text-gray-500 mb-2">Max 5 photos au total. Max 2 MB par photo.</p>
                <input type="file" name="images[]" accept="image/*" multiple
                    class="w-full border border-gray-300 rounded-lg p-2 text-sm">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700">
                Enregistrer les modifications
            </button>
        </form>
    </div>
@endsection
