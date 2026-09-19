@extends('layouts.admin')

@section('title', 'Agence - ' . $agency->name)

@section('content')

<div class="min-h-screen bg-slate-50 py-8"
     x-data="{ activeTab: 'overview' }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- =========================================================
             EN-TÊTE
        ========================================================== --}}
        <div class="relative overflow-hidden bg-white rounded-2xl border border-slate-100 shadow-sm">

            <div class="h-1 bg-gradient-to-r from-primary-600 via-[#FCD116] to-[#CE1126]"></div>

            <div class="p-5 sm:p-6">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>
                        <nav class="flex items-center gap-2 text-[10px] font-bold text-slate-400 mb-2">
                            <a href="{{ route('admin.agencies.index') }}"
                               class="hover:text-primary-600 transition">
                                Agences
                            </a>

                            <span>/</span>

                            <span class="text-slate-700">
                                {{ $agency->name }}
                            </span>
                        </nav>

                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                            {{ $agency->name }}
                        </h1>

                        <p class="text-xs text-slate-400 mt-1">
                            Gestion et configuration de l'agence partenaire.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">

                        <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-[10px] font-bold
                            {{ $agency->is_active
                                ? 'bg-primary-600/10 text-primary-600'
                                : 'bg-danger-50 text-[#CE1126]' }}">

                            <span class="w-1.5 h-1.5 rounded-full
                                {{ $agency->is_active ? 'bg-primary-600' : 'bg-[#CE1126]' }}">
                            </span>

                            {{ $agency->is_active ? 'Agence Active' : 'Désactivée' }}
                        </span>

                        <form action="{{ route('admin.agencies.toggle', $agency) }}" method="POST">
                            @csrf

                            <button type="submit"
                                    class="px-4 py-2 rounded-xl text-[10px] font-bold border transition shadow-sm
                                    {{ $agency->is_active
                                        ? 'border-warning-200 bg-warning-50 text-warning-700 hover:bg-warning-100'
                                        : 'border-primary-600/20 bg-primary-600/5 text-primary-600 hover:bg-primary-600/10' }}">

                                {{ $agency->is_active ? "Désactiver l'agence" : "Activer l'agence" }}
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- =========================================================
             MESSAGES
        ========================================================== --}}
        @if (session('success'))
            <div class="p-4 bg-primary-600/5 border border-primary-600/20 text-primary-700 rounded-xl text-sm font-medium flex items-center justify-between">

                <span>{{ session('success') }}</span>

                <button onclick="this.parentElement.remove()"
                        class="text-primary-600 hover:text-primary-700">
                    ✕
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-danger-50 border border-danger-200 text-[#CE1126] rounded-xl text-sm font-medium space-y-1">

                <p class="font-black">
                    Veuillez corriger les erreurs suivantes :
                </p>

                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        {{-- =========================================================
             RAPPORT DES GAINS
        ========================================================== --}}
        <a href="{{ route('admin.agencies.history', ['agency' => $agency]) }}"
           class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-center justify-between hover:shadow-md hover:border-primary-600/20 transition">

            <div class="flex items-center gap-4">

                <div class="w-11 h-11 rounded-2xl bg-primary-600/10 text-primary-600 flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>

                </div>

                <div>
                    <h4 class="font-black text-slate-900 group-hover:text-primary-600 transition">
                        Consulter l'historique et les gains
                    </h4>

                    <p class="text-xs text-slate-400 mt-0.5">
                        Suivi détaillé des transactions et commissions générées
                    </p>
                </div>

            </div>

            <div class="hidden sm:flex items-center text-xs font-bold text-primary-600 gap-1 group-hover:translate-x-1 transition">
                <span>Voir le rapport</span>
                <span>→</span>
            </div>
        </a>

        {{-- =========================================================
             ONGLETS
        ========================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <div class="overflow-x-auto">
                <div class="flex min-w-max border-b border-slate-100 px-2">

                    <button
                        @click="activeTab = 'overview'"
                        :class="activeTab === 'overview'
                            ? 'border-primary-600 text-primary-600 font-black'
                            : 'border-transparent text-slate-500 hover:text-slate-800'"
                        class="px-4 py-3 border-b-2 transition flex items-center gap-2 text-xs">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>

                        Vue d'ensemble & Params
                    </button>

                    <button
                        @click="activeTab = 'counters'"
                        :class="activeTab === 'counters'
                            ? 'border-primary-600 text-primary-600 font-black'
                            : 'border-transparent text-slate-500 hover:text-slate-800'"
                        class="px-4 py-3 border-b-2 transition flex items-center gap-2 text-xs">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>

                        Comptoirs ({{ $agency->counters->count() }})
                    </button>

                    <button
                        @click="activeTab = 'cities'"
                        :class="activeTab === 'cities'
                            ? 'border-primary-600 text-primary-600 font-black'
                            : 'border-transparent text-slate-500 hover:text-slate-800'"
                        class="px-4 py-3 border-b-2 transition flex items-center gap-2 text-xs">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>

                        Villes Desservies ({{ $agency->cities->count() }})
                    </button>

                </div>
            </div>

        </div>

        {{-- =========================================================
             TAB 1 : OVERVIEW
        ========================================================== --}}
        <div x-show="activeTab === 'overview'" class="space-y-6">

            {{-- Métriques --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            Villes desservies
                        </p>

                        <p class="text-2xl font-black text-slate-900 mt-1">
                            {{ $agency->cities->count() }}
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-primary-600/10 text-primary-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            Total comptoirs
                        </p>

                        <p class="text-2xl font-black text-slate-900 mt-1">
                            {{ $agency->counters->count() }}
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-[#FCD116]/15 text-warning-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            Comptoirs actifs
                        </p>

                        <p class="text-2xl font-black text-primary-600 mt-1">
                            {{ $agency->counters->where('is_active', true)->count() }}
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-primary-600/10 text-primary-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

            </div>

            {{-- Informations + MoMo --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- Informations générales --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">

                    <h3 class="text-base font-black text-slate-900 mb-4">
                        Informations générales
                    </h3>

                    <form action="{{ route('admin.agencies.update', $agency) }}"
                          method="POST"
                          class="space-y-4">

                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Nom *
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $agency->name) }}"
                                   required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 transition outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Téléphone
                                </label>

                                <input type="text"
                                       name="contact_phone"
                                       value="{{ old('contact_phone', $agency->contact_phone) }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 transition outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Email
                                </label>

                                <input type="email"
                                       name="contact_email"
                                       value="{{ old('contact_email', $agency->contact_email) }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 transition outline-none">
                            </div>

                        </div>

                        <button type="submit"
                                class="w-full py-2.5 bg-primary-600 text-white font-bold rounded-xl text-sm hover:bg-primary-700 shadow-sm transition">
                            Enregistrer les modifications
                        </button>

                    </form>
                </div>

                {{-- MoMo --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">

                    <div>
                        <h3 class="text-base font-black text-slate-900">
                            Numéro MoMo
                        </h3>

                        <p class="text-xs text-slate-400 mt-0.5">
                            Configuration du compte de réception des paiements.
                        </p>
                    </div>

                    <form method="POST"
                          action="{{ route('admin.agencies.momo.update', $agency) }}"
                          class="space-y-4 pt-1">

                        @csrf

                        <div class="flex flex-wrap gap-5">

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio"
                                       name="momo_operator"
                                       value="mtn"
                                       {{ $agency->momo_operator === 'mtn' ? 'checked' : '' }}
                                       class="text-warning focus:ring-warning">

                                <span class="text-sm font-bold text-warning-700">
                                    MTN Mobile Money
                                </span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio"
                                       name="momo_operator"
                                       value="orange"
                                       {{ $agency->momo_operator === 'orange' ? 'checked' : '' }}
                                       class="text-accent-500 focus:ring-accent-500">

                                <span class="text-sm font-bold text-accent-600">
                                    Orange Money
                                </span>
                            </label>

                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Numéro de téléphone
                            </label>

                            <input type="tel"
                                   name="phone_momo"
                                   value="{{ $agency->phone_momo }}"
                                   placeholder="6XXXXXXXX"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 transition outline-none">
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 bg-slate-900 text-white font-bold rounded-xl text-sm hover:bg-primary-600 shadow-sm transition">
                            Sauvegarder la configuration MoMo
                        </button>

                    </form>
                </div>

            </div>

            {{-- Manager --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">

                <h3 class="text-base font-black text-slate-900 mb-1">
                    Compte Manager Agence
                </h3>

                <p class="text-xs text-slate-400 mb-5">
                    Administrateur principal rattaché à la gestion de cette agence.
                </p>

                @php
                    $manager = \App\Models\User::where('role', 'agency_manager')
                        ->where('agency_id', $agency->id)
                        ->first();
                @endphp

                @if ($manager)

                    <form method="POST"
                          action="{{ route('admin.agencies.manager.update', $manager) }}"
                          class="space-y-4">

                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Nom complet
                                </label>

                                <input type="text"
                                       name="name"
                                       value="{{ $manager->name }}"
                                       required
                                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 transition outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Téléphone
                                </label>

                                <input type="tel"
                                       name="phone"
                                       value="{{ $manager->phone }}"
                                       required
                                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 transition outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       value="{{ $manager->email }}"
                                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 transition outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                    Statut
                                </label>

                                <select name="status"
                                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 transition outline-none">

                                    <option value="active" {{ $manager->status === 'active' ? 'selected' : '' }}>
                                        Actif
                                    </option>

                                    <option value="suspended" {{ $manager->status === 'suspended' ? 'selected' : '' }}>
                                        Suspendu
                                    </option>

                                </select>
                            </div>

                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Remplacer le mot de passe
                                <span class="normal-case tracking-normal text-slate-300 font-normal">
                                    (laisser vide pour conserver)
                                </span>
                            </label>

                            <input type="text"
                                   name="password"
                                   placeholder="Nouveau mot de passe"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 transition outline-none">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs rounded-xl transition">
                                Mettre à jour le compte manager
                            </button>
                        </div>

                    </form>

                @else

                    <div class="p-4 bg-warning-50 border border-warning-200 rounded-xl text-warning-800 text-xs mb-4">
                        Aucun compte manager configuré pour cette agence.
                    </div>

                    <form method="POST"
                          action="{{ route('admin.agencies.manager.store', $agency) }}"
                          class="space-y-4">

                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

                            <input type="text"
                                   name="name"
                                   required
                                   placeholder="Nom complet"
                                   class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">

                            <input type="tel"
                                   name="phone"
                                   required
                                   placeholder="N° Téléphone"
                                   class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">

                            <input type="text"
                                   name="password"
                                   required
                                   placeholder="Mot de passe"
                                   class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">

                            <input type="email"
                                   name="email"
                                   placeholder="Email (optionnel)"
                                   class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">

                        </div>

                        <button type="submit"
                                class="w-full py-2.5 bg-primary-600 text-white font-bold rounded-xl text-xs hover:bg-primary-700 transition">
                            Créer le compte manager
                        </button>

                    </form>

                @endif
            </div>

            {{-- Métadonnées --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex flex-wrap gap-4 items-center justify-between text-[10px] text-slate-400">

                <span>
                    ID Agence :
                    <strong class="text-slate-700">#{{ $agency->id }}</strong>
                </span>

                <span>
                    Créée le :
                    <strong class="text-slate-700">
                        {{ $agency->created_at?->format('d/m/Y à H:i') }}
                    </strong>
                </span>

                <span>
                    Dernière mise à jour :
                    <strong class="text-slate-700">
                        {{ $agency->updated_at?->format('d/m/Y à H:i') }}
                    </strong>
                </span>

            </div>

        </div>

        {{-- =========================================================
             TAB 2 : COMPTOIRS
        ========================================================== --}}
        <div x-show="activeTab === 'counters'"
             class="space-y-6"
             x-cloak>

            {{-- Création comptoir --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">

                <h3 class="text-base font-black text-slate-900 mb-1">
                    Ajouter un nouveau comptoir
                </h3>

                <p class="text-xs text-slate-400 mb-5">
                    Ajoutez un point de présence pour cette agence.
                </p>

                <form action="{{ route('admin.agencies.counters.store', $agency) }}"
                      method="POST"
                      class="space-y-4">

                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Ville *
                            </label>

                            <select name="city"
                                    required
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">

                                <option value="">Sélectionner</option>

                                @foreach ($agency->cities->where('is_active', true) as $city)
                                    <option value="{{ $city->city }}">
                                        {{ $city->city }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Quartier *
                            </label>

                            <input type="text"
                                   name="district"
                                   required
                                   placeholder="Ex : Bastos"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Point de repère
                            </label>

                            <input type="text"
                                   name="landmark"
                                   placeholder="Ex : Face station"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Téléphone
                            </label>

                            <input type="text"
                                   name="phone"
                                   placeholder="6XXXXXXXX"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                        </div>

                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-5 py-2.5 bg-primary-600 text-white font-bold text-sm rounded-xl hover:bg-primary-700 transition">
                            + Créer le comptoir
                        </button>
                    </div>

                </form>
            </div>

            {{-- Liste comptoirs --}}
            @if ($agency->counters->count())

                <div class="space-y-4">

                    @foreach ($agency->counters as $counter)

                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

                            <div class="px-5 sm:px-6 py-4 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between gap-3">

                                <div class="flex flex-wrap items-center gap-3">

                                    <h4 class="font-black text-slate-900">
                                        {{ $counter->full_name }}
                                    </h4>

                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold
                                        {{ $counter->is_active
                                            ? 'bg-primary-600/10 text-primary-600'
                                            : 'bg-danger-50 text-[#CE1126]' }}">

                                        <span class="w-1.5 h-1.5 rounded-full
                                            {{ $counter->is_active ? 'bg-primary-600' : 'bg-[#CE1126]' }}">
                                        </span>

                                        {{ $counter->is_active ? 'Actif' : 'Inactif' }}
                                    </span>

                                </div>

                                <form action="{{ route('admin.agencies.counters.toggle', $counter) }}"
                                      method="POST">

                                    @csrf

                                    <button type="submit"
                                            class="px-3 py-1.5 text-[10px] font-bold rounded-xl border transition
                                            {{ $counter->is_active
                                                ? 'border-warning-200 bg-warning-50 text-warning-700 hover:bg-warning-100'
                                                : 'border-primary-600/20 bg-primary-600/5 text-primary-600 hover:bg-primary-600/10' }}">

                                        {{ $counter->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>

                                </form>
                            </div>

                            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

                                {{-- Secrétaire --}}
                                <div class="space-y-3">

                                    <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-wider">
                                        Secrétaire affecté
                                    </h5>

                                    @if ($counter->secretary)

                                        @php
                                            $secretaryAssignment = $counter->secretary;
                                            $secretary = $secretaryAssignment->secretary;
                                        @endphp

                                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">

                                            <div>
                                                <p class="font-black text-slate-900 text-sm">
                                                    {{ $secretary->name }}
                                                </p>

                                                <p class="text-xs text-slate-500 mt-1">
                                                    📞 {{ $secretary->phone }}
                                                </p>

                                                @if ($secretary->email)
                                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                                        {{ $secretary->email }}
                                                    </p>
                                                @endif
                                            </div>

                                            @if ($secretaryAssignment->is_primary)
                                                <span class="px-2.5 py-1 rounded-full text-[9px] font-bold bg-primary-600/10 text-primary-600 border border-primary-600/10">
                                                    Principal
                                                </span>
                                            @endif

                                        </div>

                                    @else

                                        <div class="p-4 bg-warning-50 border border-warning-100 rounded-xl text-warning-800 text-xs">
                                            Aucun secrétaire n'est actuellement rattaché à ce comptoir.
                                        </div>

                                        <form action="{{ route('admin.agencies.secretary.store', $counter) }}"
                                              method="POST"
                                              class="space-y-3 pt-2">

                                            @csrf

                                            <input type="text"
                                                   name="name"
                                                   required
                                                   placeholder="Nom complet"
                                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">

                                            <div class="grid grid-cols-2 gap-2">

                                                <input type="text"
                                                       name="phone"
                                                       required
                                                       pattern="6[0-9]{8}"
                                                       placeholder="6XXXXXXXX"
                                                       class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">

                                                <input type="email"
                                                       name="email"
                                                       placeholder="Email"
                                                       class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">

                                            </div>

                                            <input type="password"
                                                   name="password"
                                                   required
                                                   minlength="8"
                                                   placeholder="Mot de passe"
                                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">

                                            <button type="submit"
                                                    class="w-full py-2.5 bg-slate-900 text-white font-bold rounded-xl text-xs hover:bg-primary-600 transition">
                                                Créer & Affecter
                                            </button>

                                        </form>

                                    @endif

                                </div>

                                {{-- Activité --}}
                                <div class="space-y-3">

                                    <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-wider">
                                        Activité du comptoir
                                    </h5>

                                    <div class="grid grid-cols-2 gap-3">

                                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                            <span class="text-[10px] text-slate-400 font-bold">
                                                Colis déposés
                                            </span>

                                            <p class="text-xl font-black text-slate-900 mt-1">
                                                {{ $counter->shipmentsAsOrigin->count() }}
                                            </p>
                                        </div>

                                        <div class="p-4 rounded-xl bg-primary-600/5 border border-primary-600/10 text-center">
                                            <span class="text-[10px] text-slate-400 font-bold">
                                                Colis reçus
                                            </span>

                                            <p class="text-xl font-black text-primary-600 mt-1">
                                                {{ $counter->shipmentsAsDestination->count() }}
                                            </p>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center text-slate-400 text-sm">
                    Aucun comptoir n'a encore été enregistré.
                </div>

            @endif

        </div>

        {{-- =========================================================
             TAB 3 : VILLES
        ========================================================== --}}
        <div x-show="activeTab === 'cities'"
             class="space-y-6"
             x-cloak>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">

                <h3 class="text-base font-black text-slate-900 mb-1">
                    Activer une nouvelle ville
                </h3>

                <p class="text-xs text-slate-400 mb-5">
                    Ajoutez une ville desservie par cette agence.
                </p>

                <form action="{{ route('admin.agencies.cities.add', $agency) }}"
                      method="POST"
                      class="flex flex-col sm:flex-row gap-3">

                    @csrf

                    <select name="city"
                            required
                            class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">

                        <option value="">
                            Sélectionner une ville dans la liste
                        </option>

                        @foreach ($cities as $city)
                            <option value="{{ $city }}">
                                {{ $city }}
                            </option>
                        @endforeach

                    </select>

                    <button type="submit"
                            class="px-5 py-2.5 bg-primary-600 text-white font-bold rounded-xl text-sm hover:bg-primary-700 transition">
                        + Ajouter la ville
                    </button>

                </form>
            </div>

            {{-- Villes --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">

                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-4">
                    Villes actuellement configurées
                </h4>

                @if($agency->cities->count())

                    <div class="flex flex-wrap gap-2">

                        @foreach ($agency->cities as $city)

                            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl border text-[10px] font-bold
                                {{ $city->is_active
                                    ? 'bg-primary-600/5 border-primary-600/15 text-primary-600'
                                    : 'bg-slate-50 border-slate-200 text-slate-400' }}">

                                <span>
                                    {{ $city->city }}
                                </span>

                                <form action="{{ route('admin.agencies.cities.toggle', $city) }}"
                                      method="POST">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="text-[9px] underline ml-1 hover:opacity-75">
                                        {{ $city->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>

                                </form>

                            </div>

                        @endforeach

                    </div>

                @else

                    <p class="text-xs text-slate-400">
                        Aucune ville n'est desservie par cette agence pour le moment.
                    </p>

                @endif

            </div>

        </div>

    </div>
</div>

@endsection