@extends('layouts.seller')

@section('title', 'Modifier le produit - ALI-KAMER')

@section('content')
    {{-- Conteneur principal avec état Alpine.js pour la modale image --}}
    <div x-data="productForm(@js($variantBuilder))" class="min-h-screen bg-[#F7F7F2] py-6 px-4 sm:px-6">
        <div class="w-full max-w-3xl mx-auto">

            {{-- Carte Principale --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-7 space-y-5">

                {{-- En-tête ALI-KAMER --}}
                <div class="bg-[#016837] text-white text-center py-5 px-4 rounded-xl relative">
                    <div class="flex items-center justify-center gap-1.5 mb-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#016837] border border-white/20"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#E30613]"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#F9A01B]"></span>
                    </div>
                    <h1 class="text-2xl font-black tracking-wider uppercase text-white">ALI-KAMER</h1>
                    <p class="text-[#F9A01B] font-medium text-xs sm:text-sm mt-0.5 truncate">Modification du produit : {{ $product->title }}</p>
                </div>

                {{-- Messages d'erreurs globaux --}}
                @if ($errors->any())
                    <div class="p-3.5 rounded-xl bg-[#E30613]/10 border border-[#E30613]/20 text-[#E30613] text-xs sm:text-sm font-medium">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Veuillez corriger les erreurs suivantes :</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data"
                    @submit="stripEmptyFileInputs($event)"
                    class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- 1. Photos actuelles --}}
                    @if ($product->images->count())
                        <div class="bg-[#F7F7F2]/60 p-3.5 sm:p-4 rounded-xl border border-gray-200">
                            <div class="flex items-center justify-between mb-2.5">
                                <label class="text-xs sm:text-sm font-bold text-[#0a1b12] flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 bg-[#016837] rounded-full inline-block"></span>
                                    Photos actuelles
                                </label>
                                <span class="text-xs font-black px-2.5 py-0.5 bg-[#F9A01B]/20 text-[#0a1b12] rounded-full">
                                    {{ $product->images->count() }} photo(s)
                                </span>
                            </div>

                            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2.5">
                                @foreach ($product->images as $image)
                                    <div
                                        class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 bg-white">
                                        <img src="{{ Storage::url($image->url) }}"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">

                                        {{-- Overlay avec 2 actions : Voir & Supprimer --}}
                                        <div
                                            class="absolute inset-0 bg-[#0a1b12]/50 backdrop-blur-[1px] opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-2">
                                            
                                            {{-- Bouton Agrandir --}}
                                            <button type="button" @click="activeImage = '{{ Storage::url($image->url) }}'"
                                                title="Agrandir"
                                                class="bg-white hover:bg-gray-100 text-[#0a1b12] p-1.5 rounded-full shadow-md transition-transform transform hover:scale-110">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                </svg>
                                            </button>

                                            {{-- Bouton Supprimer --}}
                                            <button type="button" title="Supprimer"
                                                onclick="deleteProductImage({{ $image->id }})"
                                                class="bg-[#E30613] hover:bg-[#b8040f] text-white p-1.5 rounded-full shadow-md transition-transform transform hover:scale-110">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 2. Informations générales --}}
                    <div class="space-y-3.5">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#0a1b12] mb-1">
                                Titre de l'article <span class="text-[#E30613]">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $product->title) }}" required
                                maxlength="80" placeholder="Ex : Écharpe en soie"
                                class="w-full bg-[#F7F7F2]/60 border @error('title') border-[#E30613] @else border-gray-200 @enderror rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#0a1b12] font-medium focus:bg-white focus:border-[#016837] focus:ring-1 focus:ring-[#016837] transition outline-none">
                            @error('title')
                                <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#0a1b12] mb-1">
                                Catégorie <span class="text-[#E30613]">*</span>
                            </label>
                            <select name="category_id" required
                                class="w-full bg-[#F7F7F2]/60 border @error('category_id') border-[#E30613] @else border-gray-200 @enderror rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#0a1b12] font-medium focus:bg-white focus:border-[#016837] focus:ring-1 focus:ring-[#016837] transition outline-none">
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
                            @error('category_id')
                                <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- 3. Prix et Stock --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#0a1b12] mb-1">
                                Prix actuel (FCFA) <span class="text-[#E30613]">*</span>
                            </label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required
                                min="100" max="10000000"
                                class="w-full bg-[#F7F7F2]/60 border @error('price') border-[#E30613] @else border-gray-200 @enderror rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#0a1b12] font-medium focus:bg-white focus:border-[#016837] focus:ring-1 focus:ring-[#016837] transition outline-none">
                            @error('price')
                                <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#0a1b12] mb-1">
                                Ancien prix <span class="text-gray-400 font-normal">(Optionnel)</span>
                            </label>
                            <input type="number" name="old_price" value="{{ old('old_price', $product->old_price) }}"
                                min="100" placeholder="Prix barré"
                                class="w-full bg-[#F7F7F2]/60 border @error('old_price') border-[#E30613] @else border-gray-200 @enderror rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#0a1b12] font-medium focus:bg-white focus:border-[#016837] focus:ring-1 focus:ring-[#016837] transition outline-none">
                            @error('old_price')
                                <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="!hasVariants">
                            <label class="block text-xs sm:text-sm font-bold text-[#0a1b12] mb-1">
                                Stock disponible <span class="text-[#E30613]">*</span>
                            </label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                                :required="!hasVariants"
                                class="w-full bg-[#F7F7F2]/60 border @error('stock') border-[#E30613] @else border-gray-200 @enderror rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#0a1b12] font-medium focus:bg-white focus:border-[#016837] focus:ring-1 focus:ring-[#016837] transition outline-none">
                            @error('stock')
                                <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <p x-show="hasVariants" x-cloak class="text-xs text-gray-500 sm:col-span-2">
                            Le stock se saisit par variante. Le prix ci-dessus sert de prix par défaut / d'affichage catalogue.
                        </p>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#0a1b12] mb-1">
                                Quantité minimale <span class="text-[#E30613]">*</span>
                            </label>
                            <input type="number" name="min_quantity" value="{{ old('min_quantity', $product->min_quantity) }}"
                                required min="1"
                                class="w-full bg-[#F7F7F2]/60 border @error('min_quantity') border-[#E30613] @else border-gray-200 @enderror rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#0a1b12] font-medium focus:bg-white focus:border-[#016837] focus:ring-1 focus:ring-[#016837] transition outline-none">
                            @error('min_quantity')
                                <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- 4. Mode de livraison --}}
                    <div class="space-y-2.5">
                        <label class="block text-xs sm:text-sm font-bold text-[#0a1b12]">
                            Mode de livraison <span class="text-[#E30613]">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label
                                class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition
                                {{ old('shipping_included', $product->shipping_included) == '1' ? 'border-[#016837] bg-[#016837]/5' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="shipping_included" value="1"
                                    class="w-4 h-4 text-[#016837] focus:ring-[#016837]"
                                    {{ old('shipping_included', $product->shipping_included) == '1' ? 'checked' : '' }}>
                                <div class="ml-2.5">
                                    <span class="block text-xs sm:text-sm font-bold text-[#0a1b12]">Transport inclus</span>
                                    <span class="block text-xs text-gray-500">Inclus dans le prix</span>
                                </div>
                            </label>

                            <label
                                class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition
                                {{ old('shipping_included', $product->shipping_included) == '0' ? 'border-[#016837] bg-[#016837]/5' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="shipping_included" value="0"
                                    class="w-4 h-4 text-[#016837] focus:ring-[#016837]"
                                    {{ old('shipping_included', $product->shipping_included) == '0' ? 'checked' : '' }}>
                                <div class="ml-2.5">
                                    <span class="block text-xs sm:text-sm font-bold text-[#0a1b12]">Transport exclu</span>
                                    <span class="block text-xs text-gray-500">À la charge de l'acheteur</span>
                                </div>
                            </label>
                        </div>
                        @error('shipping_included')
                            <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                        @enderror

                        <div class="pt-1">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">
                                Offrir le transport à partir de combien d'articles ? (Optionnel)
                            </label>
                            <input type="number" name="shipping_threshold_qty"
                                value="{{ old('shipping_threshold_qty', $product->shipping_threshold_qty) }}" min="1"
                                placeholder="Ex : 5"
                                class="w-36 bg-[#F7F7F2]/60 border @error('shipping_threshold_qty') border-[#E30613] @else border-gray-200 @enderror rounded-xl px-3 py-2 text-xs sm:text-sm text-[#0a1b12] font-medium focus:bg-white focus:border-[#016837] transition outline-none">
                            @error('shipping_threshold_qty')
                                <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- 5. Description --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#0a1b12] mb-1">
                            Description <span class="text-[#E30613]">*</span>
                        </label>
                        <textarea name="description" required rows="4" placeholder="Description du produit..."
                            class="w-full bg-[#F7F7F2]/60 border @error('description') border-[#E30613] @else border-gray-200 @enderror rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#0a1b12] font-medium focus:bg-white focus:border-[#016837] focus:ring-1 focus:ring-[#016837] transition outline-none">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    @include('seller.products._variants')

                    {{-- 6. Photos supplémentaires --}}
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#0a1b12] mb-1">
                            Ajouter des photos supplémentaires
                        </label>
                        <input type="file" name="images[]" accept="image/*" multiple
                            class="w-full bg-[#F7F7F2]/60 border @error('images') border-[#E30613] @else border-gray-200 @enderror rounded-xl p-2.5 text-xs sm:text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#016837] file:text-white hover:file:bg-[#0a542d] cursor-pointer transition">
                        @error('images')
                            <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="text-[#E30613] text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Boutons d'action --}}
                    <div class="pt-3 space-y-2">
                        <button type="submit"
                            class="w-full bg-[#016837] hover:bg-[#0a542d] text-white font-extrabold py-3 rounded-xl shadow-sm transition flex items-center justify-center gap-2 text-xs sm:text-sm">
                            <span>Enregistrer les modifications</span>
                            <span class="text-[#F9A01B] font-black">→</span>
                        </button>

                        <a href="{{ route('seller.products.index') }}"
                            class="block text-center w-full py-1.5 text-xs sm:text-sm font-bold text-gray-400 hover:text-[#E30613] transition">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>

        {{-- Formulaires de suppression d'images : hors du formulaire principal pour éviter les formulaires imbriqués. --}}
        @foreach ($product->images as $image)
            <form id="delete-image-{{ $image->id }}" method="POST"
                action="{{ route('seller.products.image.delete', $image) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

        {{-- Modale d'agrandissement d'image --}}
        <div x-show="activeImage" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="activeImage = null"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#0a1b12]/80 backdrop-blur-xs" 
             style="display: none;">

            {{-- Fond cliquable pour fermer --}}
            <div class="absolute inset-0" @click="activeImage = null"></div>

            {{-- Conteneur de l'image agrandie --}}
            <div class="relative bg-white rounded-2xl p-2 max-w-2xl w-full shadow-2xl z-10 border border-white/20">
                {{-- Bouton Croix (Fermer) --}}
                <button @click="activeImage = null" 
                        class="absolute -top-3 -right-3 bg-[#E30613] hover:bg-red-700 text-white rounded-full p-2 shadow-lg transition transform hover:scale-110 z-20 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="relative aspect-square w-full rounded-xl overflow-hidden bg-gray-100">
                    <img :src="activeImage" class="w-full h-full object-contain">
                </div>
            </div>
        </div>

    </div>
<script>
    function deleteProductImage(imageId) {
        if (!confirm('Supprimer cette photo ?')) return;
        const form = document.getElementById('delete-image-' + imageId);
        if (form) form.submit();
    }
</script>

@endsection