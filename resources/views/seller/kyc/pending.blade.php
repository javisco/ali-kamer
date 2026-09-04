@extends('base')

@section('title', 'Vérification KYC en cours')

{{-- Désactivation des sections du layout de base --}}
@section('navbar') @endsection
@section('footer') @endsection

@section('content')
<div class="h-screen w-full flex items-center justify-center bg-slate-100 p-4 sm:p-6 lg:p-8 overflow-hidden">

    <!-- CARTE PRINCIPALE STYLE CONNEXION (Conteneur central sans scroll) -->
    <div class="w-full max-w-5xl h-full max-h-[640px] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row border border-slate-200/80">

        <!-- ================= CÔTÉ GAUCHE : STATUT & INFOS ================= -->
        <div class="w-full lg:w-7/12 h-full p-5 sm:p-6 lg:p-8 flex flex-col justify-between overflow-hidden">

            <!-- 1. En-tête / Logo -->
            <div class="flex items-center justify-between shrink-0">
                <a href="/" class="group flex items-center gap-2 transition-transform active:scale-95">
                    <div class="p-1.5 rounded-xl bg-slate-50 border border-slate-100 shadow-sm">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-8 w-auto object-contain">
                    </div>
                </a>
                <span class="inline-flex items-center gap-1.5 text-[10px] font-black tracking-widest text-[#FFC20E] bg-[#FFC20E]/10 px-3 py-1 rounded-full uppercase border border-[#FFC20E]/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#FFC20E] animate-ping"></span>
                    Examen en cours
                </span>
            </div>

            <!-- 2. Contenu principal compact -->
            <div class="my-auto space-y-3.5">

                <!-- Card de statut avec loader -->
                <div class="rounded-2xl bg-gradient-to-br from-amber-500/10 via-amber-50/40 to-slate-50 p-3.5 border border-[#FFC20E]/30 flex items-center gap-3.5">
                    <div class="relative flex items-center justify-center shrink-0">
                        <div class="w-12 h-12 rounded-2xl bg-[#FFC20E]/20 flex items-center justify-center text-2xl shadow-inner">
                            ⏳
                        </div>
                        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#EA2328] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-[#EA2328]"></span>
                        </span>
                    </div>

                    <div>
                        <h1 class="text-xl font-black text-slate-900 tracking-tight">Dossier en traitement</h1>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            Merci d'avoir soumis vos documents. Notre équipe procède à leur vérification.
                        </p>
                    </div>
                </div>

                <!-- Détails de la demande -->
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-3.5 space-y-2 text-xs">
                    <h2 class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#006837]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Informations sur la demande
                    </h2>

                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-1.5">
                        <span class="text-slate-500 font-medium">Date de soumission</span>
                        <span class="font-bold text-slate-800">{{ $kyc->created_at->format('d/m/Y à H:i') }}</span>
                    </div>

                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-1.5">
                        <span class="text-slate-500 font-medium">Statut du dossier</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#FFC20E]/20 text-amber-900 border border-[#FFC20E]/40">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#EA2328]"></span>
                            En traitement
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Délai d'attente estimé</span>
                        <span class="font-bold text-[#006837]">24 à 48 heures ouvrables</span>
                    </div>
                </div>

                <!-- Box Notification -->
                <div class="rounded-2xl border border-[#006837]/20 bg-[#006837]/5 p-3 flex items-start gap-3">
                    <div class="p-1.5 rounded-xl bg-[#006837] text-white shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-bold text-[#006837] uppercase tracking-wider">Mode d'information</h3>
                        <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                            Vous recevrez une notification directe ainsi qu'un alerte **SMS / WhatsApp** ou e-mail dès la validation.
                        </p>
                    </div>
                </div>

                <!-- Recommendations -->
                <div class="rounded-2xl bg-slate-50 p-3 border border-slate-100">
                    <h3 class="text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Recommandations :</h3>
                    <ul class="grid grid-cols-2 gap-1.5 text-[11px] text-slate-500 font-medium">
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#EA2328] shrink-0"></span>
                            Ne renvoyez pas de dossier
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FFC20E] shrink-0"></span>
                            Vérifiez vos spams
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#006837] shrink-0"></span>
                            Préparez vos produits
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span>
                            Consultez le guide
                        </li>
                    </ul>
                </div>

            </div>

            <!-- 3. Navigation / Footer interne -->
            <div class="shrink-0 pt-2 flex items-center justify-between text-xs text-slate-400 border-t border-slate-100">
                <a href="/" class="inline-flex items-center gap-1.5 font-bold text-slate-500 hover:text-[#006837] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Tableau de bord</span>
                </a>
                <span class="text-[11px]">Ali-Kamer &copy; {{ date('Y') }}</span>
            </div>

        </div>

        <!-- ================= CÔTÉ DROIT : BANNIÈRE INTERNE (STYLE AUTH) ================= -->
        <div class="hidden lg:flex w-5/12 h-full bg-gradient-to-br from-[#004d28] via-[#006837] to-[#8B0000] text-white p-7 flex-col justify-between relative overflow-hidden shrink-0">

            <!-- Arrière-plan lumineux et filigrane -->
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-[#FFC20E]/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-16 w-64 h-64 rounded-full bg-[#EA2328]/25 blur-3xl pointer-events-none"></div>

            <div class="absolute -right-10 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                <img src="{{ asset('images/afrique.png') }}" alt="" class="w-[380px] h-auto object-contain">
            </div>

            <!-- En-tête bannière -->
            <div class="relative z-10">
                <span class="inline-flex items-center gap-1.5 text-[10px] font-black tracking-widest uppercase text-[#FFC20E] bg-black/20 px-3 py-1 rounded-full backdrop-blur-md border border-[#FFC20E]/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#006837]"></span>
                    Plateforme Vendeur
                </span>

                <h2 class="text-2xl font-black mt-5 leading-tight tracking-tight">
                    Votre boutique sera <br>bientôt <span class="text-[#FFC20E]">opérationnelle !</span>
                </h2>
                <p class="text-xs text-emerald-100/90 mt-2 leading-relaxed font-normal">
                    Nos agents examinent vos pièces pour activer votre compte.
                </p>
            </div>

            <!-- Étapes de validation -->
            <div class="relative z-10 space-y-2.5 my-auto">

                <div class="p-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-[#006837] text-white flex items-center justify-center text-xs font-bold shrink-0 shadow">
                        ✓
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-white">1. Soumission du dossier</h3>
                        <p class="text-[10px] text-emerald-100/80">Reçu avec succès.</p>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-black/20 backdrop-blur-md border border-[#FFC20E]/40 flex items-center gap-3 shadow-lg">
                    <div class="w-7 h-7 rounded-lg bg-[#FFC20E] text-slate-900 flex items-center justify-center text-xs font-bold shrink-0 shadow animate-pulse">
                        2
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-[#FFC20E]">2. Examen (En cours)</h3>
                        <p class="text-[10px] text-emerald-100/90">Vérification d'identité.</p>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-white/5 backdrop-blur-md border border-white/10 flex items-center gap-3 opacity-75">
                    <div class="w-7 h-7 rounded-lg bg-[#EA2328] text-white flex items-center justify-center text-xs font-bold shrink-0 shadow">
                        3
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-white">3. Badge Vendeur</h3>
                        <p class="text-[10px] text-emerald-100/80">Activation des ventes.</p>
                    </div>
                </div>

            </div>

            <!-- Pied de bannière -->
            <div class="relative z-10 pt-3 border-t border-white/15 flex items-center justify-between text-xs">
                <div>
                    <p class="text-[10px] text-emerald-100/80 font-medium">Assistance</p>
                    <p class="text-xs font-black text-[#FFC20E]">support@ali-kamer.cm</p>
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-[#006837] border border-white/40"></span>
                    <span class="w-2 h-2 rounded-full bg-[#EA2328] border border-white/40"></span>
                    <span class="w-2 h-2 rounded-full bg-[#FFC20E] border border-white/40"></span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection