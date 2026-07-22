@extends('base')
@section('title', 'create')
@section('content')
    <div class="max-w-2xl mx-auto py-8">

        <h1 class="text-2xl font-bold mb-6">Ajouter un produit</h1>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Titre --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required maxlength="80"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                        focus:ring-2 focus:ring-blue-500"
                    placeholder="Nom clair et précis du produit">
                <p class="text-xs text-gray-400 mt-1">Entre 10 et 80 caractères</p>
            </div>

            {{-- Catégorie --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Catégorie <span class="text-red-500">*</span>
                </label>

                <select name="category_id" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                        focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @foreach ($cat->children as $child)
                            <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                &nbsp;&nbsp;↳ {{ $child->name }}
                            </option>
                        @endforeach
                    @endforeach


                </select>
            </div>

            {{-- Prix --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Prix actuel (FCFA) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price') }}" required min="100" max="10000000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                            focus:ring-2 focus:ring-blue-500"
                        placeholder="Ex: 5000">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Ancien prix barré <span class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <input type="number" name="old_price" value="{{ old('old_price') }}" min="100"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                              focus:ring-2 focus:ring-blue-500"
                        placeholder="Ex: 7000">
                </div>
            </div>

            {{-- Stock et quantité min --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Stock disponible <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', 1) }}" required min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                              focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Quantité minimale <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="min_quantity" value="{{ old('min_quantity', 1) }}" required min="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                              focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Transport --}}
            <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                <label class="block text-sm font-medium">
                    Transport <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="shipping_included" value="1"
                            {{ old('shipping_included') == '1' ? 'checked' : '' }}>
                        <span class="text-sm">Transport inclus</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="shipping_included" value="0"
                            {{ old('shipping_included', '0') == '0' ? 'checked' : '' }}>
                        <span class="text-sm">Transport exclu (payé par l'acheteur)</span>
                    </label>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">
                        À partir de quelle quantité vous gérez le transport ? (optionnel)
                    </label>
                    <input type="number" name="shipping_threshold_qty" value="{{ old('shipping_threshold_qty') }}"
                        min="1" class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Ex: 3">
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" required rows="5"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                             focus:ring-2 focus:ring-blue-500"
                    placeholder="Décrivez votre produit en détail (minimum 50 caractères)">{{ old('description') }}</textarea>
            </div>

            {{-- Photos --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Photos <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-500 mb-2">
                    Entre 1 et 5 photos. La première sera la photo principale. Max 2 MB par photo.
                </p>
                <input type="file" name="images[]" accept="image/*" multiple required
                    class="w-full border border-gray-300 rounded-lg p-2 text-sm">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700">
                Créer le produit
            </button>
        </form>
    </div>
@endsection
