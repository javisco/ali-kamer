@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    {{-- =========================================================
        ALI-KAMER ADMIN — DASHBOARD
        Design : Vert / Rouge / Jaune — Plus Jakarta Sans
    ========================================================== --}}

    <div class="min-h-screen bg-[#FAF9F6] font-['Plus_Jakarta_Sans']">

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6">

            {{-- =========================================================
                BARRE IDENTITAIRE ALI-KAMER
            ========================================================== --}}
            <div class="h-1.5 rounded-full overflow-hidden mb-5 flex">
                <div class="w-1/3 bg-[#00843D]"></div>
                <div class="w-1/3 bg-[#FCD116]"></div>
                <div class="w-1/3 bg-[#CE1126]"></div>
            </div>


            {{-- =========================================================
                HEADER
            ========================================================== --}}
            <div class="relative overflow-hidden rounded-[24px] mb-6 bg-[#004D2A] shadow-xl shadow-green-950/10">

                <div class="absolute -top-24 -right-20 w-72 h-72 rounded-full bg-[#00843D]/30 blur-3xl"></div>
                <div class="absolute -bottom-28 left-1/3 w-72 h-72 rounded-full bg-[#FCD116]/10 blur-3xl"></div>

                <div class="relative px-5 sm:px-7 py-6 sm:py-7">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                        <div>

                            <div
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                                bg-white/10 border border-white/15 text-green-50 text-[11px] font-bold mb-3">

                                <span class="relative flex w-2 h-2">
                                    <span
                                        class="absolute inline-flex w-full h-full rounded-full bg-[#FCD116] opacity-60 animate-ping"></span>
                                    <span class="relative inline-flex w-2 h-2 rounded-full bg-[#FCD116]"></span>
                                </span>

                                Administration Ali-Kamer
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                                Tableau de bord
                            </h1>

                            <p class="mt-2 text-xs sm:text-sm text-green-100/80 max-w-2xl leading-relaxed">
                                Supervisez l’activité de la plateforme, les transactions,
                                les utilisateurs et les opérations logistiques.
                            </p>

                        </div>

                        <div class="flex flex-wrap items-center gap-3">

                            <div
                                class="inline-flex items-center gap-3 px-4 py-3 rounded-2xl
                                bg-white/10 border border-white/15 backdrop-blur-sm text-white">

                                <div class="relative">
                                    <span class="absolute inset-0 rounded-full bg-[#FCD116] animate-ping opacity-40"></span>
                                    <span class="relative block w-2.5 h-2.5 rounded-full bg-[#FCD116]"></span>
                                </div>

                                <div>
                                    <p class="text-[10px] text-green-100/70 uppercase tracking-wider font-bold">
                                        État du système
                                    </p>

                                    <p class="text-xs font-black mt-0.5">
                                        Opérationnel
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>


            {{-- =========================================================
                KPI PRINCIPAUX
            ========================================================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 mb-5">


                {{-- =====================================================
                    COMMANDES
                ====================================================== --}}
                <div
                    class="group relative overflow-hidden bg-white rounded-[20px]
                    border border-green-900/10 shadow-[0_3px_16px_rgba(0,77,42,0.06)]
                    hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">

                    <div class="absolute inset-x-0 top-0 h-1 bg-[#00843D]"></div>

                    <div class="p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div>
                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    Commandes aujourd’hui
                                </p>

                                <p class="text-2xl font-black text-[#004D2A] mt-1.5">
                                    {{ $kpis['orders_today'] }}
                                </p>

                                <p class="mt-1.5 text-[11px] text-slate-500">
                                    Activité enregistrée aujourd’hui
                                </p>
                            </div>

                            <div
                                class="w-10 h-10 rounded-2xl bg-green-50 text-[#00843D]
                                flex items-center justify-center
                                group-hover:scale-110 transition-transform">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 5h6M9 9h6m-8 4h10m-8 4h6M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                </svg>

                            </div>

                        </div>

                    </div>
                </div>


                {{-- =====================================================
                    CA
                ====================================================== --}}
                <div
                    class="group relative overflow-hidden bg-white rounded-[20px]
                    border border-green-900/10 shadow-[0_3px_16px_rgba(0,77,42,0.06)]
                    hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">

                    <div class="absolute inset-x-0 top-0 h-1 bg-[#00843D]"></div>

                    <div class="p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div class="min-w-0">

                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    CA aujourd’hui
                                </p>

                                <p class="text-xl sm:text-2xl font-black text-[#00843D] mt-1.5 truncate">
                                    {{ number_format($kpis['revenue_today'], 0, ',', ' ') }}

                                    <span class="text-[11px] font-black text-[#00843D]/70">
                                        FCFA
                                    </span>
                                </p>

                                <p class="mt-1.5 text-[11px] text-slate-500">
                                    Chiffre d’affaires généré
                                </p>

                            </div>

                            <div
                                class="w-10 h-10 rounded-2xl bg-green-50 text-[#00843D]
                                flex items-center justify-center
                                group-hover:scale-110 transition-transform">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 0 3 2-1.343 2-3 2m0-10v1m0 10v1m8-6a8 8 0 11-16 0 8 8 0 0116 0z" />
                                </svg>

                            </div>

                        </div>

                    </div>
                </div>


                {{-- =====================================================
                    LITIGES
                ====================================================== --}}
                <div
                    class="group relative overflow-hidden bg-white rounded-[20px]
                    border border-green-900/10 shadow-[0_3px_16px_rgba(0,77,42,0.06)]
                    hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">

                    <div
                        class="absolute inset-x-0 top-0 h-1
                        {{ $kpis['disputes_open'] > 0 ? 'bg-[#CE1126]' : 'bg-[#00843D]' }}">
                    </div>

                    <div class="p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div>

                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    Litiges ouverts
                                </p>

                                <p
                                    class="text-2xl font-black mt-1.5
                                    {{ $kpis['disputes_open'] > 0 ? 'text-[#CE1126]' : 'text-[#004D2A]' }}">
                                    {{ $kpis['disputes_open'] }}
                                </p>

                                @if ($kpis['disputes_open'] > 0)
                                    <a href="{{ route('admin.disputes.index') }}"
                                        class="inline-flex items-center gap-1 mt-1.5 text-[11px]
                                        font-black text-[#CE1126] hover:text-red-800 transition">

                                        Examiner les litiges
                                        <span>→</span>

                                    </a>
                                @else
                                    <p class="mt-1.5 text-[11px] text-[#00843D] font-semibold">
                                        Aucun litige en attente
                                    </p>
                                @endif

                            </div>

                            <div
                                class="w-10 h-10 rounded-2xl flex items-center justify-center
                                group-hover:scale-110 transition-transform
                                {{ $kpis['disputes_open'] > 0 ? 'bg-red-50 text-[#CE1126]' : 'bg-green-50 text-[#00843D]' }}">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75 3L13.7 3.8a2 2 0 00-3.4 0z" />
                                </svg>

                            </div>

                        </div>

                    </div>
                </div>


                {{-- =====================================================
                    KYC
                ====================================================== --}}
                <div
                    class="group relative overflow-hidden bg-white rounded-[20px]
                    border border-green-900/10 shadow-[0_3px_16px_rgba(0,77,42,0.06)]
                    hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">

                    <div
                        class="absolute inset-x-0 top-0 h-1
                        {{ $kpis['kyc_pending'] > 0 ? 'bg-[#FCD116]' : 'bg-[#00843D]' }}">
                    </div>

                    <div class="p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div>

                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    KYC en attente
                                </p>

                                <p
                                    class="text-2xl font-black mt-1.5
                                    {{ $kpis['kyc_pending'] > 0 ? 'text-[#9A6700]' : 'text-[#004D2A]' }}">
                                    {{ $kpis['kyc_pending'] }}
                                </p>

                                @if ($kpis['kyc_pending'] > 0)
                                    <a href="{{ route('admin.kyc.index') }}"
                                        class="inline-flex items-center gap-1 mt-1.5 text-[11px]
                                        font-black text-[#9A6700] hover:text-[#745000]">

                                        Vérifier les dossiers
                                        <span>→</span>

                                    </a>
                                @else
                                    <p class="mt-1.5 text-[11px] text-[#00843D] font-semibold">
                                        Tous les dossiers sont traités
                                    </p>
                                @endif

                            </div>

                            <div
                                class="w-10 h-10 rounded-2xl flex items-center justify-center
                                group-hover:scale-110 transition-transform
                                {{ $kpis['kyc_pending'] > 0 ? 'bg-[#FFF8D7] text-[#9A6700]' : 'bg-green-50 text-[#00843D]' }}">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z" />
                                </svg>

                            </div>

                        </div>

                    </div>
                </div>

            </div>


            {{-- =========================================================
                FINANCES
            ========================================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">


                {{-- =====================================================
                    ESCROW
                ====================================================== --}}
                <div
                    class="relative overflow-hidden rounded-[22px] bg-[#004D2A]
                    shadow-lg shadow-green-950/10">

                    <div
                        class="absolute -right-16 -top-16 w-48 h-48 rounded-full
                        bg-[#00843D]/30 blur-3xl">
                    </div>

                    <div
                        class="absolute right-8 bottom-0 w-24 h-24 rounded-full
                        bg-[#FCD116]/10 blur-2xl">
                    </div>

                    <div class="relative p-5">

                        <div class="flex items-center justify-between">

                            <div>

                                <div class="flex items-center gap-2 mb-1.5">

                                    <div
                                        class="w-7 h-7 rounded-xl bg-white/10 text-[#FCD116]
                                        flex items-center justify-center">

                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v3h8z" />
                                        </svg>

                                    </div>

                                    <span class="text-[10px] font-black uppercase tracking-wider text-[#FCD116]">
                                        Escrow
                                    </span>

                                </div>

                                <p class="text-xs text-green-100/70">
                                    Fonds actuellement séquestrés
                                </p>

                                <p class="text-xl sm:text-2xl font-black text-white mt-1.5">
                                    {{ number_format($kpis['total_escrow'], 0, ',', ' ') }}

                                    <span class="text-xs text-[#FCD116]">
                                        FCFA
                                    </span>
                                </p>

                            </div>

                            <div
                                class="hidden sm:flex w-12 h-12 rounded-2xl bg-white/10
                                border border-white/10 items-center justify-center">

                                <svg class="w-6 h-6 text-[#FCD116]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 3v18m9-9H3" />

                                </svg>

                            </div>

                        </div>

                    </div>
                </div>


                {{-- =====================================================
                    SOLDE DISPONIBLE
                ====================================================== --}}
                <div
                    class="relative overflow-hidden rounded-[22px] bg-[#00843D]
                    shadow-lg shadow-green-700/20">

                    <div
                        class="absolute -right-16 -top-16 w-48 h-48 rounded-full
                        bg-white/10 blur-3xl">
                    </div>

                    <div class="relative p-5">

                        <div class="flex items-center justify-between">

                            <div>

                                <div class="flex items-center gap-2 mb-1.5">

                                    <div
                                        class="w-7 h-7 rounded-xl bg-white/15 text-white
                                        flex items-center justify-center">

                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z" />

                                        </svg>

                                    </div>

                                    <span class="text-[10px] font-black uppercase tracking-wider text-green-50">
                                        Disponible
                                    </span>

                                </div>

                                <p class="text-xs text-green-50/80">
                                    Solde disponible vendeurs
                                </p>

                                <p class="text-xl sm:text-2xl font-black text-white mt-1.5">
                                    {{ number_format($kpis['total_available'], 0, ',', ' ') }}

                                    <span class="text-xs text-green-50">
                                        FCFA
                                    </span>
                                </p>

                            </div>

                            <div
                                class="hidden sm:flex w-12 h-12 rounded-2xl bg-white/15
                                border border-white/20 items-center justify-center">

                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 0 3 2-1.343 2-3 2m0-10v1m0 10v1" />

                                </svg>

                            </div>

                        </div>

                    </div>
                </div>

            </div>


            {{-- =========================================================
                SOLDE ELGIOPAY
            ========================================================== --}}
            @if ($elgiopayBalance)
                <div x-data="{ withdrawModal: false, amount: '', operator: 'MTN', phone: '', recipient_name: '' }"
                    class="relative overflow-hidden bg-white rounded-[22px]
                    border border-green-900/10
                    shadow-[0_3px_16px_rgba(0,77,42,0.06)] p-5 mb-6">

                    <div class="absolute top-0 left-0 right-0 h-1 flex">
                        <div class="w-1/3 bg-[#00843D]"></div>
                        <div class="w-1/3 bg-[#FCD116]"></div>
                        <div class="w-1/3 bg-[#CE1126]"></div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pt-1">
                        <h2 class="font-black text-[#004D2A] flex items-center gap-2">
                            <span
                                class="w-9 h-9 rounded-xl bg-green-50 text-[#00843D]
                                flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M3 7h18M5 7V5a2 2 0 012-2h10a2 2 0 012 2v2M5 7v12a2 2 0 002 2h10a2 2 0 002-2V7M8 11h8" />
                                </svg>
                            </span>
                            <span>
                                Solde elgiopay plateforme
                            </span>
                        </h2>

                        @if (($elgiopayBalance['available_balance'] ?? 0) >= 1000)
                            <button @click="withdrawModal = true" type="button"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl
                                bg-[#00843D] hover:bg-[#006830] text-white text-xs font-black shadow-sm
                                transition active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Effectuer un retrait</span>
                            </button>
                        @endif
                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                        {{-- Solde --}}
                        <div class="bg-[#FAF9F6] border border-green-900/5 rounded-2xl p-4">

                            <p class="text-xs text-slate-500 mb-1">
                                Solde
                            </p>

                            <p class="text-xl font-black text-[#004D2A]">

                                {{ number_format($elgiopayBalance['balance'] ?? 0, 0, ',', ' ') }}

                                <span class="text-sm font-bold text-slate-500">
                                    FCFA
                                </span>

                            </p>

                        </div>


                        {{-- Réservé --}}
                        <div class="bg-[#FFFBE8] border border-[#FCD116]/30 rounded-2xl p-4">

                            <p class="text-xs text-slate-500 mb-1">
                                Solde réservé
                            </p>

                            <p class="text-xl font-black text-[#745000]">

                                {{ number_format($elgiopayBalance['reserved_balance'] ?? 0, 0, ',', ' ') }}

                                <span class="text-sm font-bold text-slate-500">
                                    FCFA
                                </span>

                            </p>

                        </div>


                        {{-- Disponible --}}
                        <div class="bg-green-50 border border-[#00843D]/10 rounded-2xl p-4">

                            <p class="text-xs text-[#00843D] mb-1 font-semibold">
                                Solde disponible
                            </p>

                            <p class="text-xl font-black text-[#00843D]">

                                {{ number_format($elgiopayBalance['available_balance'] ?? 0, 0, ',', ' ') }}

                                <span class="text-sm font-bold text-[#00843D]/70">
                                    FCFA
                                </span>

                            </p>

                        </div>

                    </div>

                    {{-- Modal Retrait Elgiopay --}}
                    <template x-teleport="body">
                        <div x-show="withdrawModal"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-[#0a1b12]/70 backdrop-blur-sm"
                            x-cloak>

                            <div @click.away="withdrawModal = false"
                                class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">

                                {{-- Modal Header --}}
                                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-[#0a1b12] text-white">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-[#00843D]/20 text-[#00843D] flex items-center justify-center">
                                            <svg class="w-5 h-5 text-[#FCD116]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-white text-base">Retrait de fonds Elgiopay</h3>
                                            <p class="text-xs text-slate-400">Vers un compte Mobile Money</p>
                                        </div>
                                    </div>
                                    <button @click="withdrawModal = false" type="button" class="text-slate-400 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Form --}}
                                <form action="{{ route('admin.withdraw') }}" method="POST" class="p-5 space-y-4">
                                    @csrf

                                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-center justify-between">
                                        <span>Solde disponible :</span>
                                        <span class="font-black text-emerald-900 text-sm">
                                            {{ number_format($elgiopayBalance['available_balance'] ?? 0, 0, ',', ' ') }} FCFA
                                        </span>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Montant à retirer (FCFA) *</label>
                                        <input type="number" name="amount" x-model="amount" min="1000" max="{{ $elgiopayBalance['available_balance'] ?? 0 }}" required
                                            placeholder="Ex: 50000"
                                            class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00843D]">
                                        <p class="text-[11px] text-slate-500 mt-1">Minimum : 1 000 FCFA</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Opérateur *</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label class="cursor-pointer border rounded-xl p-3 flex items-center gap-2 transition"
                                                :class="operator === 'MTN' ? 'border-[#FCD116] bg-amber-50/50 ring-2 ring-[#FCD116]/50' : 'border-slate-200 hover:bg-slate-50'">
                                                <input type="radio" name="operator" value="MTN" x-model="operator" class="text-[#FCD116] focus:ring-[#FCD116]">
                                                <span class="text-xs font-bold text-slate-800">MTN MoMo</span>
                                            </label>
                                            <label class="cursor-pointer border rounded-xl p-3 flex items-center gap-2 transition"
                                                :class="operator === 'ORANGE' ? 'border-orange-500 bg-orange-50/50 ring-2 ring-orange-500/50' : 'border-slate-200 hover:bg-slate-50'">
                                                <input type="radio" name="operator" value="ORANGE" x-model="operator" class="text-orange-600 focus:ring-orange-500">
                                                <span class="text-xs font-bold text-slate-800">Orange Money</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Numéro de téléphone *</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">+237</span>
                                            <input type="text" name="phone" x-model="phone" required maxlength="9" pattern="^6[0-9]{8}$"
                                                placeholder="6XXXXXXXX"
                                                class="w-full pl-14 pr-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00843D]">
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-1">9 chiffres commençant par 6 (ex: 670123456)</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Nom du bénéficiaire (optionnel)</label>
                                        <input type="text" name="recipient_name" x-model="recipient_name" maxlength="100"
                                            placeholder="Ex: John Doe"
                                            class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00843D]">
                                    </div>

                                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                                        <button @click="withdrawModal = false" type="button"
                                            class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                                            Annuler
                                        </button>
                                        <button type="submit"
                                            class="px-5 py-2 text-xs font-black text-white bg-[#00843D] hover:bg-[#006830] rounded-xl shadow transition active:scale-95">
                                            Confirmer le retrait
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </template>

                </div>
            @endif


            {{-- =========================================================
                ALERTES
            ========================================================== --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-5">


                {{-- =====================================================
                    KYC À VALIDER
                ====================================================== --}}
                <div
                    class="bg-white border border-green-900/10 rounded-[22px]
                    shadow-[0_3px_16px_rgba(0,77,42,0.06)] overflow-hidden">

                    <div
                        class="px-4 sm:px-5 py-4 border-b border-slate-100
                        bg-gradient-to-r from-[#FFF8D7] to-white">

                        <div class="flex items-center justify-between gap-3">

                            <div class="flex items-center gap-2.5">

                                <div
                                    class="w-9 h-9 rounded-2xl bg-[#FFF3BF] text-[#9A6700]
                                    flex items-center justify-center">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z" />

                                    </svg>

                                </div>

                                <div>

                                    <h2 class="text-sm font-black text-[#004D2A]">
                                        KYC à valider
                                    </h2>

                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Dossiers nécessitant une intervention
                                    </p>

                                </div>

                            </div>

                            <a href="{{ route('admin.kyc.index') }}"
                                class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1.5
                                rounded-xl text-[11px] font-black
                                text-[#745000] bg-[#FFF8D7]
                                hover:bg-[#FCD116] transition">

                                Voir tout <span>→</span>

                            </a>

                        </div>
                    </div>


                    @forelse($pendingKyc as $kyc)
                        <div
                            class="group flex items-center justify-between gap-3
                            px-4 sm:px-5 py-3 border-b border-slate-100
                            last:border-0 hover:bg-[#FFFBE8] transition">

                            <div class="flex items-center gap-2.5 min-w-0">

                                <div
                                    class="w-8 h-8 rounded-full bg-[#FCD116]
                                    text-[#745000] flex items-center justify-center
                                    text-xs font-black flex-shrink-0">

                                    {{ strtoupper(substr($kyc->user->name, 0, 1)) }}

                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-bold text-slate-800 truncate">
                                        {{ $kyc->user->name }}
                                    </p>

                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Soumis {{ $kyc->created_at->diffForHumans() }}
                                    </p>

                                </div>

                            </div>

                            <a href="{{ route('admin.kyc.show', $kyc) }}"
                                class="flex-shrink-0 inline-flex items-center gap-1
                                px-2.5 py-1.5 rounded-xl
                                bg-[#FFF8D7] text-[#745000]
                                text-[11px] font-black
                                hover:bg-[#FCD116] transition">

                                Examiner <span>→</span>

                            </a>

                        </div>

                    @empty

                        <div class="px-5 py-10 text-center">

                            <div
                                class="mx-auto w-12 h-12 rounded-2xl bg-green-50
                                text-[#00843D] flex items-center justify-center mb-3">

                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />

                                </svg>

                            </div>

                            <p class="text-sm font-bold text-slate-700">
                                Tout est à jour
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                Aucun dossier KYC en attente.
                            </p>

                        </div>
                    @endforelse

                </div>


                {{-- =====================================================
                    LITIGES URGENTS
                ====================================================== --}}
                <div
                    class="bg-white border border-green-900/10 rounded-[22px]
                    shadow-[0_3px_16px_rgba(0,77,42,0.06)] overflow-hidden">

                    <div
                        class="px-4 sm:px-5 py-4 border-b border-slate-100
                        bg-gradient-to-r from-red-50 to-white">

                        <div class="flex items-center justify-between gap-3">

                            <div class="flex items-center gap-2.5">

                                <div
                                    class="w-9 h-9 rounded-2xl bg-red-50 text-[#CE1126]
                                    flex items-center justify-center">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75 3L13.7 3.8a2 2 0 00-3.4 0z" />

                                    </svg>

                                </div>

                                <div>

                                    <h2 class="text-sm font-black text-[#004D2A]">
                                        Litiges urgents
                                    </h2>

                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Dossiers nécessitant une intervention
                                    </p>

                                </div>

                            </div>

                            <a href="{{ route('admin.disputes.index') }}"
                                class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1.5
                                rounded-xl text-[11px] font-black
                                text-[#CE1126] bg-red-50
                                hover:bg-red-100 transition">

                                Voir tout <span>→</span>

                            </a>

                        </div>
                    </div>


                    @forelse($openDisputes as $dispute)
                        <div
                            class="group flex items-center justify-between gap-3
                            px-4 sm:px-5 py-3 border-b border-slate-100
                            last:border-0 hover:bg-red-50/40 transition">

                            <div class="flex items-center gap-2.5 min-w-0">

                                <div
                                    class="w-8 h-8 rounded-xl bg-red-50 text-[#CE1126]
                                    flex items-center justify-center flex-shrink-0">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75 3L13.7 3.8a2 2 0 00-3.4 0z" />

                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="text-xs font-bold text-slate-800 truncate">
                                        {{ $dispute->order->reference }}
                                    </p>

                                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">
                                        {{ $dispute->typeLabel() }}
                                        ·
                                        {{ $dispute->created_at->diffForHumans() }}
                                    </p>

                                </div>

                            </div>

                            <a href="{{ route('admin.disputes.show', $dispute) }}"
                                class="flex-shrink-0 inline-flex items-center gap-1
                                px-2.5 py-1.5 rounded-xl
                                bg-red-50 text-[#CE1126]
                                text-[11px] font-black
                                hover:bg-[#CE1126] hover:text-white transition">

                                Traiter <span>→</span>

                            </a>

                        </div>

                    @empty

                        <div class="px-5 py-10 text-center">

                            <div
                                class="mx-auto w-12 h-12 rounded-2xl bg-green-50
                                text-[#00843D] flex items-center justify-center mb-3">

                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />

                                </svg>

                            </div>

                            <p class="text-sm font-bold text-slate-700">
                                Aucun litige urgent
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                Aucun dossier ne nécessite votre attention.
                            </p>

                        </div>
                    @endforelse

                </div>

            </div>


            {{-- =========================================================
                COMMANDES RÉCENTES
            ========================================================== --}}
            <div
                class="bg-white border border-green-900/10 rounded-[22px]
                shadow-[0_3px_16px_rgba(0,77,42,0.06)] overflow-hidden">

                <div class="px-4 sm:px-5 py-4 border-b border-slate-100">

                    <div
                        class="flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-2">

                        <div>

                            <div class="flex items-center gap-2">

                                <div
                                    class="w-8 h-8 rounded-xl bg-green-50
                                    text-[#00843D] flex items-center justify-center">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M9 5h6M9 9h6m-8 4h10m-8 4h6" />

                                    </svg>

                                </div>

                                <h2 class="text-sm font-black text-[#004D2A]">
                                    Commandes récentes
                                </h2>

                            </div>

                            <p class="text-[11px] text-slate-400 mt-1.5 ml-10">
                                Dernières transactions enregistrées sur la plateforme
                            </p>

                        </div>

                    </div>
                </div>


                {{-- =====================================================
                    DESKTOP
                ====================================================== --}}
                <div class="hidden md:block overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-[#FAF9F6] border-b border-slate-100">

                                <th
                                    class="text-left px-4 py-3 text-[10px]
                                    font-black uppercase tracking-wider text-slate-400">
                                    Commande
                                </th>

                                <th
                                    class="text-left px-4 py-3 text-[10px]
                                    font-black uppercase tracking-wider text-slate-400">
                                    Acheteur
                                </th>

                                <th
                                    class="text-left px-4 py-3 text-[10px]
                                    font-black uppercase tracking-wider text-slate-400">
                                    Boutique
                                </th>

                                <th
                                    class="text-right px-4 py-3 text-[10px]
                                    font-black uppercase tracking-wider text-slate-400">
                                    Montant
                                </th>

                                <th
                                    class="text-right px-4 py-3 text-[10px]
                                    font-black uppercase tracking-wider text-slate-400">
                                    Statut
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse($recentOrders as $order)
                                @php

                                    $statusLabels = [
                                        'pending' => 'En attente',
                                        'awaiting_payment' => 'Paiement',
                                        'paid' => 'Payée',
                                        'preparing' => 'Préparation',
                                        'registered_origin' => 'Déposée',
                                        'in_transit' => 'En transit',
                                        'arrived_destination' => 'Arrivée',
                                        'awaiting_buyer_confirmation' => 'Confirmation',
                                        'completed' => 'Terminée',
                                        'auto_completed' => 'Auto-terminée',
                                        'disputed' => 'Litige',
                                        'cancelled' => 'Annulée',
                                        'failed' => 'Échec',
                                    ];

                                    $statusStyles = [
                                        'pending' => 'bg-slate-100 text-slate-600 ring-slate-200',
                                        'awaiting_payment' => 'bg-[#FFF8D7] text-[#745000] ring-[#FCD116]',
                                        'paid' => 'bg-green-50 text-[#00843D] ring-[#00843D]/20',
                                        'preparing' => 'bg-green-50 text-[#006B32] ring-[#00843D]/20',
                                        'registered_origin' => 'bg-green-50 text-[#006B32] ring-[#00843D]/20',
                                        'in_transit' => 'bg-[#EEF8F2] text-[#006B32] ring-[#00843D]/20',
                                        'arrived_destination' => 'bg-[#EEF8F2] text-[#006B32] ring-[#00843D]/20',
                                        'awaiting_buyer_confirmation' => 'bg-[#FFF8D7] text-[#745000] ring-[#FCD116]',
                                        'completed' => 'bg-green-50 text-[#00843D] ring-[#00843D]/20',
                                        'auto_completed' => 'bg-green-50 text-[#00843D] ring-[#00843D]/20',
                                        'disputed' => 'bg-red-50 text-[#CE1126] ring-[#CE1126]/20',
                                        'cancelled' => 'bg-red-50 text-[#CE1126] ring-[#CE1126]/20',
                                        'failed' => 'bg-red-50 text-[#CE1126] ring-[#CE1126]/20',
                                    ];

                                    $statusDots = [
                                        'pending' => 'bg-slate-400',
                                        'awaiting_payment' => 'bg-[#FCD116]',
                                        'paid' => 'bg-[#00843D]',
                                        'preparing' => 'bg-[#00843D]',
                                        'registered_origin' => 'bg-[#006B32]',
                                        'in_transit' => 'bg-[#006B32]',
                                        'arrived_destination' => 'bg-[#00843D]',
                                        'awaiting_buyer_confirmation' => 'bg-[#FCD116]',
                                        'completed' => 'bg-[#00843D]',
                                        'auto_completed' => 'bg-[#00843D]',
                                        'disputed' => 'bg-[#CE1126]',
                                        'cancelled' => 'bg-[#CE1126]',
                                        'failed' => 'bg-[#CE1126]',
                                    ];

                                @endphp


                                <tr class="group hover:bg-green-50/40 transition-colors">

                                    {{-- Commande --}}
                                    <td class="px-4 py-3">

                                        <div class="flex items-center gap-2.5">

                                            <div
                                                class="w-8 h-8 rounded-xl bg-[#FAF9F6]
                                                text-[#00843D] flex items-center justify-center
                                                text-[11px] font-black
                                                group-hover:bg-green-100 transition">

                                                #

                                            </div>

                                            <div>

                                                <p class="text-xs font-bold text-slate-800">
                                                    {{ $order->reference }}
                                                </p>

                                                <p class="text-[11px] text-slate-400 mt-0.5">
                                                    {{ $order->created_at->diffForHumans() }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Acheteur --}}
                                    <td class="px-4 py-3">

                                        <p class="text-xs font-medium text-slate-600">
                                            {{ $order->buyer->name }}
                                        </p>

                                    </td>


                                    {{-- Boutique --}}
                                    <td class="px-4 py-3">

                                        <p class="text-xs font-medium text-slate-600">
                                            {{ $order->shop->name }}
                                        </p>

                                    </td>


                                    {{-- Montant --}}
                                    <td class="px-4 py-3 text-right">

                                        <p class="text-xs font-black text-[#004D2A]">

                                            {{ number_format($order->total_amount, 0, ',', ' ') }}

                                            <span class="text-[10px] font-semibold text-slate-400">
                                                FCFA
                                            </span>

                                        </p>

                                    </td>


                                    {{-- Statut --}}
                                    <td class="px-4 py-3 text-right">

                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1
                                            rounded-full text-[10px] font-bold ring-1 ring-inset
                                            {{ $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200' }}">

                                            <span
                                                class="w-1.5 h-1.5 rounded-full
                                                {{ $statusDots[$order->status] ?? 'bg-slate-400' }}">
                                            </span>

                                            {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="px-4 py-14 text-center">

                                        <div
                                            class="mx-auto w-14 h-14 rounded-2xl bg-[#FAF9F6]
                                            text-slate-400 flex items-center justify-center mb-3">

                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M9 5h6M9 9h6m-8 4h10m-8 4h6" />

                                            </svg>

                                        </div>

                                        <p class="text-sm font-bold text-slate-700">
                                            Aucune commande récente
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">
                                            Les nouvelles commandes apparaîtront ici.
                                        </p>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- =====================================================
                    MOBILE
                ====================================================== --}}
                <div class="md:hidden divide-y divide-slate-100">

                    @forelse($recentOrders as $order)
                        @php

                            $statusLabels = [
                                'pending' => 'En attente',
                                'awaiting_payment' => 'Paiement',
                                'paid' => 'Payée',
                                'preparing' => 'Préparation',
                                'registered_origin' => 'Déposée',
                                'in_transit' => 'En transit',
                                'arrived_destination' => 'Arrivée',
                                'awaiting_buyer_confirmation' => 'Confirmation',
                                'completed' => 'Terminée',
                                'auto_completed' => 'Auto-terminée',
                                'disputed' => 'Litige',
                                'cancelled' => 'Annulée',
                                'failed' => 'Échec',
                            ];

                            $statusStyles = [
                                'pending' => 'bg-slate-100 text-slate-600',
                                'awaiting_payment' => 'bg-[#FFF8D7] text-[#745000]',
                                'paid' => 'bg-green-50 text-[#00843D]',
                                'preparing' => 'bg-green-50 text-[#006B32]',
                                'registered_origin' => 'bg-green-50 text-[#006B32]',
                                'in_transit' => 'bg-green-50 text-[#006B32]',
                                'arrived_destination' => 'bg-green-50 text-[#00843D]',
                                'awaiting_buyer_confirmation' => 'bg-[#FFF8D7] text-[#745000]',
                                'completed' => 'bg-green-50 text-[#00843D]',
                                'auto_completed' => 'bg-green-50 text-[#00843D]',
                                'disputed' => 'bg-red-50 text-[#CE1126]',
                                'cancelled' => 'bg-red-50 text-[#CE1126]',
                                'failed' => 'bg-red-50 text-[#CE1126]',
                            ];

                        @endphp


                        <div class="p-4 hover:bg-green-50/40 transition">

                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">

                                    <div class="flex items-center gap-2">

                                        <span
                                            class="w-1.5 h-1.5 rounded-full
                                            {{ in_array($order->status, ['completed', 'auto_completed', 'paid'])
                                                ? 'bg-[#00843D]'
                                                : (in_array($order->status, ['disputed', 'failed', 'cancelled'])
                                                    ? 'bg-[#CE1126]'
                                                    : (in_array($order->status, ['awaiting_payment', 'awaiting_buyer_confirmation'])
                                                        ? 'bg-[#FCD116]'
                                                        : 'bg-[#00843D]')) }}">
                                        </span>

                                        <p class="text-sm font-black text-slate-800 truncate">
                                            {{ $order->reference }}
                                        </p>

                                    </div>

                                    <p class="text-[11px] text-slate-400 mt-1">
                                        {{ $order->buyer->name }}
                                    </p>

                                </div>


                                <span
                                    class="inline-flex flex-shrink-0 px-2 py-0.5
                                    rounded-full text-[10px] font-bold
                                    {{ $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-600' }}">

                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}

                                </span>

                            </div>


                            <div class="flex items-center justify-between mt-3">

                                <div>

                                    <p class="text-[11px] text-slate-400">
                                        Boutique
                                    </p>

                                    <p class="text-[11px] font-semibold text-slate-600 mt-0.5">
                                        {{ $order->shop->name }}
                                    </p>

                                </div>


                                <div class="text-right">

                                    <p class="text-[11px] text-slate-400">
                                        Montant
                                    </p>

                                    <p class="text-xs font-black text-[#004D2A] mt-0.5">

                                        {{ number_format($order->total_amount, 0, ',', ' ') }}

                                        <span class="text-[10px] text-slate-400">
                                            FCFA
                                        </span>

                                    </p>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="px-5 py-12 text-center">

                            <p class="text-sm font-bold text-slate-600">
                                Aucune commande récente
                            </p>

                        </div>
                    @endforelse

                </div>

            </div>


            {{-- =========================================================
                FOOTER
            ========================================================== --}}
            <div class="mt-5 flex flex-col sm:flex-row sm:items-center
                sm:justify-between gap-2">

                <div class="flex items-center gap-2 text-[11px] text-slate-400">

                    <span class="w-1.5 h-1.5 rounded-full bg-[#00843D]"></span>

                    <span>
                        Tableau de bord administrateur
                    </span>

                </div>

                <p class="text-[11px] text-slate-400">
                    Données mises à jour selon l’activité actuelle.
                </p>

            </div>

        </div>
    </div>

@endsection
