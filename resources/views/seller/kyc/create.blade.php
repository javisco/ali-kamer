@extends('base')

@section('title', 'Vérification KYC')

{{-- On neutralise la navbar et le footer hérités du layout base si nécessaire --}}
@section('navbar') @endsection
@section('footer') @endsection

@section('content')
<div class="h-screen w-full flex bg-slate-50 overflow-hidden">

    <!-- ================= COLONNE GAUCHE : FORMULAIRE DE SOUMISSION ================= -->
    <div class="w-full lg:w-1/2 h-full flex flex-col justify-between p-6 sm:p-8 lg:p-10 xl:p-12 bg-white overflow-y-auto min-h-0 shadow-2xl z-10">

        <!-- 1. En-tête / Logo -->
        <div class="flex items-center justify-between shrink-0 mb-2">
            <a href="/" class="group flex items-center gap-3 transition-transform active:scale-95">
                <div class="p-2 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm group-hover:border-slate-200">
                    <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-10 sm:h-11 w-auto object-contain">
                </div>
            </a>
            <span class="text-[11px] font-black tracking-widest text-[#006837] bg-[#006837]/10 px-3.5 py-1.5 rounded-full uppercase border border-[#006837]/20">
                Espace Vendeur Partner
            </span>
        </div>

        <!-- 2. Section Principale / Formulaire -->
        <div class="my-auto max-w-lg w-full mx-auto py-2">

            <!-- Titre & Sous-titre -->
            <div class="mb-4">
                <div class="flex items-center gap-2 mb-1">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#EA2328] animate-pulse"></span>
                    <span class="text-xs font-bold text-[#EA2328] uppercase tracking-wider">Étape obligatoire</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Vérification d'identité (KYC)</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium leading-relaxed">
                    Soumettez vos pièces justificatives pour activer votre boutique et recevoir vos paiements en toute sécurité.
                </p>
            </div>

            <!-- Messages d'Alerte -->
            @if (session('fail'))
                <div class="mb-3 rounded-2xl border border-[#EA2328]/30 bg-[#EA2328]/5 p-3 text-xs sm:text-sm text-[#EA2328] shadow-sm flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0 text-[#EA2328]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('fail') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-3 rounded-2xl border border-[#006837]/30 bg-[#006837]/5 p-3 text-xs sm:text-sm text-[#006837] shadow-sm flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0 text-[#006837]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Formulaire KYC -->
            <form action="{{ route('seller.kyc.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                @csrf

                <!-- Grid pour CNI Recto et Verso -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- CNI Recto -->
                    <div>
                        <label for="cni_front_url" class="block mb-1 text-xs font-bold uppercase tracking-wider text-slate-700">
                            CNI - Recto <span class="text-[#EA2328]">*</span>
                        </label>
                        <input type="file" name="cni_front_url" id="cni_front_url" accept="image/*" required
                            class="w-full text-xs text-slate-500 bg-slate-50/60 rounded-2xl border p-2
                            file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold 
                            file:bg-[#006837] file:text-white hover:file:bg-[#00522b] transition cursor-pointer
                            @error('cni_front_url') border-[#EA2328] @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                        @error('cni_front_url')
                            <p class="mt-1 text-xs text-[#EA2328] font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CNI Verso -->
                    <div>
                        <label for="cni_back_url" class="block mb-1 text-xs font-bold uppercase tracking-wider text-slate-700">
                            CNI - Verso <span class="text-[#EA2328]">*</span>
                        </label>
                        <input type="file" name="cni_back_url" id="cni_back_url" accept="image/*" required
                            class="w-full text-xs text-slate-500 bg-slate-50/60 rounded-2xl border p-2
                            file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold 
                            file:bg-[#006837] file:text-white hover:file:bg-[#00522b] transition cursor-pointer
                            @error('cni_back_url') border-[#EA2328] @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                        @error('cni_back_url')
                            <p class="mt-1 text-xs text-[#EA2328] font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Selfie avec CNI -->
                <div>
                    <label for="selfie_url" class="block mb-0.5 text-xs font-bold uppercase tracking-wider text-slate-700">
                        Selfie avec votre CNI <span class="text-[#EA2328]">*</span>
                    </label>
                    <p class="text-[11px] text-slate-400 mb-1">Tenez votre document lisiblement à côté de votre visage.</p>
                    <input type="file" name="selfie_url" id="selfie_url" accept="image/*" required
                        class="w-full text-xs text-slate-500 bg-slate-50/60 rounded-2xl border p-2
                        file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold 
                        file:bg-[#006837] file:text-white hover:file:bg-[#00522b] transition cursor-pointer
                        @error('selfie_url') border-[#EA2328] @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                    @error('selfie_url')
                        <p class="mt-1 text-xs text-[#EA2328] font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RCCM / Carte contribuable -->
                <div>
                    <label for="rccm_url" class="block mb-0.5 text-xs font-bold uppercase tracking-wider text-slate-700">
                        RCCM ou Carte de contribuable <span class="text-slate-400 font-normal normal-case">(facultatif)</span>
                    </label>
                    <p class="text-[11px] text-slate-400 mb-1">Formats acceptés : JPG, PNG ou PDF (max 5MB).</p>
                    <input type="file" name="rccm_url" id="rccm_url" accept="image/*,application/pdf"
                        class="w-full text-xs text-slate-500 bg-slate-50/60 rounded-2xl border p-2
                        file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold 
                        file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 transition cursor-pointer
                        @error('rccm_url') border-[#EA2328] @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                    @error('rccm_url')
                        <p class="mt-1 text-xs text-[#EA2328] font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Note de confidentialité -->
                <div class="rounded-2xl bg-slate-50 p-3 border border-slate-100 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-[#006837] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <p class="text-[11px] text-slate-500 leading-tight">
                        Vos données personnelles sont chiffrées et uniquement destinées à la vérification interne de votre boutique.
                    </p>
                </div>

                <!-- Bouton Submit (Vert avec surbrillance Jaune/Rouge) -->
                <button type="submit"
                    class="w-full rounded-2xl bg-[#006837] py-3.5 text-sm font-extrabold text-white shadow-lg shadow-[#006837]/25 transition-all hover:bg-[#00522b] active:scale-[0.99] flex items-center justify-center gap-2 group mt-2">
                    <span>Soumettre mon dossier KYC</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1 text-[#FFC20E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Navigation Retour -->
        <div class="shrink-0 pt-2 flex items-center justify-between text-xs text-slate-400">
            <a href="/" class="inline-flex items-center gap-1.5 font-bold text-slate-500 hover:text-[#006837] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour à l'accueil</span>
            </a>
            <span>Ali-Kamer &copy; {{ date('Y') }}</span>
        </div>

    </div>

    <!-- ================= COLONNE DROITE : BANNIÈRE NATIONALE VERT-ROUGE-JAUNE ================= -->
    <div class="hidden lg:flex w-1/2 h-full bg-gradient-to-br from-[#004d28] via-[#006837] to-[#8B0000] text-white p-10 xl:p-14 flex-col justify-between relative overflow-hidden shrink-0">

        <!-- Éléments visuels d'arrière-plan (Drapeau / Effets Lumineux) -->
        <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-[#FFC20E]/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-[#EA2328]/20 blur-3xl pointer-events-none"></div>

        <!-- Logo Ali-Kamer géant en filigrane -->
        <div class="absolute -right-16 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
            <img src="{{ asset('images/afrique.png') }}" alt="" class="w-[520px] h-auto object-contain">
        </div>

        <!-- Section supérieure : Badge & Logo -->
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-2 text-xs font-black tracking-widest uppercase text-[#FFC20E] bg-black/20 px-4 py-1.5 rounded-full backdrop-blur-md border border-[#FFC20E]/30">
                    <span class="w-2 h-2 rounded-full bg-[#EA2328]"></span>
                    Sécurité & Conformité
                </span>
                
                <div class="p-2.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-xl">
                    <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-10 w-auto object-contain drop-shadow">
                </div>
            </div>

            <h2 class="text-3xl xl:text-4xl font-black mt-8 leading-tight tracking-tight">
                Activez votre boutique <br>en toute <span class="text-[#FFC20E]">confiance.</span>
            </h2>
            <p class="text-sm text-emerald-100/90 mt-3 leading-relaxed max-w-md font-normal">
                Afin de garantir la sécurité des acheteurs et la traçabilité des ventes sur la plateforme Ali-Kamer, la vérification KYC est obligatoire pour tous nos vendeurs.
            </p>
        </div>

        <!-- Section centrale : Étapes clés -->
        <div class="relative z-10 space-y-4 my-auto max-w-md">
            
            <div class="flex items-start gap-4 p-3.5 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 hover:bg-white/10 transition">
                <div class="w-10 h-10 rounded-xl bg-[#FFC20E] text-slate-900 font-black flex items-center justify-center shrink-0 shadow-md">
                    01
                </div>
                <div>
                    <h3 class="font-bold text-sm text-white">Photos claires et lisibles</h3>
                    <p class="text-xs text-emerald-100/80 mt-0.5 leading-snug">Évitez les reflets et le flou sur les pièces d'identité.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-3.5 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 hover:bg-white/10 transition">
                <div class="w-10 h-10 rounded-xl bg-[#EA2328] text-white font-black flex items-center justify-center shrink-0 shadow-md">
                    02
                </div>
                <div>
                    <h3 class="font-bold text-sm text-white">Document complet</h3>
                    <p class="text-xs text-emerald-100/80 mt-0.5 leading-snug">Les 4 coins de la carte nationale d'identité doivent figurer sur la photo.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-3.5 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 hover:bg-white/10 transition">
                <div class="w-10 h-10 rounded-xl bg-[#006837] border border-emerald-400/30 text-white font-black flex items-center justify-center shrink-0 shadow-md">
                    03
                </div>
                <div>
                    <h3 class="font-bold text-sm text-white">Selfie de confirmation</h3>
                    <p class="text-xs text-emerald-100/80 mt-0.5 leading-snug">Tenez la CNI à côté de votre visage pour validation immédiate.</p>
                </div>
            </div>

        </div>

        <!-- Section inférieure : Pied de page banner -->
        <div class="relative z-10 pt-4 border-t border-white/15 flex items-center justify-between">
            <div>
                <p class="text-xs text-emerald-100/90 font-medium">Temps moyen de traitement</p>
                <p class="text-sm font-black text-[#FFC20E]">24h à 48h ouvrées</p>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-[#006837] border border-white/40"></span>
                <span class="w-3 h-3 rounded-full bg-[#EA2328] border border-white/40"></span>
                <span class="w-3 h-3 rounded-full bg-[#FFC20E] border border-white/40"></span>
            </div>
        </div>

    </div>

</div>
@endsection