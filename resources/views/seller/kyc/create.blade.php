@extends('base')

@section('title', 'Vérification KYC')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center px-4 py-10 bg-slate-50/50">
    <div class="w-full max-w-3xl">

        {{-- Alerte d'erreur --}}
        @if (session('fail'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('fail') }}</span>
            </div>
        @endif

        {{-- Alerte de succès --}}
        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">

            <!-- En-tête avec Gradient -->
            <div class="bg-gradient-to-br from-blue-900 via-blue-700 to-blue-600 px-6 pt-8 pb-6 text-center relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                <div class="absolute -bottom-12 -left-12 w-32 h-32 rounded-full bg-cyan-300/10 blur-xl"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center p-3 bg-white/10 backdrop-blur-md rounded-2xl shadow-xl mb-3 border border-white/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-white">Vérification de votre identité</h1>
                    <p class="text-xs text-blue-100 mt-1 max-w-lg mx-auto">
                        Pour sécuriser la plateforme et activer votre boutique seller, veuillez fournir des documents valides.
                    </p>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">

                <!-- Bloc d'information -->
                <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-4 text-xs text-slate-700 leading-relaxed">
                    <div class="flex items-center gap-2 font-bold text-blue-900 mb-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Consignes importantes pour la validation</span>
                    </div>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-slate-600">
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                            Photos nettes et bien éclairées
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                            Les 4 coins de la pièce visibles
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                            Visage & CNI visibles sur le selfie
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                            Traitement sous <strong>24h à 48h</strong>
                        </li>
                    </ul>
                </div>

                <!-- Formulaire -->
                <form action="{{ route('seller.kyc.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <!-- Grid pour Recto et Verso -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- CNI Recto -->
                        <div>
                            <label for="cni_front_url" class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-slate-700">
                                CNI - Recto <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="cni_front_url" id="cni_front_url" accept="image/*"
                                class="w-full text-xs text-slate-500 bg-slate-50/50 rounded-2xl border p-2.5 
                                file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold 
                                file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition
                                @error('cni_front_url') border-red-500 focus:ring-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror
                                focus:outline-none">
                            @error('cni_front_url')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CNI Verso -->
                        <div>
                            <label for="cni_back_url" class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-slate-700">
                                CNI - Verso <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="cni_back_url" id="cni_back_url" accept="image/*"
                                class="w-full text-xs text-slate-500 bg-slate-50/50 rounded-2xl border p-2.5 
                                file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold 
                                file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition
                                @error('cni_back_url') border-red-500 focus:ring-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror
                                focus:outline-none">
                            @error('cni_back_url')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Selfie avec CNI -->
                    <div>
                        <label for="selfie_url" class="block mb-1 text-xs font-semibold uppercase tracking-wider text-slate-700">
                            Selfie avec votre CNI <span class="text-red-500">*</span>
                        </label>
                        <p class="text-[11px] text-slate-400 mb-1.5">Tenez votre CNI près de votre visage, les détails doivent rester lisibles.</p>
                        <input type="file" name="selfie_url" id="selfie_url" accept="image/*"
                            class="w-full text-xs text-slate-500 bg-slate-50/50 rounded-2xl border p-2.5 
                            file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold 
                            file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition
                            @error('selfie_url') border-red-500 focus:ring-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror
                            focus:outline-none">
                        @error('selfie_url')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RCCM / Carte contribuable -->
                    <div>
                        <label for="rccm_url" class="block mb-1 text-xs font-semibold uppercase tracking-wider text-slate-700">
                            RCCM ou Carte de contribuable <span class="text-slate-400 font-normal lowercase">(facultatif)</span>
                        </label>
                        <p class="text-[11px] text-slate-400 mb-1.5">Format accepté : Image ou document PDF.</p>
                        <input type="file" name="rccm_url" id="rccm_url" accept="image/*,application/pdf"
                            class="w-full text-xs text-slate-500 bg-slate-50/50 rounded-2xl border p-2.5 
                            file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold 
                            file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition
                            @error('rccm_url') border-red-500 focus:ring-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror
                            focus:outline-none">
                        @error('rccm_url')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bouton Submit -->
                    <button type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-blue-700 to-blue-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:from-blue-800 hover:to-blue-700 active:scale-[0.99] mt-4 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Soumettre mon dossier</span>
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection