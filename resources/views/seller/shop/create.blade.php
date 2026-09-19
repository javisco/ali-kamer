@extends('layouts.seller')

@section('title', 'Créer votre boutique - Ali-Kamer')

@section('content')

    {{-- Animation fluide --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.35s ease-out forwards; }
    </style>

    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6">

        <div class="animate-fade-in space-y-6">

            {{-- 1. EN-TÊTE / BANNIÈRE --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-primary-600 via-primary-600 to-primary-700 text-white rounded-3xl p-6 sm:p-8 shadow-xl">
                <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 text-center sm:text-left">
                    <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold text-white mb-3 border border-white/20">
                        <span class="w-2 h-2 rounded-full bg-warning animate-pulse"></span>
                        Étape 1 sur 2 • Informations de la boutique
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Créer votre boutique
                    </h1>
                    <p class="text-primary-100 text-xs sm:text-sm mt-1 max-w-lg font-medium">
                        Renseignez les détails de votre commerce avant de passer à l'étape de vérification.
                    </p>
                </div>
            </div>

            {{-- 2. CARTE FORMULAIRE --}}
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm">

                {{-- Alertes d'erreurs globales --}}
                @if ($errors->any())
                    <div class="bg-danger-50 border border-danger-200 text-danger-700 px-4 py-3.5 rounded-2xl mb-6 text-sm">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <svg class="w-4 h-4 text-danger shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Veuillez corriger les erreurs suivantes :</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-xs pl-1 text-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('seller.shop.store') }}" class="space-y-5">
                    @csrf

                    <!-- Nom de la boutique -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nom de la boutique <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            placeholder="Ex: Electroménager Express"
                            class="w-full bg-slate-50 border @error('name') border-danger bg-danger-50/30 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        @error('name')
                            <p class="text-xs text-danger mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grille 2 Colonnes : Ville & Téléphone -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Ville -->
                        <div>
                            <label for="city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Ville <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" required
                                placeholder="Ex: Douala, Yaoundé..."
                                class="w-full bg-slate-50 border @error('city') border-danger bg-danger-50/30 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                            @error('city')
                                <p class="text-xs text-danger mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Téléphone <span class="text-danger">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                placeholder="Ex: 6xx xxx xxx"
                                class="w-full bg-slate-50 border @error('phone') border-danger bg-danger-50/30 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                            @error('phone')
                                <p class="text-xs text-danger mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Adresse -->
                    <div>
                        <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Adresse exacte / Quartier <span class="text-slate-400 font-normal capitalize">(Facultatif)</span>
                        </label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}"
                            placeholder="Ex: Akwa, Rue Deido"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Description de la boutique <span class="text-slate-400 font-normal capitalize">(Facultatif)</span>
                        </label>
                        <textarea id="description" name="description" rows="3"
                            placeholder="Décrivez en quelques mots ce que vous vendez..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition resize-none">{{ old('description') }}</textarea>
                    </div>

                    <!-- Bouton de Soumission -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-bold py-3.5 px-6 rounded-xl text-sm shadow-lg shadow-primary-600/25 transition-all duration-200">
                            <span>Continuer vers la vérification d'identité</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection