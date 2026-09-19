@extends('layouts.admin')

@section('title', 'Examen du dossier KYC - Ali-Kamer')

@section('content')

    <div class="min-h-screen bg-slate-50 py-6 px-3 sm:px-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- 1. EN-TÊTE BANNIÈRE ALI-KAMER -->
            <div class="bg-primary-600 text-white rounded-2xl p-5 sm:p-6 shadow-xs relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-3 py-1 rounded-full text-xs font-semibold text-white mb-2 border border-white/15">
                            <span class="w-2 h-2 rounded-full bg-accent-500"></span>
                            Vérification d'identité
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                            Examen du Dossier KYC
                        </h1>
                        <p class="text-white/80 text-xs sm:text-sm mt-0.5 font-medium">
                            Contrôle de conformité et validation du compte vendeur
                        </p>
                    </div>

                    <div class="sm:text-right bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/10">
                        <h2 class="text-base font-black text-white">
                            {{ $kyc->user->name }}
                        </h2>
                        <p class="text-white/80 text-xs font-medium mt-0.5">
                            Soumis le {{ $kyc->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- 2. SECTION INFORMATIONS & VÉRIFICATION MOMO -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Colonne Gauche : Vendeur & MoMo -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Infos Vendeur -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
                        <h3 class="font-black text-xs uppercase tracking-wider text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informations Vendeur
                        </h3>

                        <div class="space-y-3 text-xs font-medium">
                            <div>
                                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Nom complet</p>
                                <p class="text-slate-900 font-bold text-sm mt-0.5">{{ $kyc->user->name }}</p>
                            </div>

                            <div>
                                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Téléphone principal</p>
                                <p class="text-slate-900 font-bold mt-0.5">{{ $kyc->user->phone }}</p>
                            </div>

                            <div>
                                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Numéro Mobile Money</p>
                                <p class="text-slate-900 font-bold mt-0.5">{{ $kyc->momo_number }}</p>
                            </div>

                            <div>
                                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Date de soumission</p>
                                <p class="text-slate-600 mt-0.5">{{ $kyc->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vérification MoMo Campay -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
                        <h3 class="font-black text-xs uppercase tracking-wider text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <svg class="w-4 h-4 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Vérification Mobile Money
                        </h3>

                        @if ($holderInfo)
                            @php
                                $momoName = $holderInfo['first_name'] ?? '' . ' ' . ($holderInfo['last_name'] ?? '');
                                $momoName = trim($momoName) ?: $holderInfo['name'] ?? 'Non disponible';

                                $match = str_contains(
                                    strtolower($kyc->user->name),
                                    strtolower(explode(' ', $momoName)[0] ?? ''),
                                );
                            @endphp

                            <div class="space-y-2.5 text-xs">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 font-medium">Numéro MoMo</span>
                                    <span class="font-bold text-slate-900">{{ $kyc->momo_number }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 font-medium">Titulaire MoMo</span>
                                    <span class="font-bold {{ $match ? 'text-primary-600' : 'text-danger' }}">
                                        {{ $momoName }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 font-medium">Nom sur le compte</span>
                                    <span class="font-bold text-slate-900">{{ $kyc->user->name }}</span>
                                </div>
                                <div class="flex justify-between items-center border-t border-slate-100 pt-2">
                                    <span class="text-slate-500 font-medium">Correspondance</span>
                                    <span class="font-black {{ $match ? 'text-primary-600' : 'text-danger' }}">
                                        {{ $match ? '✓ Probable' : '⚠ Noms différents' }}
                                    </span>
                                </div>
                            </div>

                            @if (!$match)
                                <div class="bg-danger/10 border border-danger/20 rounded-xl p-3 mt-3 text-[11px] font-bold text-danger flex items-start gap-2">
                                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span>Attention : Le nom MoMo ne correspond pas au compte. Vérifiez manuellement avant de valider.</span>
                                </div>
                            @endif
                        @else
                            <div class="bg-slate-100/80 rounded-xl p-3 text-xs text-slate-500 font-medium">
                                Service d'interrogation MoMo indisponible. Veuillez vérifier les pièces jointes manuellement.
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Colonne Droite : Documents Pièces Jointes -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
                        <h3 class="font-black text-xs uppercase tracking-wider text-slate-900 mb-5 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Documents Fournis
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <!-- CNI Recto -->
                            <div class="space-y-1.5">
                                <p class="text-xs font-bold text-slate-900 flex items-center justify-between">
                                    <span>CNI Recto</span>
                                    <a href="{{ $urls['cni_front_url'] }}" target="_blank" class="text-[10px] text-primary-600 hover:underline">Ouvrir ↗</a>
                                </p>
                                <a href="{{ $urls['cni_front_url'] }}" target="_blank" class="block group overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    <img src="{{ $urls['cni_front_url'] }}" alt="CNI Recto" class="w-full h-48 object-cover group-hover:scale-105 transition duration-200">
                                </a>
                            </div>

                            <!-- CNI Verso -->
                            <div class="space-y-1.5">
                                <p class="text-xs font-bold text-slate-900 flex items-center justify-between">
                                    <span>CNI Verso</span>
                                    <a href="{{ $urls['cni_back_url'] }}" target="_blank" class="text-[10px] text-primary-600 hover:underline">Ouvrir ↗</a>
                                </p>
                                <a href="{{ $urls['cni_back_url'] }}" target="_blank" class="block group overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    <img src="{{ $urls['cni_back_url'] }}" alt="CNI Verso" class="w-full h-48 object-cover group-hover:scale-105 transition duration-200">
                                </a>
                            </div>

                            <!-- Selfie CNI -->
                            <div class="space-y-1.5">
                                <p class="text-xs font-bold text-slate-900 flex items-center justify-between">
                                    <span>Selfie avec CNI</span>
                                    <a href="{{ $urls['selfie_url'] }}" target="_blank" class="text-[10px] text-primary-600 hover:underline">Ouvrir ↗</a>
                                </p>
                                <a href="{{ $urls['selfie_url'] }}" target="_blank" class="block group overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    <img src="{{ $urls['selfie_url'] }}" alt="Selfie" class="w-full h-48 object-cover group-hover:scale-105 transition duration-200">
                                </a>
                            </div>

                            <!-- RCCM -->
                            @if ($kyc->rccm_url)
                                <div class="space-y-1.5">
                                    <p class="text-xs font-bold text-slate-900 flex items-center justify-between">
                                        <span>Registre du commerce (RCCM)</span>
                                        <a href="{{ $urls['rccm_url'] }}" target="_blank" class="text-[10px] text-primary-600 hover:underline">Ouvrir ↗</a>
                                    </p>
                                    <a href="{{ $urls['rccm_url'] }}" target="_blank" class="block group overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                        <img src="{{ $urls['rccm_url'] }}" alt="RCCM" class="w-full h-48 object-cover group-hover:scale-105 transition duration-200">
                                    </a>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>

            <!-- 3. PRISE DE DÉCISION -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
                <h2 class="font-black text-xs uppercase tracking-wider text-slate-900 mb-5 pb-2 border-b border-slate-100">
                    Décision d'évaluation
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Approbation -->
                    <div class="bg-primary-600/5 border border-primary-600/20 rounded-2xl p-5 flex flex-col justify-between">
                        <div>
                            <h3 class="font-black text-sm text-primary-600 uppercase tracking-wider">Approuver le dossier</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1 mb-4">
                                Le vendeur sera validé et pourra commencer à effectuer ses opérations et retraits sur la plateforme.
                            </p>
                        </div>

                        <form action="{{ route('admin.kyc.approve', $kyc) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-xl bg-primary-600 hover:bg-primary-700 active:scale-98 py-3.5 text-xs font-black text-white transition shadow-xs uppercase tracking-wider">
                                ✓ Approuver le dossier
                            </button>
                        </form>
                    </div>

                    <!-- Rejet -->
                    <div class="bg-danger/5 border border-danger/20 rounded-2xl p-5">
                        <h3 class="font-black text-sm text-danger uppercase tracking-wider mb-1">Rejeter le dossier</h3>
                        <p class="text-xs text-slate-500 font-medium mb-3">
                            Indiquez obligatoirement le motif du rejet qui sera transmis au vendeur.
                        </p>

                        <form action="{{ route('admin.kyc.reject', $kyc) }}" method="POST" class="space-y-3">
                            @csrf
                            <textarea name="reason" rows="3" required placeholder="Exemple : Document CNI ilisible ou nom ne correspondant pas au compte..."
                                class="w-full rounded-xl border border-slate-200 p-3 text-xs font-semibold text-slate-900 focus:outline-none focus:border-danger focus:ring-2 focus:ring-danger/20 shadow-xs"></textarea>

                            <button type="submit"
                                class="w-full rounded-xl bg-danger hover:bg-[#b8040f] active:scale-98 py-3.5 text-xs font-black text-white transition shadow-xs uppercase tracking-wider">
                                ✗ Rejeter le dossier
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection