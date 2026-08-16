@extends('base')
@section('title', 'Nouveau tutoriel')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-2xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Nouveau tutoriel</h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tutorials.store') }}"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-4">

            {{-- Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Type <span class="text-red-500">*</span>
                </label>
                <select name="type" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                               focus:ring-2 focus:ring-indigo-500">
                    <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>
                        🎬 Vidéo
                    </option>
                    <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>
                        📖 Texte
                    </option>
                </select>
            </div>

            {{-- Pour qui --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Destiné à <span class="text-red-500">*</span>
                </label>
                <select name="role_target" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                               focus:ring-2 focus:ring-indigo-500">
                    <option value="buyer"  {{ old('role_target') === 'buyer'  ? 'selected' : '' }}>
                        Acheteurs
                    </option>
                    <option value="seller" {{ old('role_target') === 'seller' ? 'selected' : '' }}>
                        Vendeurs
                    </option>
                    <option value="all"    {{ old('role_target') === 'all'    ? 'selected' : '' }}>
                        Tous
                    </option>
                </select>
            </div>
        </div>

        {{-- Catégorie --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Catégorie <span class="text-red-500">*</span>
            </label>
            <select name="category" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                           focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Choisir --</option>
                @foreach($categories as $value => $label)
                    <option value="{{ $value }}" {{ old('category') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Titre --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Titre <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title') }}" required
                   placeholder="Ex: Comment passer sa première commande"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500">
        </div>

        {{-- URL Vidéo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                URL de la vidéo
                <span class="text-gray-400 font-normal">(YouTube ou autre)</span>
            </label>
            <input type="url" name="video_url" value="{{ old('video_url') }}"
                   placeholder="https://www.youtube.com/watch?v=..."
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500">
            <p class="text-xs text-gray-400 mt-1">
                Collez simplement l'URL YouTube — l'embed est géré automatiquement.
            </p>
        </div>

        {{-- Thumbnail --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                URL miniature
                <span class="text-gray-400 font-normal">(optionnel)</span>
            </label>
            <input type="url" name="thumbnail_url" value="{{ old('thumbnail_url') }}"
                   placeholder="https://..."
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500">
        </div>

        {{-- Durée --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Durée <span class="text-gray-400 font-normal">(minutes)</span>
            </label>
            <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}"
                   min="1" placeholder="Ex: 5"
                   class="w-32 border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500">
        </div>

        {{-- Ordre --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Ordre d'affichage
            </label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                   min="0"
                   class="w-32 border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500">
            <p class="text-xs text-gray-400 mt-1">0 = premier affiché</p>
        </div>

        {{-- Contenu texte --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Contenu texte
                <span class="text-gray-400 font-normal">(optionnel — complément à la vidéo)</span>
            </label>
            <textarea name="content" rows="6"
                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                             focus:ring-2 focus:ring-indigo-500"
                      placeholder="Description, étapes, conseils...">{{ old('content') }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" name="publish" value="0"
                    class="flex-1 border-2 border-gray-300 text-gray-700 font-bold
                           py-3 rounded-xl transition hover:bg-gray-50">
                Sauvegarder en brouillon
            </button>
            <button type="submit" name="publish" value="1"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                           py-3 rounded-xl transition">
                Publier maintenant
            </button>
        </div>

        <a href="{{ route('admin.tutorials.index') }}"
           class="block text-center text-sm text-gray-400 hover:underline">
            Annuler
        </a>
    </form>
</div>
</div>
@endsection