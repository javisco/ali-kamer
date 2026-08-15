@extends('base')

@section('title', 'Dashboard Admin')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">

            <div>
                <p class="text-sm font-medium text-indigo-600 mb-1">
                    Administration
                </p>

                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Tableau de bord
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Vue d'ensemble de l'activité de la plateforme.
                </p>
            </div>

            <div class="flex items-center gap-2">

                <div class="hidden sm:flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-600 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Système opérationnel
                </div>

            </div>

        </div>


        {{-- =========================================================
             KPI PRINCIPAUX
        ========================================================== --}}

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4">

            {{-- Commandes --}}

            <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            Commandes aujourd'hui
                        </p>

                        <p class="text-2xl sm:text-3xl font-bold text-slate-900 mt-2">
                            {{ $kpis['orders_today'] }}
                        </p>

                    </div>

                    <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M9 5h6M9 9h6m-8 4h10m-8 4h6M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                        </svg>
                    </div>

                </div>

            </div>


            {{-- CA --}}

            <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5 shadow-sm">

                <div class="flex items-start justify-between">

                    <div class="min-w-0">

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            CA aujourd'hui
                        </p>

                        <p class="text-xl sm:text-2xl font-bold text-indigo-600 mt-2 truncate">
                            {{ number_format($kpis['revenue_today'], 0, ',', ' ') }}
                            <span class="text-xs sm:text-sm font-semibold">FCFA</span>
                        </p>

                    </div>

                    <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v1m0 10v1m8-6a8 8 0 11-16 0 8 8 0 0116 0z"/>
                        </svg>

                    </div>

                </div>

            </div>


            {{-- Litiges --}}

            <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            Litiges ouverts
                        </p>

                        <p class="text-2xl sm:text-3xl font-bold mt-2
                            {{ $kpis['disputes_open'] > 0 ? 'text-red-600' : 'text-slate-900' }}">
                            {{ $kpis['disputes_open'] }}
                        </p>

                    </div>

                    <div class="w-9 h-9 rounded-lg
                        {{ $kpis['disputes_open'] > 0 ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-500' }}
                        flex items-center justify-center">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z"/>
                        </svg>

                    </div>

                </div>

                @if($kpis['disputes_open'] > 0)

                    <a href="{{ route('admin.disputes.index') }}"
                       class="inline-block mt-2 text-xs font-semibold text-red-600 hover:text-red-700">
                        Examiner les litiges →
                    </a>

                @endif

            </div>


            {{-- KYC --}}

            <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            KYC en attente
                        </p>

                        <p class="text-2xl sm:text-3xl font-bold mt-2
                            {{ $kpis['kyc_pending'] > 0 ? 'text-orange-600' : 'text-slate-900' }}">
                            {{ $kpis['kyc_pending'] }}
                        </p>

                    </div>

                    <div class="w-9 h-9 rounded-lg
                        {{ $kpis['kyc_pending'] > 0 ? 'bg-orange-50 text-orange-600' : 'bg-slate-100 text-slate-500' }}
                        flex items-center justify-center">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>
                        </svg>

                    </div>

                </div>

                @if($kpis['kyc_pending'] > 0)

                    <a href="{{ route('admin.kyc.index') }}"
                       class="inline-block mt-2 text-xs font-semibold text-orange-600 hover:text-orange-700">
                        Vérifier les dossiers →
                    </a>

                @endif

            </div>

        </div>


        {{-- =========================================================
             FINANCES
        ========================================================== --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-7">

            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            Fonds séquestrés
                        </p>

                        <p class="text-xl sm:text-2xl font-bold text-slate-800 mt-2">
                            {{ number_format($kpis['total_escrow'], 0, ',', ' ') }}
                            <span class="text-sm font-semibold text-slate-500">FCFA</span>
                        </p>

                    </div>

                    <div class="text-slate-400 text-sm">
                        Escrow
                    </div>

                </div>

            </div>


            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            Solde disponible vendeurs
                        </p>

                        <p class="text-xl sm:text-2xl font-bold text-emerald-600 mt-2">
                            {{ number_format($kpis['total_available'], 0, ',', ' ') }}
                            <span class="text-sm font-semibold text-emerald-600">FCFA</span>
                        </p>

                    </div>

                    <div class="text-xs font-semibold px-2 py-1 rounded-md bg-emerald-50 text-emerald-700">
                        Disponible
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             ACTIONS RAPIDES
        ========================================================== --}}

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-7">

            <div class="px-5 py-4 border-b border-slate-100">

                <h2 class="font-bold text-slate-800">
                    Accès rapide
                </h2>

            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y sm:divide-y-0 divide-slate-100">

                <a href="{{ route('admin.users.index') }}"
                   class="p-4 hover:bg-slate-50 transition group">

                    <div class="flex items-center gap-3">

                        <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-indigo-50 group-hover:text-indigo-600 transition">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm9 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                            </svg>

                        </div>

                        <div>
                            <p class="text-sm font-semibold text-slate-800">
                                Utilisateurs
                            </p>
                            <p class="text-xs text-slate-400">
                                Gérer les comptes
                            </p>
                        </div>

                    </div>

                </a>


                <a href="{{ route('admin.agencies.index') }}"
                   class="p-4 hover:bg-slate-50 transition group">

                    <div class="flex items-center gap-3">

                        <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-indigo-50 group-hover:text-indigo-600 transition">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 8h2m-2 4h2m2-4h2m-2 4h2"/>
                            </svg>

                        </div>

                        <div>
                            <p class="text-sm font-semibold text-slate-800">
                                Agences
                            </p>
                            <p class="text-xs text-slate-400">
                                Réseau logistique
                            </p>
                        </div>

                    </div>

                </a>


                <a href="{{ route('admin.kyc.index') }}"
                   class="p-4 hover:bg-slate-50 transition group">

                    <div class="flex items-center gap-3">

                        <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-orange-50 group-hover:text-orange-600 transition">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>
                            </svg>

                        </div>

                        <div>
                            <p class="text-sm font-semibold text-slate-800">
                                KYC
                            </p>
                            <p class="text-xs text-slate-400">
                                Vérification
                            </p>
                        </div>

                    </div>

                </a>


                <a href="{{ route('admin.disputes.index') }}"
                   class="p-4 hover:bg-slate-50 transition group">

                    <div class="flex items-center gap-3">

                        <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-red-50 group-hover:text-red-600 transition">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75 3L13.7 3.8a2 2 0 00-3.4 0z"/>
                            </svg>

                        </div>

                        <div>
                            <p class="text-sm font-semibold text-slate-800">
                                Litiges
                            </p>
                            <p class="text-xs text-slate-400">
                                Résolution
                            </p>
                        </div>

                    </div>

                </a>

            </div>

        </div>


        {{-- =========================================================
             ZONE ALERTES
        ========================================================== --}}

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-7">


            {{-- KYC --}}

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">

                    <div>

                        <h2 class="font-bold text-slate-800">
                            KYC à valider
                        </h2>

                        <p class="text-xs text-slate-400 mt-0.5">
                            Dossiers nécessitant une intervention
                        </p>

                    </div>

                    <a href="{{ route('admin.kyc.index') }}"
                       class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                        Voir tout →
                    </a>

                </div>


                @forelse($pendingKyc as $kyc)

                    <div class="flex items-center justify-between gap-4 px-5 py-3.5 border-b border-slate-50 last:border-0">

                        <div class="min-w-0">

                            <p class="text-sm font-semibold text-slate-800 truncate">
                                {{ $kyc->user->name }}
                            </p>

                            <p class="text-xs text-slate-400 mt-0.5">
                                Soumis {{ $kyc->created_at->diffForHumans() }}
                            </p>

                        </div>

                        <a href="{{ route('admin.kyc.show', $kyc) }}"
                           class="flex-shrink-0 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                            Examiner →
                        </a>

                    </div>

                @empty

                    <div class="px-5 py-10 text-center">

                        <div class="mx-auto w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                            ✓
                        </div>

                        <p class="text-sm font-medium text-slate-700">
                            Tout est à jour
                        </p>

                        <p class="text-xs text-slate-400 mt-1">
                            Aucun dossier KYC en attente.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- LITIGES --}}

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">

                    <div>

                        <h2 class="font-bold text-slate-800">
                            Litiges urgents
                        </h2>

                        <p class="text-xs text-slate-400 mt-0.5">
                            Dossiers nécessitant une intervention
                        </p>

                    </div>

                    <a href="{{ route('admin.disputes.index') }}"
                       class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                        Voir tout →
                    </a>

                </div>


                @forelse($openDisputes as $dispute)

                    <div class="flex items-center justify-between gap-4 px-5 py-3.5 border-b border-slate-50 last:border-0">

                        <div class="min-w-0">

                            <p class="text-sm font-semibold text-slate-800 truncate">
                                {{ $dispute->order->reference }}
                            </p>

                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $dispute->typeLabel() }}
                                · {{ $dispute->created_at->diffForHumans() }}
                            </p>

                        </div>

                        <a href="{{ route('admin.disputes.show', $dispute) }}"
                           class="flex-shrink-0 text-xs font-semibold text-red-600 hover:text-red-700">
                            Traiter →
                        </a>

                    </div>

                @empty

                    <div class="px-5 py-10 text-center">

                        <div class="mx-auto w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                            ✓
                        </div>

                        <p class="text-sm font-medium text-slate-700">
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

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">

                <div>

                    <h2 class="font-bold text-slate-800">
                        Commandes récentes
                    </h2>

                    <p class="text-xs text-slate-400 mt-0.5">
                        Dernières transactions enregistrées
                    </p>

                </div>

            </div>


            {{-- Desktop --}}

            <div class="hidden md:block overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-50 border-b border-slate-100">

                        <tr>

                            <th class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                Commande
                            </th>

                            <th class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                Acheteur
                            </th>

                            <th class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                Boutique
                            </th>

                            <th class="text-right px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                Montant
                            </th>

                            <th class="text-right px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
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
                                    'pending' => 'bg-slate-100 text-slate-600',
                                    'awaiting_payment' => 'bg-yellow-50 text-yellow-700',
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

                            <tr class="hover:bg-slate-50/70 transition">

                                <td class="px-5 py-4">

                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $order->reference }}
                                    </p>

                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $order->created_at->diffForHumans() }}
                                    </p>

                                </td>


                                <td class="px-5 py-4">

                                    <p class="text-sm text-slate-700">
                                        {{ $order->buyer->name }}
                                    </p>

                                </td>


                                <td class="px-5 py-4">

                                    <p class="text-sm text-slate-700">
                                        {{ $order->shop->name }}
                                    </p>

                                </td>


                                <td class="px-5 py-4 text-right">

                                    <p class="text-sm font-bold text-slate-800">
                                        {{ number_format($order->total_amount, 0, ',', ' ') }}
                                        <span class="text-xs font-medium text-slate-400">
                                            FCFA
                                        </span>
                                    </p>

                                </td>


                                <td class="px-5 py-4 text-right">

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold
                                        {{ $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-600' }}">

                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-5 py-12 text-center">

                                    <p class="text-sm font-medium text-slate-600">
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
                            'awaiting_payment' => 'bg-yellow-50 text-yellow-700',
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

                    <div class="p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div>

                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $order->reference }}
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    {{ $order->buyer->name }}
                                </p>

                            </div>

                            <span class="inline-flex px-2 py-1 rounded-full text-[10px] font-semibold
                                {{ $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-600' }}">

                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}

                            </span>

                        </div>


                        <div class="flex items-center justify-between mt-3">

                            <span class="text-xs text-slate-400">
                                {{ $order->shop->name }}
                            </span>

                            <span class="text-sm font-bold text-slate-800">
                                {{ number_format($order->total_amount, 0, ',', ' ') }}
                                FCFA
                            </span>

                        </div>

                    </div>

                @empty

                    <div class="px-5 py-12 text-center">

                        <p class="text-sm font-medium text-slate-600">
                            Aucune commande récente
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- =========================================================
             FOOTER
        ========================================================== --}}

        <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs text-slate-400">

            <p>
                Tableau de bord administrateur
            </p>

            <p>
                Données mises à jour selon l'activité actuelle.
            </p>

        </div>

    </div>

</div>

@endsection