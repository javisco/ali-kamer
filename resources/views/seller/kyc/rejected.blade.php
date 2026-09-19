@extends('base')

@section('title', 'Vérification KYC Refusée')

{{-- Désactivation des sections du layout de base --}}
@section('navbar') @endsection
@section('footer') @endsection

@section('content')
<div class="h-screen w-full flex items-center justify-center bg-slate-100 p-4 sm:p-6 lg:p-8 overflow-hidden">

    <!-- CARTE PRINCIPALE STYLE CONNEXION (Zero Scroll) -->
    <div class="w-full max-w-5xl h-full max-h-[640px] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row border border-slate-200/80">

        <!-- ================= CÔTÉ GAUCHE : MOTIF DU REJET & ACTION ================= -->
        <div class="w-full lg:w-7/12 h-full p-5 sm:p-6 lg:p-8 flex flex-col justify-between overflow-hidden">

            <!-- 1. En-tête / Logo -->
            <div class="flex items-center justify-between shrink-0">
                <a href="/" class="group flex items-center gap-2 transition-transform active:scale-95">
                    <div class="p-1.5 rounded-xl bg-slate-50 border border-slate-100 shadow-sm">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-8 w-auto object-contain">
                    </div>
                </a>
                <span class="inline-flex items-center gap-1.5 text-[10px] font-black tracking-widest text-danger bg-danger/10 px-3 py-1 rounded-full uppercase border border-danger/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                    Dossier Non Validé
                </span>
            </div>

            <!-- Flash Session Fail -->
            @if (session('fail'))
                <div class="my-1 rounded-xl border border-danger-200 bg-danger-50 p-2.5 text-xs text-danger-700 font-medium">
                    {{ session('fail') }}
                </div>
            @endif

            <!-- 2. Contenu principal compact -->
            <div class="my-auto space-y-3.5">

                <!-- Header Statut Rejet -->
                <div class="rounded-2xl bg-gradient-to-br from-danger/10 via-danger-50/50 to-slate-50 p-3.5 border border-danger/30 flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-danger/15 flex items-center justify-center text-2xl shrink-0 shadow-inner">
                        ⚠️
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-slate-900 tracking-tight">Vérification refusée</h1>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            Bonjour <strong class="text-slate-700">{{ $user->name }}</strong>, votre demande n'a pas pu être approuvée.
                        </p>
                    </div>
                </div>

                <!-- Block Motif du rejet -->
                <div class="rounded-2xl border border-danger-200 bg-danger-50/60 p-3.5 space-y-1.5">
                    <h2 class="text-[10px] font-black uppercase tracking-wider text-danger flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Motif du rejet communiqué
                    </h2>
                    <p class="text-xs font-semibold text-slate-800 leading-relaxed bg-white/80 p-2.5 rounded-xl border border-danger-100 shadow-sm">
                        "{{ $user->kycDocument->rejection_reason ?? 'Les documents fournis ne respectent pas les critères de lisibilité ou de conformité.' }}"
                    </p>
                </div>

                <!-- Recommandations / Consignes pour corriger -->
                <div class="rounded-2xl bg-slate-50 p-3.5 border border-slate-200/80">
                    <h3 class="text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Conseils pour la nouvelle soumission :</h3>
                    <ul class="grid grid-cols-2 gap-1.5 text-[11px] text-slate-600 font-medium">
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-600 shrink-0"></span>
                            Photos nettes et bien éclairées
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-warning shrink-0"></span>
                            Document lisible et complet
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-danger shrink-0"></span>
                            CNI / Passeport valide
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span>
                            Informations identiques au compte
                        </li>
                    </ul>
                </div>

                <!-- Bouton d'action principal -->
                <div class="pt-1">
                    <a href="{{ route('seller.kyc.create') }}"
                       class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-primary-600 hover:bg-primary-700 px-6 py-3 text-xs font-black uppercase tracking-wider text-white shadow-lg shadow-primary-600/20 transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Soumettre un nouveau dossier
                    </a>
                </div>

            </div>

            <!-- 3. Footer interne -->
            <div class="shrink-0 pt-2 flex items-center justify-between text-xs text-slate-400 border-t border-slate-100">
                <a href="/" class="inline-flex items-center gap-1.5 font-bold text-slate-500 hover:text-primary-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Tableau de bord</span>
                </a>
                <span class="text-[11px]">Ali-Kamer &copy; {{ date('Y') }}</span>
            </div>

        </div>

        <!-- ================= CÔTÉ DROIT : BANNIÈRE ASSISTANCE ================= -->
        <div class="hidden lg:flex w-5/12 h-full bg-gradient-to-br from-danger-800 via-danger to-primary-800 text-white p-7 flex-col justify-between relative overflow-hidden shrink-0">

            <!-- Arrière-plan lumineux et filigrane -->
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-warning/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-16 w-64 h-64 rounded-full bg-primary-600/30 blur-3xl pointer-events-none"></div>

            <div class="absolute -right-10 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                <img src="{{ asset('images/afrique.png') }}" alt="" class="w-[380px] h-auto object-contain">
            </div>

            <!-- En-tête bannière -->
            <div class="relative z-10">
                <span class="inline-flex items-center gap-1.5 text-[10px] font-black tracking-widest uppercase text-warning bg-black/20 px-3 py-1 rounded-full backdrop-blur-md border border-warning/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                    Support Vendeur
                </span>

                <h2 class="text-2xl font-black mt-5 leading-tight tracking-tight">
                    Besoin d'aide pour <br>votre <span class="text-warning">document ?</span>
                </h2>
                <p class="text-xs text-danger-100/90 mt-2 leading-relaxed font-normal">
                    Notre équipe d'assistance est à votre disposition si vous avez des questions sur le motif de rejet.
                </p>
            </div>

            <!-- Étapes d'assistance -->
            <div class="relative z-10 space-y-2.5 my-auto">

                <div class="p-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-black/30 text-warning flex items-center justify-center text-xs font-bold shrink-0 shadow">
                        1
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-white">Lisez attentivement le motif</h3>
                        <p class="text-[10px] text-danger-100/80">Identifiez la pièce à corriger.</p>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-black/30 text-warning flex items-center justify-center text-xs font-bold shrink-0 shadow">
                        2
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-white">Reprenez des photos nettes</h3>
                        <p class="text-[10px] text-danger-100/80">Évitez les reflets et les flous.</p>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-black/20 backdrop-blur-md border border-primary-600/40 flex items-center gap-3 shadow-lg">
                    <div class="w-7 h-7 rounded-lg bg-primary-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow">
                        3
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-white">Renvoyez votre formulaire</h3>
                        <p class="text-[10px] text-success-100/90">Traitement prioritaire garanti.</p>
                    </div>
                </div>

            </div>

            <!-- Pied de bannière -->
            <div class="relative z-10 pt-3 border-t border-white/15 flex items-center justify-between text-xs">
                <div>
                    <p class="text-[10px] text-danger-100/80 font-medium">Contacter le support</p>
                    <p class="text-xs font-black text-warning">support@ali-kamer.cm</p>
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-primary-600 border border-white/40"></span>
                    <span class="w-2 h-2 rounded-full bg-danger border border-white/40"></span>
                    <span class="w-2 h-2 rounded-full bg-warning border border-white/40"></span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection