@extends('base')
@section('title', 'Agences partenaires')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 space-y-6">

            <!-- En-tête de page -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Agences partenaires</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Gérez le réseau d'agences et le suivi de leurs performances.</p>
                </div>
                <a href="{{ route('admin.agencies.create') }}"
                    class="inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle agence
                </a>
            </div>

            <!-- Message Flash Success -->
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">✕</button>
                </div>
            @endif

            <!-- Banner Stats / Gains -->
            <a href="{{ route('admin.agencies.earnings', ['agencies' => $agencies]) }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between hover:shadow-md hover:border-indigo-100 transition-all duration-200 block">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">Consulter les gains des agences</h4>
                        <p class="text-xs text-gray-400">Analyse financière globale et commissions réseau</p>
                    </div>
                </div>
                <div class="flex items-center text-sm font-semibold text-indigo-600 gap-1 group-hover:translate-x-1 transition-transform">
                    <span>Voir le rapport</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Liste des Agences -->
            <div class="space-y-4">
                @forelse($agencies as $agency)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            
                            <!-- Détails Agence -->
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <h2 class="text-lg font-bold text-gray-900">{{ $agency->name }}</h2>
                                    <span class="px-3 py-0.5 rounded-full text-xs font-semibold {{ $agency->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                        {{ $agency->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        {{ $agency->counters_count }} comptoir(s)
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $agency->cities_count }} ville(s)
                                    </span>
                                    @if ($agency->contact_phone)
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ $agency->contact_phone }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Villes desservies --}}
                                @if($agency->cities->count())
                                    <div class="flex flex-wrap gap-1.5 pt-1">
                                        @foreach ($agency->cities as $city)
                                            <span class="px-2.5 py-0.5 rounded-lg text-xs font-medium {{ $city->is_active ? 'bg-gray-100 text-gray-700' : 'bg-gray-50 text-gray-400 line-through' }}">
                                                {{ $city->city }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-3 self-end sm:self-center pt-2 sm:pt-0">
                                <form method="POST" action="{{ route('admin.agencies.toggle', $agency) }}">
                                    @csrf
                                    <button type="submit" 
                                        class="px-3 py-1.5 rounded-xl text-xs font-semibold transition border {{ $agency->is_active ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                        {{ $agency->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>

                                <a href="{{ route('admin.agencies.show', $agency) }}"
                                    class="px-4 py-1.5 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                                    <span>Gérer</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                        <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 mb-1">Aucune agence partenaire</h3>
                        <p class="text-gray-400 text-xs mb-4">Commencez par enregistrer une première agence dans le système.</p>
                        <a href="{{ route('admin.agencies.create') }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-red-600 hover:text-red-700">
                            <span>Créer la première agence</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $agencies->links() }}
            </div>
        </div>
    </div>
@endsection