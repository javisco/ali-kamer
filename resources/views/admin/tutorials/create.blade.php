@extends('layouts.admin')

@section('title', 'Nouveau tutoriel - Ali-Kamer')

@section('content')
<div class="min-h-screen bg-[#F7F7F2] py-6 px-3 sm:px-6">
    <div class="max-w-2xl mx-auto space-y-6">

        <!-- 1. BANNIÈRE EN-TÊTE ALI-KAMER -->
        <div class="bg-[#016837] text-white rounded-2xl p-5 sm:p-6 shadow-xs relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-3 py-1 rounded-full text-xs font-semibold text-white mb-2 border border-white/15">
                        <span class="w-2 h-2 rounded-full bg-[#F9A01B]"></span>
                        Centre d'Aide & Formations
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                        Nouveau Tutoriel
                    </h1>
                    <p class="text-white/80 text-xs sm:text-sm mt-0.5 font-medium">
                        Rédigez ou ajoutez une vidéo d'accompagnement pour les utilisateurs.
                    </p>
                </div>

                <div>
                    <a href="{{ route('admin.tutorials.index') }}" 
                       class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold px-3.5 py-2 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Retour</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. MESSAGES D'ERREURS -->
        @if($errors->any())
            <div class="bg-[#E30613]/10 border border-[#E30613]/20 text-[#E30613] rounded-xl p-4 text-xs font-bold space-y-1 shadow-xs">
                @foreach($errors->all() as $error)
                    <p class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $error }}</span>
                    </p>
                @endforeach
            </div>
        @endif

        <!-- 3. FORMULAIRE -->
        <form method="POST" action="{{ route('admin.tutorials.store') }}"
              class="bg-white rounded-2xl border border-gray-200 shadow-xs p-5 sm:p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Type --}}
                <div>
                    <label class="block text-xs font-bold text-[#0a1b12] uppercase tracking-wider mb-1.5">
                        Type <span class="text-[#E30613]">*</span>
                    </label>
                    <select name="type" required
                            class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs bg-white">
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
                    <label class="block text-xs font-bold text-[#0a1b12] uppercase tracking-wider mb-1.5">
                        Destiné à <span class="text-[#E30613]">*</span>
                    </label>
                    <select name="role_target" required
                            class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs bg-white">
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
                <label class="block text-xs font-bold text-[#0a1b12] uppercase tracking-wider mb-1.5">
                    Catégorie <span class="text-[#E30613]">*</span>
                </label>
                <select name="category" required
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs bg-white">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach($categories as $value => $label)
                        <option value="{{ $value }}" {{ old('category') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Titre --}}
            <div>
                <label class="block text-xs font-bold text-[#0a1b12] uppercase tracking-wider mb-1.5">
                    Titre <span class="text-[#E30613]">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="Ex: Comment passer sa première commande"
                       class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs">
            </div>

            {{-- URL Vidéo --}}
            <div>
                <label class="block text-xs font-bold text-[#0a1b12] uppercase tracking-wider mb-1">
                    URL de la vidéo
                    <span class="text-gray-400 font-normal lowercase">(YouTube ou autre)</span>
                </label>
                <input type="url" name="video_url" value="{{ old('video_url') }}"
                       placeholder="https://www.youtube.com/watch?v=..."
                       class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs">
                <p class="text-[11px] text-gray-400 font-medium mt-1">
                    Collez simplement l'URL YouTube — le lecteur est généré automatiquement.
                </p>
            </div>

            {{-- Thumbnail --}}
            <div>
                <label class="block text-xs font-bold text-[#0a1b12] uppercase tracking-wider mb-1">
                    URL de la miniature
                    <span class="text-gray-400 font-normal lowercase">(optionnel)</span>
                </label>
                <input type="url" name="thumbnail_url" value="{{ old('thumbnail_url') }}"
                       placeholder="https://..."
                       class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs">
            </div>

            {{-- Durée & Ordre --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#0a1b12] uppercase tracking-wider mb-1">
                        Durée <span class="text-gray-400 font-normal lowercase">(minutes)</span>
                    </label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}"
                           min="1" placeholder="Ex: 5"
                           class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0a1b12] uppercase tracking-wider mb-1">
                        Ordre d'affichage
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                           min="0"
                           class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs">
                    <p class="text-[11px] text-gray-400 font-medium mt-1">0 = affiché en premier</p>
                </div>
            </div>

            {{-- Contenu texte --}}
            <div>
                <label class="block text-xs font-bold text-[#0a1b12] uppercase tracking-wider mb-1">
                    Contenu texte
                    <span class="text-gray-400 font-normal lowercase">(optionnel — complément)</span>
                </label>
                <textarea name="content" rows="5"
                          class="w-full border border-gray-200 rounded-xl p-3.5 text-xs font-medium text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs"
                          placeholder="Description, étapes détaillées, conseils pratiques...">{{ old('content') }}</textarea>
            </div>

            <!-- BOUTONS D'ACTION -->
            <div class="flex flex-col sm:flex-row gap-3 pt-3">
                <button type="submit" name="publish" value="0"
                        class="flex-1 bg-white hover:bg-gray-50 text-[#0a1b12] font-black py-3 rounded-xl border border-gray-300 transition text-xs shadow-xs uppercase tracking-wider">
                    Sauvegarder en brouillon
                </button>
                <button type="submit" name="publish" value="1"
                        class="flex-1 bg-[#F9A01B] hover:bg-[#e08e14] active:scale-98 text-[#0a1b12] font-black py-3 rounded-xl transition text-xs shadow-xs uppercase tracking-wider">
                    Publier maintenant
                </button>
            </div>

            <a href="{{ route('admin.tutorials.index') }}"
               class="block text-center text-xs font-bold text-gray-400 hover:text-[#0a1b12] transition">
                Annuler
            </a>
        </form>
    </div>
</div>
@endsection