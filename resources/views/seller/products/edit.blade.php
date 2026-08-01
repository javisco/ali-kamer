@extends('base')

@section('title', 'Modifier le produit - ALI-KAMER')

@section('content')
    <div class="w-full max-w-4xl mx-auto py-8 px-4 sm:px-6">

        {{-- Carte Principale --}}
        <div class="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden p-8">

            {{-- En-tête ALI-KAMER --}}
            <div class="bg-blue-600 text-white text-center py-6 px-4 rounded-xl">
                <h1 class="text-2xl font-extrabold tracking-wide uppercase">ALI-KAMER</h1>
                <p class="text-blue-100 text-xs sm:text-sm mt-1">Modification du produit : {{ $product->title }}</p>
            </div>

            {{-- Messages d'erreurs globaux --}}
            @if ($errors->any())
                <div class="m-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm">
                    <div class="flex items-center gap-2 font-semibold mb-2 text-red-700">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Veuillez corriger les erreurs suivantes :</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-red-600 pl-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data"
                class="p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                {{-- 1. Photos actuelles --}}
                @if ($product->images->count())
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 bg-blue-600 rounded-full inline-block"></span>
                                Photos actuelles
                            </label>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full">
                                {{ $product->images->count() }} photo(s)
                            </span>
                        </div>

                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                            @foreach ($product->images as $image)
                                <div
                                    class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200 bg-white shadow-sm">
                                    <img src="{{ Storage::url($image->url) }}"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">

                                    {{-- Hover Corbeille --}}
                                    <form method="POST" action="{{ route('seller.products.image.delete', $image) }}"
                                        onsubmit="return confirm('Supprimer cette photo ?')"
                                        class="absolute inset-0 bg-slate-900/40 backdrop-blur-[1px] opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Supprimer"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-md transform group-hover:scale-100 scale-75 transition-transform duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- 2. Informations générales --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Titre de l'article <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $product->title) }}" required
                            maxlength="80" placeholder="Ex : Écharpe en soie"
                            class="w-full bg-slate-50/80 border @error('title') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" required
                            class="w-full bg-slate-50/80 border @error('category_id') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
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
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 3. Prix et Stock --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Prix actuel (FCFA) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required
                            min="100" max="10000000"
                            class="w-full bg-slate-50/80 border @error('price') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                        @error('price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Ancien prix <span class="text-slate-400 font-normal">(Optionnel)</span>
                        </label>
                        <input type="number" name="old_price" value="{{ old('old_price', $product->old_price) }}"
                            min="100" placeholder="Prix barré"
                            class="w-full bg-slate-50/80 border @error('old_price') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                        @error('old_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Stock disponible <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                            min="0"
                            class="w-full bg-slate-50/80 border @error('stock') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                        @error('stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Quantité minimale <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="min_quantity" value="{{ old('min_quantity', $product->min_quantity) }}"
                            required min="1"
                            class="w-full bg-slate-50/80 border @error('min_quantity') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                        @error('min_quantity')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 4. Mode de livraison --}}
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-slate-700">
                        Mode de livraison <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label
                            class="relative flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all {{ old('shipping_included', $product->shipping_included) == '1' ? 'border-blue-600 bg-blue-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                            <input type="radio" name="shipping_included" value="1"
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                {{ old('shipping_included', $product->shipping_included) == '1' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-slate-800">Transport inclus</span>
                                <span class="block text-xs text-slate-500">Inclus dans le prix de l'article</span>
                            </div>
                        </label>

                        <label
                            class="relative flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all {{ old('shipping_included', $product->shipping_included) == '0' ? 'border-blue-600 bg-blue-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                            <input type="radio" name="shipping_included" value="0"
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                {{ old('shipping_included', $product->shipping_included) == '0' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-slate-800">Transport exclu</span>
                                <span class="block text-xs text-slate-500">À la charge de l'acheteur</span>
                            </div>
                        </label>
                    </div>
                    @error('shipping_included')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <div class="pt-1">
                        <label class="block text-xs font-semibold text-slate-500 mb-1">
                            Offrir le transport à partir de combien d'articles ? (Optionnel)
                        </label>
                        <input type="number" name="shipping_threshold_qty"
                            value="{{ old('shipping_threshold_qty', $product->shipping_threshold_qty) }}" min="1"
                            placeholder="Ex : 5"
                            class="w-36 bg-slate-50/80 border @error('shipping_threshold_qty') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                        @error('shipping_threshold_qty')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 5. Description --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" required rows="4" placeholder="Description du produit..."
                        class="w-full bg-slate-50/80 border @error('description') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 6. Ajouter de nouvelles photos --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Ajouter des photos supplémentaires
                    </label>
                    <input type="file" name="images[]" accept="image/*" multiple
                        class="w-full bg-slate-50/80 border @error('images') border-red-500 @else border-slate-200 @enderror rounded-xl p-3 text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    @error('images')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Boutons d'action --}}
                <div class="pt-4 space-y-3">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-blue-600/25 transition-all duration-200 text-sm tracking-wide">
                        Enregistrer les modifications
                    </button>

                    <a href="{{ route('seller.products.index') }}"
                        class="block text-center w-full py-2 text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                        Annuler
                    </a>
                </div>

            </form>
        </div>
    </div>
@endsection