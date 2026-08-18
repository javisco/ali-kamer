@extends('base')

@section('title', 'Ajouter un produit - ALI-KAMER')

@section('content')
    <div class="w-full max-w-4xl mx-auto py-8 px-4 sm:px-6">

        {{-- Carte Principale --}}
        <div class="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden">

            {{-- En-tête ALI-KAMER --}}
            <div class="bg-blue-600 text-white text-center py-6 px-4">
                <h1 class="text-2xl font-extrabold tracking-wide uppercase">ALI-KAMER</h1>
                <p class="text-blue-100 text-xs sm:text-sm mt-1">Ajouter un nouveau produit à votre catalogue</p>
            </div>

            {{-- Messages d'erreurs --}}
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

            <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data"
                class="p-6 sm:p-8 space-y-6">
                @csrf

                {{-- 1. Informations générales --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Titre du produit <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required maxlength="80"
                            placeholder="Ex : Chaussures en cuir fait main"
                            class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                        <p class="text-xs text-slate-400 mt-1">Saisissez un titre clair (entre 10 et 80 caractères)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" required
                            class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                            <option value="">-- Choisir une catégorie --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @foreach ($cat->children as $child)
                                    <option value="{{ $child->id }}"
                                        {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;↳ {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 2. Prix et Stock --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Prix actuel (FCFA) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}" required min="1"
                            max="10000000" placeholder="Ex : 5000"
                            class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Ancien prix <span class="text-slate-400 font-normal">(Optionnel)</span>
                        </label>
                        <input type="number" name="old_price" value="{{ old('old_price') }}" min="1"
                            placeholder="Ex : 7000 (Prix barré)"
                            class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Stock disponible <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="stock" value="{{ old('stock', 1) }}" required min="0"
                            class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            Quantité minimale <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="min_quantity" value="{{ old('min_quantity', 1) }}" required
                            min="1"
                            class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                    </div>
                </div>


                


                {{-- 3. Mode de livraison --}}
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-slate-700">
                        Mode de livraison <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label
                            class="relative flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all {{ old('shipping_included') == '1' ? 'border-blue-600 bg-blue-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                            <input type="radio" name="shipping_included" value="1"
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                {{ old('shipping_included') == '1' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-slate-800">Transport inclus</span>
                                <span class="block text-xs text-slate-500">Inclus dans le prix du produit</span>
                            </div>
                        </label>

                        <label
                            class="relative flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all {{ old('shipping_included', '0') == '0' ? 'border-blue-600 bg-blue-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                            <input type="radio" name="shipping_included" value="0"
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                {{ old('shipping_included', '0') == '0' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-slate-800">Transport exclu</span>
                                <span class="block text-xs text-slate-500">À la charge de l'acheteur</span>
                            </div>
                        </label>
                    </div>

                    <div class="pt-1">
                        <label class="block text-xs font-semibold text-slate-500 mb-1">
                            Offrir le transport à partir de combien d'articles ? (Optionnel)
                        </label>
                        <input type="number" name="shipping_threshold_qty" value="{{ old('shipping_threshold_qty') }}"
                            min="1" placeholder="Ex : 3"
                            class="w-36 bg-slate-50/80 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">
                    </div>
                </div>

                {{-- 4. Description --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" required rows="4"
                        placeholder="Décrivez votre produit en détail (matériaux, taille, état...)"
                        class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all outline-none">{{ old('description') }}</textarea>
                </div>

                {{-- 5. Upload Photos --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">
                        Photos du produit <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-slate-500 mb-2">
                        Sélectionnez entre 1 et 5 photos (Max 2 MB par photo). La première sera la photo principale.
                    </p>
                    <input type="file" name="images[]" accept="image/*" multiple required
                        class="w-full bg-slate-50/80 border border-slate-200 rounded-xl p-3 text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                </div>

                {{-- Boutons d'action --}}
                <div class="pt-4 space-y-3">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-blue-600/25 transition-all duration-200 text-sm tracking-wide">
                        Créer le produit
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
