@extends('base')
@section('title', 'Moteur Financier')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/40">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6">

        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <div class="relative overflow-hidden rounded-2xl mb-6
                    bg-gradient-to-r from-indigo-700 via-violet-700 to-purple-700
                    shadow-lg shadow-indigo-200/40">

            <div class="absolute -top-20 -right-16 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -bottom-24 left-1/3 w-64 h-64 rounded-full bg-purple-400/20 blur-3xl"></div>

            <div class="relative px-5 sm:px-6 py-5 sm:py-6">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <div>

                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center gap-1.5 mb-2
                                  text-[11px] font-semibold text-indigo-100
                                  hover:text-white transition">

                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>

                            Retour au dashboard

                        </a>

                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                            Moteur financier
                        </h1>

                        <p class="mt-1.5 text-xs sm:text-sm text-indigo-100 max-w-2xl">
                            Modifiez les taux et paramètres financiers. Les changements s'appliquent
                            aux nouvelles commandes uniquement.
                        </p>

                    </div>

                    <div class="inline-flex items-center gap-2.5
                                px-3 py-2 rounded-xl
                                bg-white/10 border border-white/20
                                backdrop-blur-sm text-white flex-shrink-0">

                        <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1m8-6a8 8 0 11-16 0 8 8 0 0116 0z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-[11px] text-indigo-200">Paramètres actifs</p>
                            <p class="text-xs font-bold">{{ collect($settings)->flatten()->count() }} réglages</p>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             MESSAGES
        ========================================================== --}}

        @if (session('success'))
            <div class="flex items-center gap-2.5 bg-emerald-50 border border-emerald-200
                        text-emerald-800 px-4 py-3 rounded-xl mb-5 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z"/>
                        </svg>
                        {{ $error }}
                    </p>
                @endforeach
            </div>
        @endif


        {{-- =========================================================
             SIMULATEUR
        ========================================================== --}}

        <div class="relative overflow-hidden rounded-xl mb-6
                    bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950
                    shadow-lg shadow-slate-200">

            <div class="absolute -right-14 -top-14 w-40 h-40 rounded-full bg-indigo-500/20 blur-2xl"></div>

            <div class="relative p-5">

                <div class="flex items-center gap-2.5 mb-4">

                    <div class="w-8 h-8 rounded-lg bg-white/10 text-indigo-300 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14"/>
                        </svg>
                    </div>

                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-300">
                            Simulation
                        </span>
                        <p class="text-xs text-slate-300">
                            Commande de {{ number_format($simulation['amount'], 0, ',', ' ') }} FCFA
                        </p>
                    </div>

                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

                    <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                        <p class="text-[11px] text-slate-400 mb-1">Acheteur paie</p>
                        <p class="text-lg font-black text-indigo-300">
                            {{ number_format($simulation['totalBuyer'], 0, ',', ' ') }}
                            <span class="text-[10px] font-semibold text-indigo-400">FCFA</span>
                        </p>
                        <p class="text-[11px] text-slate-500 mt-1.5">
                            {{ number_format($simulation['protection'], 0, ',', ' ') }} protection
                            + {{ number_format($simulation['gateway'], 0, ',', ' ') }} Campay
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                        <p class="text-[11px] text-slate-400 mb-1">Vendeur reçoit</p>
                        <p class="text-lg font-black text-emerald-400">
                            {{ number_format($simulation['netSeller'], 0, ',', ' ') }}
                            <span class="text-[10px] font-semibold text-emerald-500">FCFA</span>
                        </p>
                        <p class="text-[11px] text-slate-500 mt-1.5">
                            − commission {{ number_format($simulation['commission'], 0, ',', ' ') }}
                            − agence {{ number_format($simulation['agencyCommission'], 0, ',', ' ') }}
                            − retrait {{ number_format($simulation['payoutFee'], 0, ',', ' ') }}
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                        <p class="text-[11px] text-slate-400 mb-1">Ali-Kamer gagne</p>
                        <p class="text-lg font-black text-white">
                            {{ number_format($simulation['platformProfit'], 0, ',', ' ') }}
                            <span class="text-[10px] font-semibold text-slate-400">FCFA</span>
                        </p>
                        <p class="text-[11px] text-slate-500 mt-1.5">
                            Protection + commission − agences
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                        <p class="text-[11px] text-slate-400 mb-1">Agences reçoivent</p>
                        <p class="text-lg font-black text-purple-300">
                            {{ number_format($simulation['agencyCommission'], 0, ',', ' ') }}
                            <span class="text-[10px] font-semibold text-purple-400">FCFA</span>
                        </p>
                        <p class="text-[11px] text-slate-500 mt-1.5">
                            Par commande traitée
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             FORMULAIRE PARAMÈTRES
        ========================================================== --}}

        <form method="POST" action="{{ route('admin.financial-engine.update') }}" id="settings-form">
            @csrf

            @php
                $groupLabels = [
                    'commissions' => 'Commissions et frais',
                    'timers' => 'Délais et timers',
                    'limites' => 'Limites et seuils',
                ];

                $groupIcons = [
                    'commissions' => ['bg-violet-50 text-violet-600', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1m8-6a8 8 0 11-16 0 8 8 0 0116 0z'],
                    'timers' => ['bg-blue-50 text-blue-600', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'limites' => ['bg-orange-50 text-orange-600', 'M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v3h8z'],
                ];
            @endphp

            <div class="space-y-4 mb-6">

                @foreach ($settings as $group => $items)

                    @php
                        [$iconBg, $iconPath] = $groupIcons[$group] ?? ['bg-slate-50 text-slate-600', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'];
                    @endphp

                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

                        <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2.5">

                            <div class="w-9 h-9 rounded-xl {{ $iconBg }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPath }}"/>
                                </svg>
                            </div>

                            <h2 class="text-sm font-black text-slate-800">
                                {{ $groupLabels[$group] ?? $group }}
                            </h2>

                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach ($items as $setting)
                                <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50/60 transition">

                                    <div class="flex-1 min-w-0">
                                        <label class="block text-xs font-bold text-slate-700">
                                            {{ $setting->label }}
                                        </label>
                                        @if ($setting->description)
                                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $setting->description }}</p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <input type="number" name="settings[{{ $setting->key }}]"
                                            value="{{ $setting->casted_value }}"
                                            step="{{ $setting->type === 'percentage' ? '0.1' : '1' }}" min="0"
                                            class="w-24 border border-slate-200 rounded-lg px-3 py-1.5
                                              text-sm text-right font-semibold text-slate-800
                                              focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                              transition">
                                        <span class="text-[11px] font-semibold text-slate-400 w-7">
                                            {{ $setting->typeLabel() }}
                                        </span>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                    </div>

                @endforeach

            </div>

            {{-- Barre d'action --}}
            <div class="sticky bottom-4 z-10">

                <div class="bg-white border border-slate-200 rounded-xl shadow-lg
                            px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                    <p class="flex items-center gap-2 text-[11px] text-slate-500">
                        <svg class="w-4 h-4 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z"/>
                        </svg>
                        Ces paramètres s'appliquent aux nouvelles commandes uniquement.
                        Les commandes existantes gardent leurs taux d'origine.
                    </p>

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2
                               bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                               text-sm px-6 py-2.5 rounded-xl transition
                               shadow-md shadow-indigo-200 flex-shrink-0">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>

                        Sauvegarder les paramètres

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection