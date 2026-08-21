@extends('base')
@section('title', 'Dashboard — ' . $agency->name)
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">{{ $agency->name }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Tableau de bord agence</p>
                </div>
                <a href="{{ route('agency.wallet') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                  px-5 py-2.5 rounded-xl text-sm transition">
                    💰 Mon wallet
                </a>
            </div>

            @if (session('success'))
                <div
                    class="bg-emerald-50 border border-emerald-200 text-emerald-800
                    px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="bg-red-50 border border-red-200 text-red-700
                    px-4 py-3 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    <p class="text-xs text-gray-500 mb-1">Gains totaux</p>
                    <p class="text-xl font-extrabold text-indigo-600">
                        {{ number_format($stats['total_earned'], 0, ',', ' ') }} FCFA
                    </p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    <p class="text-xs text-gray-500 mb-1">Solde disponible</p>
                    <p class="text-xl font-extrabold text-emerald-600">
                        {{ number_format($stats['wallet_available'], 0, ',', ' ') }} FCFA
                    </p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    <p class="text-xs text-gray-500 mb-1">Colis traités</p>
                    <p class="text-xl font-extrabold text-gray-900">{{ $stats['colis_count'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    <p class="text-xs text-gray-500 mb-1">Comptoirs</p>
                    <p class="text-xl font-extrabold text-gray-900">
                        {{ $agency->counters->count() }}
                    </p>
                </div>
            </div>

            {{-- Créer un comptoir --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                <h2 class="font-bold text-gray-800 mb-4">Ajouter un comptoir</h2>

                @if ($agency->cities->where('is_active', true)->isEmpty())
                    <p class="text-sm text-orange-500">
                        Aucune ville desservie configurée.
                        Contactez l'administrateur pour ajouter des villes.
                    </p>
                @else
                    <form method="POST" action="{{ route('agency.counters.store') }}" class="grid grid-cols-2 gap-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Ville <span class="text-red-500">*</span>
                            </label>
                            <select name="city" required
                                class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                   focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Choisir --</option>
                                @foreach ($agency->cities->where('is_active', true) as $city)
                                    <option value="{{ $city->city }}">{{ $city->city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Quartier / Site <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="district" required placeholder="Ex: Carrière, Terminus..."
                                class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                  focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Repère</label>
                            <input type="text" name="landmark" placeholder="Ex: Face au marché..."
                                class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                  focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Téléphone</label>
                            <input type="tel" name="phone"
                                class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                  focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="col-span-2">
                            <button
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                                   py-2.5 rounded-xl text-sm transition">
                                Créer le comptoir
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            {{-- Liste des comptoirs + secrétaires --}}
            @forelse($agency->counters as $counter)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">

                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-gray-900">
                                {{ $counter->city }}
                                @if ($counter->district)
                                    — {{ $counter->district }}
                                @endif
                            </h3>
                            @if ($counter->landmark)
                                <p class="text-xs text-gray-400">{{ $counter->landmark }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-semibold
                                 {{ $counter->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                {{ $counter->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            <form method="POST" action="{{ route('agency.counters.toggle', $counter) }}">
                                @csrf
                                <button class="text-xs text-gray-500 hover:underline">
                                    {{ $counter->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Secrétaire --}}
                    @if ($counter->secretary)
                        @php $sec = $counter->secretary->secretary; @endphp
                        <div class="bg-gray-50 rounded-xl p-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $sec->name }}</p>
                                <p class="text-xs text-gray-400">{{ $sec->phone }}</p>
                                <span class="text-xs {{ $sec->isActive() ? 'text-emerald-500' : 'text-orange-500' }}">
                                    {{ $sec->isActive() ? 'Actif' : 'Suspendu' }}
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('agency.secretary.toggle', $sec) }}">
                                    @csrf
                                    <button class="text-xs text-gray-500 hover:underline">
                                        {{ $sec->isActive() ? 'Suspendre' : 'Réactiver' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('agency.secretary.delete', $sec) }}"
                                    onsubmit="return confirm('Supprimer ce secrétaire ?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-500 hover:underline">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Créer un secrétaire --}}
                        <div class="border border-dashed border-gray-300 rounded-xl p-4">
                            <p class="text-xs text-orange-500 mb-3">
                                ⚠ Aucun secrétaire — comptoir non opérationnel
                            </p>
                            <form method="POST" action="{{ route('agency.secretary.store', $counter) }}"
                                class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <input type="text" name="name" required placeholder="Nom complet"
                                            class="w-full border border-gray-300 rounded-lg
                                              px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <input type="tel" name="phone" required placeholder="6XXXXXXXX"
                                            class="w-full border border-gray-300 rounded-lg
                                              px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <input type="text" name="password" required
                                            placeholder="Mot de passe (min 8 car.)"
                                            class="w-full border border-gray-300 rounded-lg
                                              px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <input type="email" name="email" placeholder="Email (optionnel)"
                                            class="w-full border border-gray-300 rounded-lg
                                              px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <button
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white
                                       font-bold py-2 rounded-lg text-xs transition">
                                    Créer et affecter
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
                    <p class="text-gray-400 text-sm">
                        Aucun comptoir. Créez votre premier comptoir ci-dessus.
                    </p>
                </div>
            @endforelse

        </div>
    </div>
@endsection
