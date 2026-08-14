@extends('base')

@section('title', 'Agence - ' . $agency->name)

@section('content')

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- =========================================================
             EN-TÊTE
        ========================================================== --}}
            <div class="mb-8">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>
                        <div class="flex items-center gap-3 mb-2">

                            <a href="{{ route('admin.agencies.index') }}" class="text-gray-500 hover:text-gray-700">
                                ← Agences
                            </a>

                            <span class="text-gray-300">/</span>

                            <span class="text-gray-500">
                                {{ $agency->name }}
                            </span>

                        </div>

                        <h1 class="text-3xl font-bold text-gray-900">
                            {{ $agency->name }}
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Gestion de l'agence, de ses comptoirs et de ses secrétaires.
                        </p>
                    </div>


                    {{-- STATUT AGENCE --}}
                    <div class="flex items-center gap-3">

                        @if ($agency->is_active)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Active
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                Désactivée
                            </span>
                        @endif


                        {{-- ACTIVER / DESACTIVER --}}
                        <form action="{{ route('admin.agencies.toggle', $agency) }}" method="POST">

                            @csrf
                            @method('POST')

                            <button type="submit"
                                class="px-4 py-2 rounded-lg border text-sm font-medium
                                {{ $agency->is_active
                                    ? 'border-red-200 text-red-600 hover:bg-red-50'
                                    : 'border-green-200 text-green-600 hover:bg-green-50' }}">

                                {{ $agency->is_active ? 'Désactiver' : 'Activer' }}

                            </button>

                        </form>

                    </div>

                </div>

            </div>


            {{-- =========================================================
             MESSAGES
        ========================================================== --}}

            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif


            @if ($errors->any())

                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">

                    <p class="font-semibold text-red-800 mb-2">
                        Une erreur est survenue :
                    </p>

                    <ul class="list-disc list-inside text-sm text-red-700">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =========================================================
             INFORMATIONS AGENCE
        ========================================================== --}}

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                {{-- Informations générales --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">

                    <div class="px-6 py-5 border-b border-gray-200">

                        <h2 class="text-lg font-semibold text-gray-900">
                            Informations de l'agence
                        </h2>

                    </div>

                    <div class="p-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">
                                    Nom
                                </p>

                                <p class="mt-1 text-gray-900 font-medium">
                                    {{ $agency->name }}
                                </p>
                            </div>


                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">
                                    Slug
                                </p>

                                <p class="mt-1 text-gray-700">
                                    {{ $agency->slug }}
                                </p>
                            </div>


                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">
                                    Téléphone
                                </p>

                                <p class="mt-1 text-gray-700">
                                    {{ $agency->contact_phone ?? 'Non renseigné' }}
                                </p>
                            </div>


                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">
                                    Email
                                </p>

                                <p class="mt-1 text-gray-700">
                                    {{ $agency->contact_email ?? 'Non renseigné' }}
                                </p>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Statistiques --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">

                    <div class="px-6 py-5 border-b border-gray-200">

                        <h2 class="text-lg font-semibold text-gray-900">
                            Vue d'ensemble
                        </h2>

                    </div>

                    <div class="p-6 space-y-5">

                        <div class="flex items-center justify-between">

                            <span class="text-gray-600">
                                Villes desservies
                            </span>

                            <span class="text-xl font-bold text-gray-900">
                                {{ $agency->cities->count() }}
                            </span>

                        </div>


                        <div class="flex items-center justify-between">

                            <span class="text-gray-600">
                                Comptoirs
                            </span>

                            <span class="text-xl font-bold text-gray-900">
                                {{ $agency->counters->count() }}
                            </span>

                        </div>


                        <div class="flex items-center justify-between">

                            <span class="text-gray-600">
                                Comptoirs actifs
                            </span>

                            <span class="text-xl font-bold text-green-600">
                                {{ $agency->counters->where('is_active', true)->count() }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
             VILLES DESSERVIES
        ========================================================== --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">

                <div class="px-6 py-5 border-b border-gray-200">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                Villes desservies
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Les villes dans lesquelles cette agence accepte les opérations.
                            </p>
                        </div>

                    </div>

                </div>


                <div class="p-6">

                    {{-- Ajouter une ville --}}

                    <form action="{{ route('admin.agencies.cities.add', $agency) }}" method="POST"
                        class="flex flex-col sm:flex-row gap-3 mb-6">

                        @csrf

                        <select name="city" required
                            class="flex-1 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                            <option value="">
                                Sélectionner une ville
                            </option>

                            @foreach ($cities as $city)
                                <option value="{{ $city }}">
                                    {{ $city }}
                                </option>
                            @endforeach

                        </select>

                        <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">

                            + Ajouter la ville

                        </button>

                    </form>


                    {{-- Liste des villes --}}

                    @if ($agency->cities->count())

                        <div class="flex flex-wrap gap-3">

                            @foreach ($agency->cities as $city)
                                <div
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg border
                                {{ $city->is_active ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">

                                    <span
                                        class="font-medium
                                    {{ $city->is_active ? 'text-green-800' : 'text-gray-600' }}">

                                        {{ $city->city }}

                                    </span>


                                    <form action="{{ route('admin.agencies.cities.toggle', $city) }}" method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="text-xs px-2 py-1 rounded
                                            {{ $city->is_active ? 'text-red-600 hover:bg-red-100' : 'text-green-600 hover:bg-green-100' }}">

                                            {{ $city->is_active ? 'Désactiver' : 'Activer' }}

                                        </button>

                                    </form>

                                </div>
                            @endforeach

                        </div>
                    @else
                        <p class="text-sm text-gray-500">
                            Aucune ville desservie pour le moment.
                        </p>

                    @endif

                </div>

            </div>


            {{-- =========================================================
             COMPTOIRS
        ========================================================== --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">

                <div class="px-6 py-5 border-b border-gray-200">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>

                            <h2 class="text-lg font-semibold text-gray-900">
                                Comptoirs
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Gestion des points de dépôt et de réception de l'agence.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-6">

                    {{-- =================================================
                     FORMULAIRE CREATION COMPTOIR
                ================================================== --}}

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-6">

                        <h3 class="font-semibold text-gray-900 mb-4">
                            Ajouter un comptoir
                        </h3>


                        <form action="{{ route('admin.agencies.counters.store', $agency) }}" method="POST">

                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Ville
                                    </label>

                                    <select name="city" required
                                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                                        <option value="">
                                            Choisir
                                        </option>

                                        @foreach ($agency->cities->where('is_active', true) as $city)
                                            <option value="{{ $city->city }}">
                                                {{ $city->city }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>


                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Quartier
                                    </label>

                                    <input type="text" name="district" required
                                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="Ex : Bastos">

                                </div>


                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Point de repère
                                    </label>

                                    <input type="text" name="landmark"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="Ex : près de...">

                                </div>


                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Téléphone
                                    </label>

                                    <input type="text" name="phone"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="6XXXXXXXX">

                                </div>

                            </div>


                            <div class="mt-4">

                                <button type="submit"
                                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">

                                    + Créer le comptoir

                                </button>

                            </div>

                        </form>

                    </div>


                    {{-- =================================================
                     LISTE DES COMPTOIRS
                ================================================== --}}

                    @if ($agency->counters->count())

                        <div class="space-y-5">

                            @foreach ($agency->counters as $counter)
                                <div class="border border-gray-200 rounded-xl overflow-hidden">

                                    {{-- En-tête comptoir --}}

                                    <div class="bg-gray-50 px-5 py-4">

                                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                            <div>

                                                <div class="flex items-center gap-3">

                                                    <h3 class="font-semibold text-gray-900">
                                                        {{ $counter->full_name }}
                                                    </h3>

                                                    @if ($counter->is_active)
                                                        <span
                                                            class="text-xs px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                                                            Actif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="text-xs px-2.5 py-1 rounded-full bg-red-100 text-red-700">
                                                            Désactivé
                                                        </span>
                                                    @endif

                                                </div>

                                                <p class="text-sm text-gray-500 mt-1">

                                                    {{ $counter->district }}

                                                    @if ($counter->landmark)
                                                        · {{ $counter->landmark }}
                                                    @endif

                                                    @if ($counter->phone)
                                                        · {{ $counter->phone }}
                                                    @endif

                                                </p>

                                            </div>


                                            {{-- Toggle comptoir --}}

                                            <form action="{{ route('admin.agencies.counters.toggle', $counter) }}"
                                                method="POST">

                                                @csrf
                                                @method('POST')

                                                <button type="submit"
                                                    class="px-4 py-2 rounded-lg border text-sm
                                                    {{ $counter->is_active
                                                        ? 'border-red-200 text-red-600 hover:bg-red-50'
                                                        : 'border-green-200 text-green-600 hover:bg-green-50' }}">

                                                    {{ $counter->is_active ? 'Désactiver' : 'Activer' }}

                                                </button>

                                            </form>

                                        </div>

                                    </div>


                                    {{-- Corps du comptoir --}}

                                    <div class="p-5">

                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                                            {{-- --------------------------------
                                             SECRETARIAT
                                        --------------------------------- --}}

                                            <div>

                                                <h4 class="text-sm font-semibold text-gray-900 mb-3">
                                                    Secrétaire affecté
                                                </h4>


                                                @if ($counter->secretary)
                                                    @php
                                                        $secretaryAssignment = $counter->secretary;
                                                        $secretary = $secretaryAssignment->secretary;
                                                    @endphp


                                                    <div
                                                        class="flex items-center justify-between p-4 rounded-lg bg-blue-50 border border-blue-100">

                                                        <div>

                                                            <p class="font-medium text-gray-900">
                                                                {{ $secretary->name }}
                                                            </p>

                                                            <p class="text-sm text-gray-500 mt-1">
                                                                {{ $secretary->phone }}
                                                            </p>

                                                            @if ($secretary->email)
                                                                <p class="text-sm text-gray-500">
                                                                    {{ $secretary->email }}
                                                                </p>
                                                            @endif

                                                        </div>


                                                        @if ($secretaryAssignment->is_primary)
                                                            <span
                                                                class="text-xs px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">
                                                                Principal
                                                            </span>
                                                        @endif

                                                    </div>
                                                @else
                                                    {{-- Aucun secrétaire --}}

                                                    <div class="p-4 rounded-lg bg-yellow-50 border border-yellow-200">

                                                        <p class="text-sm font-medium text-yellow-800">
                                                            Aucun secrétaire affecté.
                                                        </p>

                                                        <p class="text-xs text-yellow-700 mt-1">
                                                            Créez un compte secrétaire pour ce comptoir.
                                                        </p>

                                                    </div>


                                                    {{-- Formulaire secrétaire --}}

                                                    <form action="{{ route('admin.agencies.secretary.store', $counter) }}"
                                                        method="POST" class="mt-4 space-y-3">

                                                        @csrf

                                                        <div>

                                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                Nom complet
                                                            </label>

                                                            <input type="text" name="name" required
                                                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                                                        </div>


                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                                            <div>

                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                                    Téléphone
                                                                </label>

                                                                <input type="text" name="phone" required
                                                                    pattern="6[0-9]{8}" placeholder="6XXXXXXXX"
                                                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                                                            </div>


                                                            <div>

                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                                    Email
                                                                </label>

                                                                <input type="email" name="email"
                                                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                                                            </div>

                                                        </div>


                                                        <div>

                                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                Mot de passe
                                                            </label>

                                                            <input type="password" name="password" required
                                                                minlength="8"
                                                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                                                        </div>


                                                        <button type="submit"
                                                            class="w-full px-4 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">

                                                            Créer et affecter le secrétaire

                                                        </button>

                                                    </form>
                                                @endif

                                            </div>


                                            {{-- --------------------------------
                                             EXPEDITIONS
                                        --------------------------------- --}}

                                            <div>

                                                <h4 class="text-sm font-semibold text-gray-900 mb-3">
                                                    Activité du comptoir
                                                </h4>


                                                <div class="grid grid-cols-2 gap-3">

                                                    <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">

                                                        <p class="text-xs text-gray-500">
                                                            Colis déposés
                                                        </p>

                                                        <p class="text-2xl font-bold text-gray-900 mt-1">
                                                            {{ $counter->shipmentsAsOrigin->count() }}
                                                        </p>

                                                    </div>


                                                    <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">

                                                        <p class="text-xs text-gray-500">
                                                            Colis reçus
                                                        </p>

                                                        <p class="text-2xl font-bold text-gray-900 mt-1">
                                                            {{ $counter->shipmentsAsDestination->count() }}
                                                        </p>

                                                    </div>

                                                </div>


                                                <div class="mt-4 text-sm text-gray-500">

                                                    <p>
                                                        <strong>Ville :</strong>
                                                        {{ $counter->city }}
                                                    </p>

                                                    <p class="mt-1">
                                                        <strong>Quartier :</strong>
                                                        {{ $counter->district }}
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="text-center py-10">

                            <p class="text-gray-500">
                                Aucun comptoir n'a encore été créé pour cette agence.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

            {{-- =========================================================
             INFORMATIONS TECHNIQUES
        ========================================================== --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200">

                <div class="px-6 py-5 border-b border-gray-200">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Informations système
                    </h2>

                </div>

                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">

                    <div>

                        <span class="text-gray-500">
                            Identifiant agence
                        </span>

                        <p class="font-mono text-gray-900 mt-1">
                            #{{ $agency->id }}
                        </p>

                    </div>


                    <div>

                        <span class="text-gray-500">
                            Créée le
                        </span>

                        <p class="text-gray-900 mt-1">
                            {{ $agency->created_at?->format('d/m/Y à H:i') }}
                        </p>

                    </div>


                    <div>

                        <span class="text-gray-500">
                            Dernière modification
                        </span>

                        <p class="text-gray-900 mt-1">
                            {{ $agency->updated_at?->format('d/m/Y à H:i') }}
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </div>

@endsection
