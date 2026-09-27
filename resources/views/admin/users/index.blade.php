@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')

@section('content')

<div class="min-h-screen bg-[#F7F9F8] py-8">

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">

        {{-- =========================================================
            EN-TÊTE
        ========================================================== --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-7">

            <div>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-[#006837] flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"
                                    stroke-width="1.8"/>
                            <path stroke-linecap="round"
                                  stroke-width="1.8"
                                  d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-2xl font-black text-[#0A1B12]">
                            Utilisateurs
                        </h1>

                        <p class="text-sm text-slate-500 mt-0.5">
                            Rechercher, filtrer et gérer les comptes Ali-Kamer.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">

                <div class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 shadow-sm">
                    <div class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                        Résultats
                    </div>

                    <div class="text-lg font-black text-[#006837]">
                        {{ number_format($users->total(), 0, ',', ' ') }}
                    </div>
                </div>

            </div>

        </div>


        {{-- =========================================================
            MESSAGE DE SUCCÈS
        ========================================================== --}}
        @if(session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200
                        bg-emerald-50 px-4 py-3 text-emerald-800">

                <svg class="w-5 h-5 mt-0.5 shrink-0"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="m5 13 4 4L19 7"/>
                </svg>

                <div class="text-sm font-semibold">
                    {{ session('success') }}
                </div>
            </div>
        @endif


        {{-- =========================================================
            ERREURS
        ========================================================== --}}
        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">

                <div class="flex items-center gap-2 text-red-700 font-bold text-sm mb-2">
                    <svg class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                    </svg>

                    Vérifiez les informations
                </div>

                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        {{-- =========================================================
            FILTRES
        ========================================================== --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-7">

            {{-- En-tête filtres --}}
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row
                        sm:items-center sm:justify-between gap-3">

                <div class="flex items-center gap-3">

                    <div class="w-9 h-9 rounded-xl bg-[#006837]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#006837]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M3 5h18M6 12h12M10 19h4"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-sm font-black text-slate-800">
                            Recherche et filtres
                        </h2>

                        <p class="text-xs text-slate-400">
                            Combinez plusieurs critères pour affiner les résultats.
                        </p>
                    </div>

                </div>

                @if(request()->hasAny([
                    'q',
                    'role',
                    'status',
                    'city',
                    'momo_operator',
                    'email_status',
                    'trust_level',
                    'trust_min',
                    'trust_max',
                    'shop',
                    'registered_from',
                    'registered_to',
                    'sort'
                ]))
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center gap-2 text-xs font-bold text-red-600
                              hover:text-red-700">

                        <svg class="w-4 h-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M6 18 18 6M6 6l12 12"/>
                        </svg>

                        Réinitialiser
                    </a>
                @endif

            </div>


            <form action="{{ route('admin.users.index') }}"
                  method="GET"
                  class="p-5 sm:p-6">

                {{-- =====================================================
                    RECHERCHE GÉNÉRALE
                ====================================================== --}}
                <div class="mb-5">

                    <label class="block text-xs font-black text-slate-600 mb-2">
                        Recherche générale
                    </label>

                    <div class="relative">

                        <svg class="absolute left-4 top-1/2 -translate-y-1/2
                                    w-5 h-5 text-slate-400"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1 8 8"/>
                        </svg>

                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Nom, email, téléphone ou numéro Mobile Money..."
                               class="w-full h-12 pl-12 pr-4 rounded-xl border border-slate-200
                                      bg-slate-50 text-sm text-slate-800
                                      placeholder:text-slate-400
                                      focus:bg-white focus:border-[#006837]
                                      focus:ring-4 focus:ring-[#006837]/10 outline-none transition">
                    </div>

                </div>


                {{-- =====================================================
                    FILTRES PRINCIPAUX
                ====================================================== --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- Rôle --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">
                            Rôle
                        </label>

                        <select name="role"
                                class="filter-select">
                            <option value="">Tous les rôles</option>

                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}"
                                    @selected(request('role') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Statut --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">
                            Statut
                        </label>

                        <select name="status"
                                class="filter-select">
                            <option value="">Tous les statuts</option>

                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}"
                                    @selected(request('status') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Ville --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">
                            Ville
                        </label>

                        <select name="city"
                                class="filter-select">
                            <option value="">Toutes les villes</option>

                            @foreach($cities as $city)
                                <option value="{{ $city }}"
                                    @selected(request('city') === $city)>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Mobile Money --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">
                            Opérateur Mobile Money
                        </label>

                        <select name="momo_operator"
                                class="filter-select">
                            <option value="">Tous les opérateurs</option>

                            <option value="mtn"
                                @selected(request('momo_operator') === 'mtn')>
                                MTN Mobile Money
                            </option>

                            <option value="orange"
                                @selected(request('momo_operator') === 'orange')>
                                Orange Money
                            </option>
                        </select>
                    </div>


                    {{-- Trust --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">
                            Niveau Trust Score
                        </label>

                        <select name="trust_level"
                                class="filter-select">

                            <option value="">Tous les niveaux</option>

                            <option value="critical"
                                @selected(request('trust_level') === 'critical')>
                                Critique — &lt; 20
                            </option>

                            <option value="low"
                                @selected(request('trust_level') === 'low')>
                                Faible — 20 à 39
                            </option>

                            <option value="medium"
                                @selected(request('trust_level') === 'medium')>
                                Moyen — 40 à 69
                            </option>

                            <option value="good"
                                @selected(request('trust_level') === 'good')>
                                Fiable — 70+
                            </option>

                        </select>
                    </div>


                    {{-- Boutique --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">
                            Boutique
                        </label>

                        <select name="shop"
                                class="filter-select">

                            <option value="">Avec ou sans boutique</option>

                            <option value="yes"
                                @selected(request('shop') === 'yes')>
                                Avec boutique
                            </option>

                            <option value="no"
                                @selected(request('shop') === 'no')>
                                Sans boutique
                            </option>

                        </select>
                    </div>


                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">
                            Email
                        </label>

                        <select name="email_status"
                                class="filter-select">

                            <option value="">Tous</option>

                            <option value="verified"
                                @selected(request('email_status') === 'verified')>
                                Email vérifié
                            </option>

                            <option value="unverified"
                                @selected(request('email_status') === 'unverified')>
                                Email non vérifié
                            </option>

                        </select>
                    </div>


                    {{-- Tri --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">
                            Trier par
                        </label>

                        <select name="sort"
                                class="filter-select">

                            <option value="recent"
                                @selected(request('sort', 'recent') === 'recent')>
                                Plus récents
                            </option>

                            <option value="oldest"
                                @selected(request('sort') === 'oldest')>
                                Plus anciens
                            </option>

                            <option value="name_asc"
                                @selected(request('sort') === 'name_asc')>
                                Nom A → Z
                            </option>

                            <option value="name_desc"
                                @selected(request('sort') === 'name_desc')>
                                Nom Z → A
                            </option>

                            <option value="trust_high"
                                @selected(request('sort') === 'trust_high')>
                                Trust Score élevé
                            </option>

                            <option value="trust_low"
                                @selected(request('sort') === 'trust_low')>
                                Trust Score faible
                            </option>

                        </select>
                    </div>

                </div>


                {{-- =====================================================
                    TRUST SCORE PERSONNALISÉ
                ====================================================== --}}
                <div class="mt-5 pt-5 border-t border-slate-100">

                    <div class="flex items-center gap-2 mb-3">

                        <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.8"
                                      d="M12 3v18M3 12h18"/>
                            </svg>
                        </div>

                        <span class="text-xs font-black text-slate-700">
                            Plage Trust Score personnalisée
                        </span>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2">
                                Score minimum
                            </label>

                            <input type="number"
                                   name="trust_min"
                                   min="0"
                                   max="100"
                                   value="{{ request('trust_min') }}"
                                   placeholder="Ex. 20"
                                   class="filter-input">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2">
                                Score maximum
                            </label>

                            <input type="number"
                                   name="trust_max"
                                   min="0"
                                   max="100"
                                   value="{{ request('trust_max') }}"
                                   placeholder="Ex. 69"
                                   class="filter-input">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2">
                                Inscrit à partir du
                            </label>

                            <input type="date"
                                   name="registered_from"
                                   value="{{ request('registered_from') }}"
                                   class="filter-input">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2">
                                Inscrit jusqu'au
                            </label>

                            <input type="date"
                                   name="registered_to"
                                   value="{{ request('registered_to') }}"
                                   class="filter-input">
                        </div>

                    </div>

                </div>


                {{-- =====================================================
                    ACTIONS
                ====================================================== --}}
                <div class="mt-6 flex flex-col sm:flex-row sm:items-center
                            justify-between gap-3">

                    <p class="text-xs text-slate-400">
                        Tous les critères sélectionnés seront appliqués simultanément.
                    </p>

                    <div class="flex items-center gap-2">

                        <a href="{{ route('admin.users.index') }}"
                           class="h-10 px-4 inline-flex items-center justify-center
                                  rounded-xl border border-slate-200 bg-white
                                  text-xs font-bold text-slate-600
                                  hover:bg-slate-50 transition">
                            Réinitialiser
                        </a>

                        <button type="submit"
                                class="h-10 px-5 inline-flex items-center justify-center gap-2
                                       rounded-xl bg-[#006837] hover:bg-[#004D28]
                                       text-white text-xs font-black
                                       shadow-sm transition">

                            <svg class="w-4 h-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1 8 8"/>
                            </svg>

                            Rechercher
                        </button>

                    </div>

                </div>

            </form>
        </div>


        {{-- =========================================================
            FILTRES ACTIFS
        ========================================================== --}}
        @php
            $activeFilters = [];

            if(request('q')) {
                $activeFilters[] = 'Recherche : ' . request('q');
            }

            if(request('role') && isset($roles[request('role')])) {
                $activeFilters[] = 'Rôle : ' . $roles[request('role')];
            }

            if(request('status') && isset($statuses[request('status')])) {
                $activeFilters[] = 'Statut : ' . $statuses[request('status')];
            }

            if(request('city')) {
                $activeFilters[] = 'Ville : ' . request('city');
            }

            if(request('momo_operator')) {
                $activeFilters[] = 'MoMo : ' . strtoupper(request('momo_operator'));
            }

            if(request('trust_level')) {
                $activeFilters[] = 'Trust : ' . request('trust_level');
            }

            if(request('trust_min') !== null && request('trust_min') !== '') {
                $activeFilters[] = 'Trust min : ' . request('trust_min');
            }

            if(request('trust_max') !== null && request('trust_max') !== '') {
                $activeFilters[] = 'Trust max : ' . request('trust_max');
            }

            if(request('shop')) {
                $activeFilters[] = request('shop') === 'yes'
                    ? 'Avec boutique'
                    : 'Sans boutique';
            }

            if(request('email_status')) {
                $activeFilters[] = request('email_status') === 'verified'
                    ? 'Email vérifié'
                    : 'Email non vérifié';
            }
        @endphp

        @if(count($activeFilters))
            <div class="flex flex-wrap items-center gap-2 mb-5">

                <span class="text-xs font-black text-slate-500 mr-1">
                    Filtres actifs :
                </span>

                @foreach($activeFilters as $filter)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full
                                 bg-[#006837]/10 text-[#006837]
                                 text-[11px] font-bold">
                        {{ $filter }}
                    </span>
                @endforeach

            </div>
        @endif


        {{-- =========================================================
            TABLE UTILISATEURS
        ========================================================== --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-5 sm:px-6 py-4 border-b border-slate-100
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

                <div>
                    <h2 class="text-sm font-black text-slate-800">
                        Liste des utilisateurs
                    </h2>

                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $users->firstItem() ?? 0 }}
                        –
                        {{ $users->lastItem() ?? 0 }}
                        sur
                        {{ number_format($users->total(), 0, ',', ' ') }}
                    </p>
                </div>

            </div>


            @if($users->count())

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1100px]">

                        <thead class="bg-[#F8FAF9] border-b border-slate-100">

                            <tr>

                                <th class="text-left px-6 py-4 text-[10px] uppercase
                                           tracking-wider font-black text-slate-400">
                                    Utilisateur
                                </th>

                                <th class="text-left px-4 py-4 text-[10px] uppercase
                                           tracking-wider font-black text-slate-400">
                                    Rôle
                                </th>

                                <th class="text-left px-4 py-4 text-[10px] uppercase
                                           tracking-wider font-black text-slate-400">
                                    Contact
                                </th>

                                <th class="text-left px-4 py-4 text-[10px] uppercase
                                           tracking-wider font-black text-slate-400">
                                    Trust
                                </th>

                                <th class="text-left px-4 py-4 text-[10px] uppercase
                                           tracking-wider font-black text-slate-400">
                                    Boutique
                                </th>

                                <th class="text-left px-4 py-4 text-[10px] uppercase
                                           tracking-wider font-black text-slate-400">
                                    Statut
                                </th>

                                <th class="text-right px-6 py-4 text-[10px] uppercase
                                           tracking-wider font-black text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach($users as $user)

                                @php
                                    $trust = (int) ($user->trust_score ?? 0);

                                    if($trust < 20) {
                                        $trustLabel = 'Critique';
                                        $trustClass = 'bg-red-50 text-red-700 border-red-100';
                                        $trustBar = 'bg-red-500';
                                    } elseif($trust < 40) {
                                        $trustLabel = 'Faible';
                                        $trustClass = 'bg-orange-50 text-orange-700 border-orange-100';
                                        $trustBar = 'bg-orange-500';
                                    } elseif($trust < 70) {
                                        $trustLabel = 'Moyen';
                                        $trustClass = 'bg-amber-50 text-amber-700 border-amber-100';
                                        $trustBar = 'bg-amber-500';
                                    } else {
                                        $trustLabel = 'Fiable';
                                        $trustClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                        $trustBar = 'bg-emerald-500';
                                    }

                                    $roleLabels = [
                                        'buyer' => 'Acheteur',
                                        'seller' => 'Vendeur',
                                        'secretary' => 'Secrétaire',
                                        'admin' => 'Administrateur',
                                        'agency_manager' => 'Gestionnaire agence',
                                    ];

                                    $roleLabel = $roleLabels[$user->role] ?? ucfirst($user->role ?? '—');

                                    $statusLabels = [
                                        'candidate' => 'Candidat',
                                        'active' => 'Actif',
                                        'suspended' => 'Suspendu',
                                        'banned' => 'Banni',
                                    ];

                                    $statusLabel = $statusLabels[$user->status] ?? ucfirst($user->status ?? '—');

                                    $statusClass = match($user->status) {
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'candidate' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'suspended' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'banned' => 'bg-red-50 text-red-700 border-red-100',
                                        default => 'bg-slate-50 text-slate-600 border-slate-100',
                                    };
                                @endphp


                                <tr class="hover:bg-slate-50/70 transition">


                                    {{-- Utilisateur --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div class="w-10 h-10 rounded-xl bg-[#006837]/10
                                                        text-[#006837] flex items-center justify-center
                                                        font-black text-sm shrink-0">
                                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                            </div>

                                            <div class="min-w-0">

                                                <div class="font-black text-sm text-slate-800 truncate max-w-[220px]">
                                                    {{ $user->name ?? '—' }}
                                                </div>

                                                <div class="text-[11px] text-slate-400">
                                                    #{{ $user->id }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Rôle --}}
                                    <td class="px-4 py-4">

                                        <span class="inline-flex px-2.5 py-1 rounded-lg
                                                     bg-slate-100 text-slate-700
                                                     text-[10px] font-black">
                                            {{ $roleLabel }}
                                        </span>

                                    </td>


                                    {{-- Contact --}}
                                    <td class="px-4 py-4">

                                        <div class="space-y-1">

                                            <div class="text-xs text-slate-700 font-semibold">
                                                {{ $user->email ?? '—' }}
                                            </div>

                                            @if($user->phone)
                                                <div class="text-[11px] text-slate-400">
                                                    {{ $user->phone }}
                                                </div>
                                            @endif

                                            @if($user->phone_momo)
                                                <div class="text-[11px] text-slate-400">
                                                    MoMo : {{ $user->phone_momo }}
                                                </div>
                                            @endif

                                        </div>

                                    </td>


                                    {{-- Trust --}}
                                    <td class="px-4 py-4">

                                        <div class="w-[130px]">

                                            <div class="flex items-center justify-between mb-1.5">

                                                <span class="text-sm font-black text-slate-800">
                                                    {{ $trust }}
                                                </span>

                                                <span class="text-[9px] font-bold px-1.5 py-0.5
                                                             rounded-md border {{ $trustClass }}">
                                                    {{ $trustLabel }}
                                                </span>

                                            </div>

                                            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full {{ $trustBar }} rounded-full"
                                                     style="width: {{ min(100, max(0, $trust)) }}%">
                                                </div>
                                            </div>

                                        </div>

                                    </td>


                                    {{-- Boutique --}}
                                    <td class="px-4 py-4">

                                        @if($user->shop)

                                            <div>
                                                <div class="text-xs font-black text-slate-700">
                                                    {{ $user->shop->name ?? 'Boutique' }}
                                                </div>

                                                <div class="text-[10px] text-emerald-600 font-bold">
                                                    Boutique associée
                                                </div>
                                            </div>

                                        @else

                                            <span class="text-xs text-slate-400">
                                                Aucune
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Statut --}}
                                    <td class="px-4 py-4">

                                        <span class="inline-flex px-2.5 py-1 rounded-lg border
                                                     text-[10px] font-black {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="px-6 py-4">

                                        <div class="flex justify-end items-center gap-2">

                                            {{-- Historique --}}
                                            <a href="{{ route('admin.users.history', $user) }}"
                                               title="Historique"
                                               class="w-9 h-9 rounded-xl bg-slate-100
                                                      text-slate-600 flex items-center justify-center
                                                      hover:bg-slate-200 transition">

                                                <svg class="w-4 h-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="1.8"
                                                          d="M3 12a9 9 0 1 0 3-6.7"/>
                                                    <path stroke-linecap="round"
                                                          stroke-width="1.8"
                                                          d="M3 4v6h6"/>
                                                    <path stroke-linecap="round"
                                                          stroke-width="1.8"
                                                          d="M12 7v5l3 2"/>
                                                </svg>

                                            </a>


                                            {{-- Réactiver --}}
                                            @if($user->status === 'banned' || $user->status === 'suspended')

                                                <form action="{{ route('admin.users.unban', $user) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Réactiver cet utilisateur ?');">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            title="Réactiver"
                                                            class="w-9 h-9 rounded-xl bg-emerald-50
                                                                   text-emerald-700 flex items-center justify-center
                                                                   hover:bg-emerald-100 transition">

                                                        <svg class="w-4 h-4"
                                                             fill="none"
                                                             viewBox="0 0 24 24"
                                                             stroke="currentColor">
                                                            <path stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="m5 12 4 4L19 6"/>
                                                        </svg>

                                                    </button>

                                                </form>

                                            @else

                                                {{-- Bannir --}}
                                                <form action="{{ route('admin.users.ban', $user) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Bannir cet utilisateur ?');">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            title="Bannir"
                                                            class="w-9 h-9 rounded-xl bg-red-50
                                                                   text-red-600 flex items-center justify-center
                                                                   hover:bg-red-100 transition">

                                                        <svg class="w-4 h-4"
                                                             fill="none"
                                                             viewBox="0 0 24 24"
                                                             stroke="currentColor">
                                                            <path stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  stroke-width="1.8"
                                                                  d="M18 6 6 18M6 6l12 12"/>
                                                        </svg>

                                                    </button>

                                                </form>

                                            @endif


                                            {{-- Blacklist --}}
                                            <button type="button"
                                                    title="Blacklist"
                                                    onclick="openBlacklistModal({{ $user->id }}, @js($user->name))"
                                                    class="w-9 h-9 rounded-xl bg-amber-50
                                                           text-amber-700 flex items-center justify-center
                                                           hover:bg-amber-100 transition">

                                                <svg class="w-4 h-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="1.8"
                                                          d="M18 18.5A6 6 0 0 1 6 18.5M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM4 20h16"/>
                                                </svg>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =====================================================
                    PAGINATION
                ====================================================== --}}
                <div class="px-5 sm:px-6 py-5 border-t border-slate-100">
                    {{ $users->links() }}
                </div>

            @else

                {{-- =====================================================
                    AUCUN RÉSULTAT
                ====================================================== --}}
                <div class="px-6 py-16 text-center">

                    <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100
                                flex items-center justify-center mb-4">

                        <svg class="w-7 h-7 text-slate-400"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.6"
                                  d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1 8 8"/>
                        </svg>

                    </div>

                    <h3 class="text-base font-black text-slate-700">
                        Aucun utilisateur trouvé
                    </h3>

                    <p class="text-sm text-slate-400 mt-1">
                        Aucun compte ne correspond aux critères sélectionnés.
                    </p>

                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center gap-2 mt-5 px-4 py-2.5
                              rounded-xl bg-[#006837] text-white
                              text-xs font-black hover:bg-[#004D28] transition">
                        Réinitialiser les filtres
                    </a>

                </div>

            @endif

        </div>

    </div>
</div>


{{-- =========================================================
    MODAL BLACKLIST
========================================================== --}}
<div id="blacklistModal"
     class="fixed inset-0 z-[100] hidden">

    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
         onclick="closeBlacklistModal()"></div>

    <div class="relative min-h-full flex items-center justify-center p-4">

        <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-100
                        flex items-center justify-between">

                <div>

                    <h3 class="text-lg font-black text-slate-900">
                        Blacklist définitif
                    </h3>

                    <p class="text-xs text-slate-400 mt-1">
                        Cette action doit être justifiée.
                    </p>

                </div>

                <button type="button"
                        onclick="closeBlacklistModal()"
                        class="w-9 h-9 rounded-xl bg-slate-100
                               text-slate-500 hover:bg-slate-200">

                    <svg class="w-4 h-4 mx-auto"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 6l12 12M6 18 18 6"/>
                    </svg>

                </button>

            </div>


            <form id="blacklistForm"
                  method="POST"
                  class="p-6">

                @csrf

                <div class="rounded-2xl bg-amber-50 border border-amber-100
                            p-4 mb-5">

                    <div class="flex gap-3">

                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>

                        </svg>

                        <div class="text-xs text-amber-800">
                            <div class="font-black">
                                Utilisateur concerné
                            </div>

                            <div id="blacklistUserName"
                                 class="font-semibold mt-0.5">
                            </div>
                        </div>

                    </div>

                </div>


                <label class="block text-xs font-black text-slate-600 mb-2">
                    Motif du blacklist
                </label>

                <textarea name="reason"
                          required
                          minlength="10"
                          rows="5"
                          placeholder="Expliquez clairement la raison du blacklist..."
                          class="w-full rounded-2xl border border-slate-200
                                 bg-slate-50 px-4 py-3 text-sm
                                 placeholder:text-slate-400
                                 focus:bg-white focus:border-[#006837]
                                 focus:ring-4 focus:ring-[#006837]/10
                                 outline-none resize-none"></textarea>


                <div class="flex justify-end gap-3 mt-6">

                    <button type="button"
                            onclick="closeBlacklistModal()"
                            class="px-4 py-2.5 rounded-xl border border-slate-200
                                   text-xs font-bold text-slate-600
                                   hover:bg-slate-50">
                        Annuler
                    </button>

                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-[#E30613]
                                   text-white text-xs font-black
                                   hover:bg-[#C50510]">
                        Blacklister définitivement
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<style>
    .filter-select,
    .filter-input {
        width: 100%;
        height: 42px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8faf9;
        padding: 0 12px;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        outline: none;
        transition: all .2s ease;
    }

    .filter-input {
        background: #f8faf9;
    }

    .filter-select:focus,
    .filter-input:focus {
        background: #fff;
        border-color: #006837;
        box-shadow: 0 0 0 4px rgba(0, 104, 55, .08);
    }

    @media (max-width: 640px) {
        .filter-select,
        .filter-input {
            height: 44px;
        }
    }
</style>


<script>
    function openBlacklistModal(userId, userName) {
        const modal = document.getElementById('blacklistModal');
        const form = document.getElementById('blacklistForm');
        const name = document.getElementById('blacklistUserName');

        /*
         * La route utilise le paramètre utilisateur directement.
         */
        form.action = "{{ url('/admin/utilisateurs') }}/" + userId + "/blacklist";

        name.textContent = userName;

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        const textarea = form.querySelector('textarea[name="reason"]');

        if (textarea) {
            setTimeout(() => textarea.focus(), 100);
        }
    }

    function closeBlacklistModal() {
        const modal = document.getElementById('blacklistModal');

        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');

        const form = document.getElementById('blacklistForm');

        if (form) {
            form.reset();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeBlacklistModal();
        }
    });
</script>

@endsection