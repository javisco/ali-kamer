@extends('base')
@section('title', 'Dashboard — ' . $agency->name)

@section('content')
    <div class="bg-slate-50/60 min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- En-tête du Dashboard --}}
            <div
                class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center gap-4">
                    <div
                        class="h-14 w-14 rounded-2xl bg-gradient-to-br from-primary-600 to-primary-700 text-white flex items-center justify-center font-black text-2xl shadow-md shadow-success-800/10 shrink-0">
                        {{ strtoupper(substr($agency->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $agency->name }}</h1>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-success-50 text-primary-600 border border-success-200">
                                Partenaire Agréé
                            </span>
                        </div>
                        <p class="text-xs font-medium text-slate-500 mt-0.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Gestion administrative du réseau d'agences
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('agency.wallet') }}"
                        class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-sm shadow-success-800/15">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Mon Wallet Agence
                    </a>
                </div>
            </div>

            {{-- Notifications Flash --}}
            @if (session('success'))
                <div
                    class="bg-success-50 border border-success-200 text-success-800 px-4 py-3.5 rounded-xl mb-6 text-sm flex items-center gap-3 shadow-xs">
                    <span
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-white text-xs font-bold">✓</span>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-danger-50 border border-danger-200 text-[#CE1126] px-4 py-3.5 rounded-xl mb-6 text-sm shadow-xs">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Veuillez corriger les erreurs ci-dessous :
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Statistiques Globales --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Gains Totaux</span>
                        <span class="p-2 rounded-xl bg-success-50 text-primary-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-2xl font-black text-slate-900">
                            {{ number_format($stats['total_earned'], 0, ',', ' ') }} <span
                                class="text-xs font-bold text-slate-500">FCFA</span>
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Solde Disponible</span>
                        <span class="p-2 rounded-xl bg-warning-50 text-warning-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-2xl font-black text-primary-600">
                            {{ number_format($stats['wallet_available'], 0, ',', ' ') }} <span
                                class="text-xs font-bold text-slate-500">FCFA</span>
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Colis Traités</span>
                        <span class="p-2 rounded-xl bg-slate-100 text-slate-700">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-2xl font-black text-slate-900">{{ $stats['colis_count'] }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Comptoirs Actifs</span>
                        <span class="p-2 rounded-xl bg-slate-100 text-slate-700">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-3">
                        <p class="text-2xl font-black text-slate-900">{{ $agency->counters->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- Formulaire Créer un comptoir --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <span
                        class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-600/10 text-primary-600 text-xs font-bold">+</span>
                    <h2 class="font-extrabold text-slate-900 text-base">Ajouter un nouveau comptoir</h2>
                </div>

                @if ($agency->cities->where('is_active', true)->isEmpty())
                    <div
                        class="bg-warning-50 border border-warning-200 text-warning-800 rounded-xl p-4 text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-warning-600 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Aucune ville desservie configurée pour votre agence. Contactez l'administrateur pour en
                            assigner.</span>
                    </div>
                @else
                    <form method="POST" action="{{ route('agency.counters.store') }}"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Ville <span class="text-[#CE1126]">*</span>
                            </label>
                            <select name="city" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition">
                                <option value="">-- Sélectionner --</option>
                                @foreach ($agency->cities->where('is_active', true) as $city)
                                    <option value="{{ $city->city }}">{{ $city->city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Quartier / Site <span class="text-[#CE1126]">*</span>
                            </label>
                            <input type="text" name="district" required placeholder="Ex: Akwa, Mokolo..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Point de repère</label>
                            <input type="text" name="landmark" placeholder="Ex: Face au carrefour..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Téléphone direct</label>
                            <input type="tel" name="phone" placeholder="Ex: 6XXXXXXXX"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition">
                        </div>

                        <div class="md:col-span-2 lg:col-span-4 mt-2">
                            <button
                                class="w-full md:w-auto bg-primary-600 hover:bg-primary-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition shadow-sm shadow-success-800/15">
                                + Enregistrer le comptoir
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            {{-- Liste des Comptoirs --}}
            <div class="space-y-4">
                <h2 class="font-extrabold text-slate-900 text-lg mb-2">Comptoirs & Secrétaires affectés</h2>

                @forelse($agency->counters as $counter)
                    <div
                        class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden transition hover:border-slate-300">
                        <div class="p-5">
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-sm">
                                        🏢
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-slate-900 text-base">
                                            {{ $counter->city }}
                                            @if ($counter->district)
                                                <span class="text-slate-500 font-semibold">—
                                                    {{ $counter->district }}</span>
                                            @endif
                                        </h3>
                                        @if ($counter->landmark)
                                            <p class="text-xs font-medium text-slate-400 flex items-center gap-1 mt-0.5">
                                                📍 {{ $counter->landmark }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 self-end sm:self-center">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-extrabold tracking-wide border
                                    {{ $counter->is_active ? 'bg-success-50 text-primary-600 border-success-200' : 'bg-danger-50 text-[#CE1126] border-danger-200' }}">
                                        {{ $counter->is_active ? '● Actif' : '○ Inactif' }}
                                    </span>

                                    <form method="POST" action="{{ route('agency.counters.toggle', $counter) }}">
                                        @csrf
                                        <button
                                            class="text-xs font-bold text-slate-500 hover:text-slate-800 transition underline decoration-slate-300">
                                            {{ $counter->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Section Secrétaire --}}
                            <div class="mt-4">
                                @if ($counter->secretary?->secretary)
                                    @php $sec = $counter->secretary->secretary; @endphp
                                    <div class="space-y-3" x-data="{ editing: false }">
                                        <div
                                            class="bg-slate-50 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-slate-100">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="h-9 w-9 rounded-full bg-success-100 text-primary-600 font-black text-xs flex items-center justify-center">
                                                    {{ strtoupper(substr($sec->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-xs font-extrabold text-slate-900">{{ $sec->name }}</p>
                                                    <p class="text-xs text-slate-500 font-medium">📞 {{ $sec->phone }}</p>
                                                    @if ($sec->email)
                                                        <p class="text-[11px] text-slate-400 font-medium">{{ $sec->email }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div
                                                class="flex flex-wrap items-center gap-3 border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-200">
                                                <span
                                                    class="text-[11px] font-bold px-2.5 py-0.5 rounded-md {{ $sec->isActive() ? 'bg-success-100 text-primary-600' : 'bg-warning-100 text-warning-700' }}">
                                                    {{ $sec->isActive() ? 'Opérationnel' : 'Suspendu' }}
                                                </span>

                                                <button type="button" @click="editing = !editing"
                                                    class="text-xs font-bold text-primary-600 hover:underline">
                                                    <span x-show="!editing">Modifier</span>
                                                    <span x-show="editing" x-cloak>Fermer</span>
                                                </button>

                                                <form method="POST" action="{{ route('agency.secretary.toggle', $sec) }}">
                                                    @csrf
                                                    <button
                                                        class="text-xs font-bold text-slate-600 hover:text-slate-900 transition">
                                                        {{ $sec->isActive() ? 'Suspendre' : 'Réactiver' }}
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('agency.secretary.delete', $sec) }}"
                                                    onsubmit="return confirm('Supprimer définitivement ce secrétaire ?')">
                                                    @csrf @method('DELETE')
                                                    <button class="text-xs font-bold text-[#CE1126] hover:underline">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div x-show="editing" x-cloak
                                            class="bg-white border border-slate-200 rounded-xl p-4">
                                            <form method="POST" action="{{ route('agency.secretary.update', $sec) }}"
                                                class="space-y-3">
                                                @csrf
                                                @method('PUT')

                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="text-[10px] font-bold text-slate-500 uppercase">Nom
                                                            complet</label>
                                                        <input type="text" name="name" required
                                                            value="{{ $sec->name }}"
                                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-[10px] font-bold text-slate-500 uppercase">Téléphone</label>
                                                        <input type="tel" name="phone" required
                                                            value="{{ $sec->phone }}" pattern="6[0-9]{8}"
                                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                                                    </div>
                                                    <div>
                                                        <label class="text-[10px] font-bold text-slate-500 uppercase">Email
                                                            (optionnel)</label>
                                                        <input type="email" name="email" value="{{ $sec->email }}"
                                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                                                    </div>
                                                    <div>
                                                        <label class="text-[10px] font-bold text-slate-500 uppercase">Nouveau
                                                            mot de passe (optionnel)</label>
                                                        <input type="password" name="password"
                                                            placeholder="Laisser vide pour ne pas changer"
                                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                                                    </div>
                                                </div>

                                                <div class="flex justify-end gap-2 pt-1">
                                                    <button type="button" @click="editing = false"
                                                        class="text-xs font-bold text-slate-500 hover:text-slate-700 px-3 py-1.5">
                                                        Annuler
                                                    </button>
                                                    <button type="submit"
                                                        class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-1.5 px-4 rounded-lg text-xs transition">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    {{-- Formulaire Secrétaire Absent --}}
                                    <div
                                        class="bg-warning-50/50 border border-dashed border-warning-200/80 rounded-xl p-4">
                                        <div class="flex items-center gap-2 mb-3 text-warning-800">
                                            <svg class="w-4 h-4 text-warning-600 shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <p class="text-xs font-bold">Comptoir non opérationnel — Aucun secrétaire
                                                attribué</p>
                                        </div>

                                        <form method="POST" action="{{ route('agency.secretary.store', $counter) }}"
                                            class="space-y-3">
                                            @csrf
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                                <div>
                                                    <input type="text" name="name" required
                                                        placeholder="Nom complet"
                                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                                                </div>
                                                <div>
                                                    <input type="tel" name="phone" required
                                                        placeholder="N° Téléphone (6XXXXXXXX)" pattern="6[0-9]{8}"
                                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                                                </div>
                                                <div>
                                                    <input type="password" name="password" required minlength="8"
                                                        placeholder="Mot de passe (8 caractères min.)"
                                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                                                </div>
                                                <div>
                                                    <input type="email" name="email"
                                                        placeholder="Email (Optionnel)"
                                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                                                </div>
                                            </div>
                                            <button
                                                class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition shadow-xs">
                                                + Créer et affecter le secrétaire
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-12 text-center">
                        <div
                            class="h-12 w-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 font-bold text-lg">
                            🏬
                        </div>
                        <p class="text-slate-800 font-bold text-base">Aucun comptoir pour le moment</p>
                        <p class="text-slate-400 text-xs mt-1">Utilisez le formulaire ci-dessus pour configurer votre
                            premier comptoir.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection
