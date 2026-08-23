@extends('base')

@section('title', 'Dashboard Admin')

@section('content')

{{-- =========================================================
     ALI-KAMER ADMIN — DASHBOARD
     Design system : bleu #1769E0 + surfaces neutres
========================================================= --}}
<div class="min-h-screen bg-[#F7F8FA]">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="relative overflow-hidden rounded-2xl mb-6 bg-slate-900 shadow-lg shadow-slate-200/60">
            <div class="absolute -top-20 -right-16 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -bottom-24 left-1/3 w-64 h-64 rounded-full bg-blue-400/10 blur-3xl"></div>

            <div class="relative px-5 sm:px-6 py-5 sm:py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-white/10 border border-white/20 text-slate-200 text-[11px] font-semibold mb-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Administration
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                            Tableau de bord
                        </h1>

                        <p class="mt-1.5 text-xs sm:text-sm text-slate-300 max-w-2xl">
                            Supervisez l’activité de la plateforme, les transactions, les utilisateurs et les opérations logistiques.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="inline-flex items-center gap-2.5 px-3 py-2 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-sm text-white">
                            <div class="relative">
                                <span class="absolute inset-0 rounded-full bg-emerald-400 animate-ping opacity-50"></span>
                                <span class="relative block w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-300">État du système</p>
                                <p class="text-xs font-bold">Opérationnel</p>
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

            {{-- Commandes --}}
            <div class="group relative overflow-hidden bg-white rounded-2xl border border-slate-200/80 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#1769E0]"></div>
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Commandes aujourd’hui</p>
                            <p class="text-2xl font-black text-slate-900 mt-1.5">{{ $kpis['orders_today'] }}</p>
                            <p class="mt-1.5 text-[11px] text-slate-500">Activité enregistrée aujourd’hui</p>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-[#1769E0] text-white flex items-center justify-center shadow-lg shadow-blue-100 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5h6M9 9h6m-8 4h10m-8 4h6M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CA --}}
            <div class="group relative overflow-hidden bg-white rounded-2xl border border-slate-200/80 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <div class="absolute inset-x-0 top-0 h-1 bg-[#1769E0]"></div>
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">CA aujourd’hui</p>
                            <p class="text-xl sm:text-2xl font-black text-[#1769E0] mt-1.5 truncate">
                                {{ number_format($kpis['revenue_today'], 0, ',', ' ') }}
                                <span class="text-[11px] font-bold text-blue-500">FCFA</span>
                            </p>
                            <p class="mt-1.5 text-[11px] text-slate-500">Chiffre d’affaires généré</p>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-[#1769E0] text-white flex items-center justify-center shadow-lg shadow-blue-100 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1m8-6a8 8 0 11-16 0 8 8 0 0116 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Litiges --}}
            <div class="group relative overflow-hidden bg-white rounded-2xl border border-slate-200/80 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <div class="absolute inset-x-0 top-0 h-1 {{ $kpis['disputes_open'] > 0 ? 'bg-gradient-to-r from-red-500 to-rose-600' : 'bg-gradient-to-r from-emerald-400 to-green-500' }}"></div>
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Litiges ouverts</p>
                            <p class="text-2xl font-black mt-1.5 {{ $kpis['disputes_open'] > 0 ? 'text-red-600' : 'text-slate-900' }}">
                                {{ $kpis['disputes_open'] }}
                            </p>
                            @if ($kpis['disputes_open'] > 0)
                                <a href="{{ route('admin.disputes.index') }}" class="inline-flex items-center gap-1 mt-1.5 text-[11px] font-bold text-red-600 hover:text-red-700">
                                    Examiner les litiges <span>→</span>
                                </a>
                            @else
                                <p class="mt-1.5 text-[11px] text-emerald-600 font-medium">Aucun litige en attente</p>
                            @endif
                        </div>
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform {{ $kpis['disputes_open'] > 0 ? 'bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-200' : 'bg-emerald-50 text-emerald-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KYC --}}
            <div class="group relative overflow-hidden bg-white rounded-2xl border border-slate-200/80 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <div class="absolute inset-x-0 top-0 h-1 {{ $kpis['kyc_pending'] > 0 ? 'bg-gradient-to-r from-orange-400 to-amber-500' : 'bg-gradient-to-r from-emerald-400 to-green-500' }}"></div>
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">KYC en attente</p>
                            <p class="text-2xl font-black mt-1.5 {{ $kpis['kyc_pending'] > 0 ? 'text-orange-600' : 'text-slate-900' }}">
                                {{ $kpis['kyc_pending'] }}
                            </p>
                            @if ($kpis['kyc_pending'] > 0)
                                <a href="{{ route('admin.kyc.index') }}" class="inline-flex items-center gap-1 mt-1.5 text-[11px] font-bold text-orange-600 hover:text-orange-700">
                                    Vérifier les dossiers <span>→</span>
                                </a>
                            @else
                                <p class="mt-1.5 text-[11px] text-emerald-600 font-medium">Tous les dossiers sont traités</p>
                            @endif
                        </div>
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform {{ $kpis['kyc_pending'] > 0 ? 'bg-gradient-to-br from-orange-400 to-amber-500 text-white shadow-lg shadow-orange-200' : 'bg-emerald-50 text-emerald-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>
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

            {{-- Escrow --}}
            <div class="relative overflow-hidden rounded-2xl bg-slate-900 shadow-lg shadow-slate-200/50">
                <div class="absolute -right-14 -top-14 w-40 h-40 rounded-full bg-blue-500/10 blur-2xl"></div>
                <div class="relative p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <div class="w-7 h-7 rounded-xl bg-white/10 text-blue-300 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v3h8z"/>
                                    </svg>
                                </div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-300">Escrow</span>
                            </div>
                            <p class="text-xs text-slate-300">Fonds actuellement séquestrés</p>
                            <p class="text-xl sm:text-2xl font-black text-white mt-1.5">
                                {{ number_format($kpis['total_escrow'], 0, ',', ' ') }}
                                <span class="text-xs text-blue-300">FCFA</span>
                            </p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-white/10 border border-white/10 items-center justify-center">
                            <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v18m9-9H3"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Solde disponible --}}
            <div class="relative overflow-hidden rounded-2xl bg-[#1769E0] shadow-lg shadow-blue-200/40">
                <div class="absolute -right-14 -top-14 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
                <div class="relative p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <div class="w-7 h-7 rounded-xl bg-white/15 text-white flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>
                                    </svg>
                                </div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-50">Disponible</span>
                            </div>
                            <p class="text-xs text-blue-100">Solde disponible vendeurs</p>
                            <p class="text-xl sm:text-2xl font-black text-white mt-1.5">
                                {{ number_format($kpis['total_available'], 0, ',', ' ') }}
                                <span class="text-xs text-blue-100">FCFA</span>
                            </p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-white/15 border border-white/20 items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Solde Campay --}}
        @if ($campayBalance)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_2px_12px_rgba(15,23,42,0.05)] p-5 mb-6">
                <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-lg">💳</span>
                    Solde Campay plateforme
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-[#F7F8FA] rounded-2xl p-4">
                        <p class="text-xs text-slate-500 mb-1">MTN MoMo</p>
                        <p class="text-xl font-extrabold text-slate-900">
                            {{ number_format($campayBalance['mtn'] ?? 0, 0, ',', ' ') }} <span class="text-sm font-semibold text-slate-500">FCFA</span>
                        </p>
                    </div>
                    <div class="bg-[#F7F8FA] rounded-2xl p-4">
                        <p class="text-xs text-slate-500 mb-1">Orange Money</p>
                        <p class="text-xl font-extrabold text-slate-900">
                            {{ number_format($campayBalance['orange'] ?? 0, 0, ',', ' ') }} <span class="text-sm font-semibold text-slate-500">FCFA</span>
                        </p>
                    </div>
                    <div class="bg-[#F7F8FA] rounded-2xl p-4">
                        <p class="text-xs text-slate-500 mb-1">Solde Total</p>
                        <p class="text-xl font-extrabold text-slate-900">
                            {{ number_format($campayBalance['total_balance'] ?? 0, 0, ',', ' ') }} <span class="text-sm font-semibold text-slate-500">FCFA</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- =========================================================
             ACTIONS RAPIDES
        ========================================================== --}}
        <div class="mb-6">
            <div class="mb-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#1769E0]">Navigation</p>
                <h2 class="text-lg font-black text-slate-900 mt-0.5">Accès rapides</h2>
                <p class="text-xs text-slate-500 mt-0.5">Accédez directement aux principaux outils d’administration.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-3">

                {{-- Utilisateurs --}}
                <a href="{{ route('admin.users.index') }}"
                   class="group relative overflow-hidden bg-white border border-slate-200/80 rounded-2xl p-4 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-[#1769E0] scale-x-0 group-hover:scale-x-100 origin-left transition-transform"></div>
                    <div class="w-10 h-10 rounded-2xl bg-[#EEF5FF] text-[#1769E0] flex items-center justify-center group-hover:bg-[#1769E0] group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm9 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-bold text-slate-800">Utilisateurs</h3>
                    <p class="mt-0.5 text-[11px] text-slate-400">Gérer les comptes</p>
                </a>

                {{-- Moteur financier --}}
                <a href="{{ route('admin.financial-engine.index') }}"
                   class="group relative overflow-hidden bg-white border border-slate-200/80 rounded-2xl p-4 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-[#1769E0] scale-x-0 group-hover:scale-x-100 origin-left transition-transform"></div>
                    <div class="w-10 h-10 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center group-hover:bg-violet-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1m8-6a8 8 0 11-16 0 8 8 0 0116 0z"/>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-bold text-slate-800">Moteur financier</h3>
                    <p class="mt-0.5 text-[11px] text-slate-400">Définir les taux</p>
                </a>

                {{-- Agences --}}
                <a href="{{ route('admin.agencies.index') }}"
                   class="group relative overflow-hidden bg-white border border-slate-200/80 rounded-2xl p-4 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-[#1769E0] scale-x-0 group-hover:scale-x-100 origin-left transition-transform"></div>
                    <div class="w-10 h-10 rounded-2xl bg-[#EEF5FF] text-[#1769E0] flex items-center justify-center group-hover:bg-[#1769E0] group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 8h2m-2 4h2m2-4h2m-2 4h2"/>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-bold text-slate-800">Agences</h3>
                    <p class="mt-0.5 text-[11px] text-slate-400">Réseau logistique</p>
                </a>

                {{-- KYC --}}
                <a href="{{ route('admin.kyc.index') }}"
                   class="group relative overflow-hidden bg-white border border-slate-200/80 rounded-2xl p-4 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-orange-400 to-amber-500 scale-x-0 group-hover:scale-x-100 origin-left transition-transform"></div>
                    <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-bold text-slate-800">KYC</h3>
                    <p class="mt-0.5 text-[11px] text-slate-400">Vérification</p>
                </a>

                {{-- Litiges --}}
                <a href="{{ route('admin.disputes.index') }}"
                   class="group relative overflow-hidden bg-white border border-slate-200/80 rounded-2xl p-4 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-red-500 to-rose-500 scale-x-0 group-hover:scale-x-100 origin-left transition-transform"></div>
                    <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z"/>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-bold text-slate-800">Litiges</h3>
                    <p class="mt-0.5 text-[11px] text-slate-400">Résolution</p>
                </a>

                {{-- Catégories --}}
                <a href="{{ route('admin.categories.index') }}"
                   class="group relative overflow-hidden bg-white border border-slate-200/80 rounded-2xl p-4 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-indigo-500 scale-x-0 group-hover:scale-x-100 origin-left transition-transform"></div>
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-bold text-slate-800">Catégories</h3>
                    <p class="mt-0.5 text-[11px] text-slate-400">Arborescence</p>
                </a>

                {{-- Tutoriels --}}
                <a href="{{ route('admin.tutorials.index') }}"
                   class="group relative overflow-hidden bg-white border border-slate-200/80 rounded-2xl p-4 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-[#1769E0] scale-x-0 group-hover:scale-x-100 origin-left transition-transform"></div>
                    <div class="w-10 h-10 rounded-2xl bg-[#EEF5FF] text-[#1769E0] flex items-center justify-center group-hover:bg-[#1769E0] group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-bold text-slate-800">Tutoriels</h3>
                    <p class="mt-0.5 text-[11px] text-slate-400">Gérer le contenu</p>
                </a>

                {{-- Notes basses --}}
                <a href="{{ route('admin.users.low-scores') }}"
                   class="group relative overflow-hidden bg-white border border-red-200/80 rounded-2xl p-4 shadow-[0_2px_12px_rgba(15,23,42,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-red-500 scale-x-0 group-hover:scale-x-100 origin-left transition-transform"></div>
                    <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-bold text-slate-800">Notes basses</h3>
                    <p class="mt-0.5 text-[11px] text-red-500 font-medium">
                        {{ \App\Models\User::where('role', 'buyer')->where('trust_score', '<', 40)->count() }} à surveiller
                    </p>
                </a>
            </div>
        </div>

        {{-- =========================================================
             ALERTES
        ========================================================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-5">

            {{-- KYC à valider --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-[0_2px_12px_rgba(15,23,42,0.05)] overflow-hidden">
                <div class="px-4 sm:px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-orange-50/80 to-white">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-black text-slate-800">KYC à valider</h2>
                                <p class="text-[11px] text-slate-400 mt-0.5">Dossiers nécessitant une intervention</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.kyc.index') }}"
                           class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-[11px] font-bold text-orange-600 bg-orange-50 hover:bg-orange-100 transition">
                            Voir tout <span>→</span>
                        </a>
                    </div>
                </div>

                @forelse($pendingKyc as $kyc)
                    <div class="group flex items-center justify-between gap-3 px-4 sm:px-5 py-3 border-b border-slate-100 last:border-0 hover:bg-slate-50/60 transition">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 text-white flex items-center justify-center text-xs font-black flex-shrink-0">
                                {{ strtoupper(substr($kyc->user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $kyc->user->name }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Soumis {{ $kyc->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.kyc.show', $kyc) }}"
                           class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-orange-50 text-orange-600 text-[11px] font-bold hover:bg-orange-500 hover:text-white transition">
                            Examiner <span>→</span>
                        </a>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <div class="mx-auto w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Tout est à jour</p>
                        <p class="text-xs text-slate-400 mt-1">Aucun dossier KYC en attente.</p>
                    </div>
                @endforelse
            </div>

            {{-- Litiges urgents --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-[0_2px_12px_rgba(15,23,42,0.05)] overflow-hidden">
                <div class="px-4 sm:px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-red-50/80 to-white">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-black text-slate-800">Litiges urgents</h2>
                                <p class="text-[11px] text-slate-400 mt-0.5">Dossiers nécessitant une intervention</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.disputes.index') }}"
                           class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-[11px] font-bold text-red-600 bg-red-50 hover:bg-red-100 transition">
                            Voir tout <span>→</span>
                        </a>
                    </div>
                </div>

                @forelse($openDisputes as $dispute)
                    <div class="group flex items-center justify-between gap-3 px-4 sm:px-5 py-3 border-b border-slate-100 last:border-0 hover:bg-red-50/40 transition">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $dispute->order->reference }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5 truncate">
                                    {{ $dispute->typeLabel() }} · {{ $dispute->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.disputes.show', $dispute) }}"
                           class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-red-50 text-red-600 text-[11px] font-bold hover:bg-red-600 hover:text-white transition">
                            Traiter <span>→</span>
                        </a>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <div class="mx-auto w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Aucun litige urgent</p>
                        <p class="text-xs text-slate-400 mt-1">Aucun dossier ne nécessite votre attention.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- =========================================================
             COMMANDES RÉCENTES
        ========================================================== --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-[0_2px_12px_rgba(15,23,42,0.05)] overflow-hidden">
            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-[#EEF5FF] text-[#1769E0] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5h6M9 9h6m-8 4h10m-8 4h6"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-black text-slate-800">Commandes récentes</h2>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5 ml-10">Dernières transactions enregistrées sur la plateforme</p>
                    </div>
                </div>
            </div>

            {{-- Desktop --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="text-left px-4 py-2.5 text-[10px] font-black uppercase tracking-wider text-slate-400">Commande</th>
                            <th class="text-left px-4 py-2.5 text-[10px] font-black uppercase tracking-wider text-slate-400">Acheteur</th>
                            <th class="text-left px-4 py-2.5 text-[10px] font-black uppercase tracking-wider text-slate-400">Boutique</th>
                            <th class="text-right px-4 py-2.5 text-[10px] font-black uppercase tracking-wider text-slate-400">Montant</th>
                            <th class="text-right px-4 py-2.5 text-[10px] font-black uppercase tracking-wider text-slate-400">Statut</th>
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
                                    'awaiting_payment' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                    'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                    'preparing' => 'bg-blue-50 text-blue-700 ring-blue-200',
                                    'registered_origin' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
                                    'in_transit' => 'bg-purple-50 text-purple-700 ring-purple-200',
                                    'arrived_destination' => 'bg-cyan-50 text-cyan-700 ring-cyan-200',
                                    'awaiting_buyer_confirmation' => 'bg-orange-50 text-orange-700 ring-orange-200',
                                    'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                    'auto_completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                    'disputed' => 'bg-red-50 text-red-700 ring-red-200',
                                    'cancelled' => 'bg-red-50 text-red-700 ring-red-200',
                                    'failed' => 'bg-red-50 text-red-700 ring-red-200',
                                ];

                                $statusDots = [
                                    'pending' => 'bg-slate-400',
                                    'awaiting_payment' => 'bg-amber-500',
                                    'paid' => 'bg-emerald-500',
                                    'preparing' => 'bg-blue-500',
                                    'registered_origin' => 'bg-indigo-500',
                                    'in_transit' => 'bg-purple-500',
                                    'arrived_destination' => 'bg-cyan-500',
                                    'awaiting_buyer_confirmation' => 'bg-orange-500',
                                    'completed' => 'bg-emerald-500',
                                    'auto_completed' => 'bg-emerald-500',
                                    'disputed' => 'bg-red-500',
                                    'cancelled' => 'bg-red-500',
                                    'failed' => 'bg-red-500',
                                ];
                            @endphp

                            <tr class="group hover:bg-blue-50/30 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-[11px] font-black group-hover:bg-[#E0EDFF] group-hover:text-[#1769E0] transition">
                                            #
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">{{ $order->reference }}</p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-medium text-slate-600">{{ $order->buyer->name }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-medium text-slate-600">{{ $order->shop->name }}</p>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <p class="text-xs font-black text-slate-800">
                                        {{ number_format($order->total_amount, 0, ',', ' ') }}
                                        <span class="text-[10px] font-semibold text-slate-400">FCFA</span>
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold ring-1 ring-inset {{ $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$order->status] ?? 'bg-slate-400' }}"></span>
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-14 text-center">
                                    <div class="mx-auto w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5h6M9 9h6m-8 4h10m-8 4h6"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Aucune commande récente</p>
                                    <p class="text-xs text-slate-400 mt-1">Les nouvelles commandes apparaîtront ici.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
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
                            'awaiting_payment' => 'bg-amber-50 text-amber-700',
                            'paid' => 'bg-emerald-50 text-emerald-700',
                            'preparing' => 'bg-blue-50 text-blue-700',
                            'registered_origin' => 'bg-indigo-50 text-indigo-700',
                            'in_transit' => 'bg-purple-50 text-purple-700',
                            'arrived_destination' => 'bg-cyan-50 text-cyan-700',
                            'awaiting_buyer_confirmation' => 'bg-orange-50 text-orange-700',
                            'completed' => 'bg-emerald-50 text-emerald-700',
                            'auto_completed' => 'bg-emerald-50 text-emerald-700',
                            'disputed' => 'bg-red-50 text-red-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                            'failed' => 'bg-red-50 text-red-700',
                        ];
                    @endphp

                    <div class="p-4 hover:bg-slate-50 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full {{ in_array($order->status, ['completed', 'auto_completed', 'paid']) ? 'bg-emerald-500' : (in_array($order->status, ['disputed', 'failed', 'cancelled']) ? 'bg-red-500' : 'bg-blue-500') }}"></span>
                                    <p class="text-sm font-black text-slate-800 truncate">{{ $order->reference }}</p>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1">{{ $order->buyer->name }}</p>
                            </div>
                            <span class="inline-flex flex-shrink-0 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between mt-3">
                            <div>
                                <p class="text-[11px] text-slate-400">Boutique</p>
                                <p class="text-[11px] font-semibold text-slate-600 mt-0.5">{{ $order->shop->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[11px] text-slate-400">Montant</p>
                                <p class="text-xs font-black text-slate-800 mt-0.5">
                                    {{ number_format($order->total_amount, 0, ',', ' ') }}
                                    <span class="text-[10px] text-slate-400">FCFA</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center">
                        <p class="text-sm font-bold text-slate-600">Aucune commande récente</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- =========================================================
             FOOTER
        ========================================================== --}}
        <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center gap-2 text-[11px] text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Tableau de bord administrateur</span>
            </div>
            <p class="text-[11px] text-slate-400">
                Données mises à jour selon l’activité actuelle.
            </p>
        </div>
    </div>
</div>

@endsection