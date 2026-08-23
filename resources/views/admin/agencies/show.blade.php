@extends('base')

@section('title', 'Agence - ' . $agency->name)

@section('content')
    <div class="min-h-screen bg-gray-50 py-8" x-data="{ activeTab: 'overview' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Fil d'Ariane & En-tête -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-medium text-gray-400 mb-1">
                        <a href="{{ route('admin.agencies.index') }}" class="hover:text-gray-600 transition">Agences</a>
                        <span>/</span>
                        <span class="text-gray-800 font-semibold">{{ $agency->name }}</span>
                    </nav>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $agency->name }}</h1>
                </div>

                <!-- Statut & Actions Rapides -->
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1.5 {{ $agency->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        <span class="w-2 h-2 rounded-full {{ $agency->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                        {{ $agency->is_active ? 'Agence Active' : 'Désactivée' }}
                    </span>

                    <form action="{{ route('admin.agencies.toggle', $agency) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold border transition shadow-sm cursor-pointer {{ $agency->is_active ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                            {{ $agency->is_active ? 'Désactiver l\'agence' : 'Activer l\'agence' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Messages Flash & Erreurs -->
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">✕</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium space-y-1">
                    <p class="font-bold">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Banner Rapport des Gains -->
            <a href="{{ route('admin.agencies.history', ['agency' => $agency]) }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between hover:shadow-md hover:border-indigo-100 transition block">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition">Consulter l'historique et les gains</h4>
                        <p class="text-xs text-gray-400">Suivi détaillé des transactions et commissions générées</p>
                    </div>
                </div>
                <div class="flex items-center text-sm font-semibold text-indigo-600 gap-1 group-hover:translate-x-1 transition-transform">
                    <span>Voir le rapport</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <!-- Navigation par Onglets -->
            <div class="border-b border-gray-200 flex gap-6 text-sm font-medium">
                <button @click="activeTab = 'overview'" 
                        :class="activeTab === 'overview' ? 'border-red-600 text-red-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="pb-3 border-b-2 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Vue d'ensemble & Params
                </button>

                <button @click="activeTab = 'counters'" 
                        :class="activeTab === 'counters' ? 'border-red-600 text-red-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="pb-3 border-b-2 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Comptoirs ({{ $agency->counters->count() }})
                </button>

                <button @click="activeTab = 'cities'" 
                        :class="activeTab === 'cities' ? 'border-red-600 text-red-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="pb-3 border-b-2 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Villes Desservies ({{ $agency->cities->count() }})
                </button>
            </div>

            <!-- TAB 1: VUE D'ENSEMBLE & CONFIGURATION -->
            <div x-show="activeTab === 'overview'" class="space-y-6">
                <!-- Cartes Métriques -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-400">Villes desservies</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $agency->cities->count() }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg></div>
                    </div>
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-400">Total Comptoirs</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $agency->counters->count() }}</p>
                        </div>
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                    </div>
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-400">Comptoirs Actifs</p>
                            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $agency->counters->where('is_active', true)->count() }}</p>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Édition des informations -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-base font-bold text-gray-900 mb-4">Informations Générales</h3>
                        <form action="{{ route('admin.agencies.update', $agency) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nom *</label>
                                <input type="text" name="name" value="{{ old('name', $agency->name) }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition outline-none">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Téléphone</label>
                                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $agency->contact_phone) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Email</label>
                                    <input type="email" name="contact_email" value="{{ old('contact_email', $agency->contact_email) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition outline-none">
                                </div>
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-red-600 text-white font-semibold rounded-xl text-sm hover:bg-red-700 shadow-sm transition">
                                Enregistrer les modifications
                            </button>
                        </form>
                    </div>

                    <!-- Numéro MoMo (Retraits) -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Numéro MoMo (Retraits)</h3>
                            <p class="text-xs text-gray-400">Configuration du compte de réception des paiements.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.agencies.momo.update', $agency) }}" class="space-y-4 pt-2">
                            @csrf
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="momo_operator" value="mtn" {{ $agency->momo_operator === 'mtn' ? 'checked' : '' }} class="text-yellow-500 focus:ring-yellow-400">
                                    <span class="text-sm font-bold text-yellow-700">MTN Mobile Money</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="momo_operator" value="orange" {{ $agency->momo_operator === 'orange' ? 'checked' : '' }} class="text-orange-500 focus:ring-orange-400">
                                    <span class="text-sm font-bold text-orange-600">Orange Money</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Numéro de téléphone</label>
                                <input type="tel" name="phone_momo" value="{{ $agency->phone_momo }}" placeholder="6XXXXXXXX" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 transition outline-none">
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-gray-900 text-white font-semibold rounded-xl text-sm hover:bg-black shadow-sm transition">
                                Sauvegarder la configuration MoMo
                            </button>
                        </form>
                    </div>

                </div>

                <!-- Gestion du Manager de l'Agence -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-1">Compte Manager Agence</h3>
                    <p class="text-xs text-gray-400 mb-4">Administrateur principal rattaché à la gestion de cette agence.</p>

                    @php
                        $manager = \App\Models\User::where('role', 'agency_manager')->where('agency_id', $agency->id)->first();
                    @endphp

                    @if ($manager)
                        <form method="POST" action="{{ route('admin.agencies.manager.update', $manager) }}" class="space-y-4">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nom complet</label>
                                    <input type="text" name="name" value="{{ $manager->name }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Téléphone</label>
                                    <input type="tel" name="phone" value="{{ $manager->phone }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Email</label>
                                    <input type="email" name="email" value="{{ $manager->email }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Statut</label>
                                    <select name="status" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                                        <option value="active" {{ $manager->status === 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="suspended" {{ $manager->status === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Remplacer le mot de passe <span class="text-gray-300 font-normal">(laisser vide pour conserver)</span></label>
                                <input type="text" name="password" placeholder="Nouveau mot de passe" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-5 py-2 bg-gray-800 hover:bg-black text-white font-semibold text-xs rounded-xl transition">
                                    Mettre à jour le compte manager
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs mb-4">
                            Aucun compte manager configuré pour cette agence.
                        </div>
                        <form method="POST" action="{{ route('admin.agencies.manager.store', $agency) }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                <input type="text" name="name" required placeholder="Nom complet" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                                <input type="tel" name="phone" required placeholder="N° Téléphone" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                                <input type="text" name="password" required placeholder="Mot de passe" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                                <input type="email" name="email" placeholder="Email (optionnel)" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white font-semibold rounded-xl text-xs hover:bg-indigo-700 transition">
                                Créer le compte manager
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Métadonnées système -->
                <div class="bg-gray-100 rounded-2xl p-4 flex flex-wrap items-center justify-between text-xs text-gray-500">
                    <span>ID Agence: <strong class="text-gray-800">#{{ $agency->id }}</strong></span>
                    <span>Créée le: <strong class="text-gray-800">{{ $agency->created_at?->format('d/m/Y à H:i') }}</strong></span>
                    <span>Dernière mise à jour: <strong class="text-gray-800">{{ $agency->updated_at?->format('d/m/Y à H:i') }}</strong></span>
                </div>
            </div>

            <!-- TAB 2: COMPTOIRS -->
            <div x-show="activeTab === 'counters'" class="space-y-6" x-cloak>
                <!-- Formulaire Création Comptoir -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4">Ajouter un nouveau comptoir</h3>
                    <form action="{{ route('admin.agencies.counters.store', $agency) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ville *</label>
                                <select name="city" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                                    <option value="">Sélectionner</option>
                                    @foreach ($agency->cities->where('is_active', true) as $city)
                                        <option value="{{ $city->city }}">{{ $city->city }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Quartier *</label>
                                <input type="text" name="district" required placeholder="Ex : Bastos" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Point de repère</label>
                                <input type="text" name="landmark" placeholder="Ex : Face station" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Téléphone</label>
                                <input type="text" name="phone" placeholder="6XXXXXXXX" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-semibold text-sm rounded-xl hover:bg-red-700 transition">
                                + Créer le comptoir
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Liste des comptoirs -->
                @if ($agency->counters->count())
                    <div class="space-y-4">
                        @foreach ($agency->counters as $counter)
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <h4 class="font-bold text-gray-900">{{ $counter->full_name }}</h4>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $counter->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                            {{ $counter->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </div>

                                    <form action="{{ route('admin.agencies.counters.toggle', $counter) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $counter->is_active ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                            {{ $counter->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                </div>

                                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Secrétaire -->
                                    <div class="space-y-3">
                                        <h5 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Secrétaire affecté</h5>
                                        @if ($counter->secretary)
                                            @php
                                                $secretaryAssignment = $counter->secretary;
                                                $secretary = $secretaryAssignment->secretary;
                                            @endphp
                                            <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                                                <div>
                                                    <p class="font-bold text-gray-900 text-sm">{{ $secretary->name }}</p>
                                                    <p class="text-xs text-gray-500 mt-0.5">📞 {{ $secretary->phone }}</p>
                                                    @if ($secretary->email)
                                                        <p class="text-xs text-gray-400">{{ $secretary->email }}</p>
                                                    @endif
                                                </div>
                                                @if ($secretaryAssignment->is_primary)
                                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100">Principal</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl text-amber-800 text-xs">
                                                Aucun secrétaire n'est actuellement rattaché à ce comptoir.
                                            </div>

                                            <form action="{{ route('admin.agencies.secretary.store', $counter) }}" method="POST" class="space-y-3 pt-2">
                                                @csrf
                                                <input type="text" name="name" required placeholder="Nom complet" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs">
                                                <div class="grid grid-cols-2 gap-2">
                                                    <input type="text" name="phone" required pattern="6[0-9]{8}" placeholder="6XXXXXXXX" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs">
                                                    <input type="email" name="email" placeholder="Email" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs">
                                                </div>
                                                <input type="password" name="password" required minlength="8" placeholder="Mot de passe" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs">
                                                <button type="submit" class="w-full py-2 bg-gray-900 text-white font-semibold rounded-xl text-xs hover:bg-black transition">
                                                    Créer & Affecter
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- Statistiques du comptoir -->
                                    <div class="space-y-3">
                                        <h5 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Activité du comptoir</h5>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 text-center">
                                                <span class="text-xs text-gray-400 font-medium">Colis déposés</span>
                                                <p class="text-xl font-extrabold text-gray-900 mt-1">{{ $counter->shipmentsAsOrigin->count() }}</p>
                                            </div>
                                            <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 text-center">
                                                <span class="text-xs text-gray-400 font-medium">Colis reçus</span>
                                                <p class="text-xl font-extrabold text-gray-900 mt-1">{{ $counter->shipmentsAsDestination->count() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400 text-sm">
                        Aucun comptoir n'a encore été enregistré.
                    </div>
                @endif
            </div>

            <!-- TAB 3: VILLES DESSERVIES -->
            <div x-show="activeTab === 'cities'" class="space-y-6" x-cloak>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4">Activer une nouvelle ville</h3>
                    <form action="{{ route('admin.agencies.cities.add', $agency) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <select name="city" required class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="">Sélectionner une ville dans la liste</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-semibold rounded-xl text-sm hover:bg-red-700 transition">
                            + Ajouter la ville
                        </button>
                    </form>
                </div>

                <!-- Badges des villes -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Villes actuellement configurées</h4>
                    @if ($agency->cities->count())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($agency->cities as $city)
                                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl border text-xs font-semibold {{ $city->is_active ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-gray-50 border-gray-200 text-gray-400' }}">
                                    <span>{{ $city->city }}</span>
                                    <form action="{{ route('admin.agencies.cities.toggle', $city) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-[10px] underline ml-1 hover:opacity-75">
                                            {{ $city->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400">Aucune ville n'est desservie par cette agence pour le moment.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection