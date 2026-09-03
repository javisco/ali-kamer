@extends('layouts.admin')

@section('title', 'Agences partenaires')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 space-y-6">

        {{-- En-tête --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#00843D]"></span>
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#00843D]">
                        Réseau Ali-Kamer
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                    Agences partenaires
                </h1>

                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Gérez le réseau d'agences et le suivi de leurs performances.
                </p>
            </div>

            <a href="{{ route('admin.agencies.create') }}"
               class="inline-flex items-center justify-center gap-2 bg-[#00843D] hover:bg-[#006B32] text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-sm">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4"/>
                </svg>

                Nouvelle agence
            </a>
        </div>

        {{-- Barre tricolore --}}
        <div class="h-1 rounded-full bg-gradient-to-r from-[#00843D] via-[#FCD116] to-[#CE1126]"></div>

        {{-- Message succès --}}
        @if (session('success'))
            <div class="bg-[#00843D]/5 border border-[#00843D]/20 text-[#006B32] px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-[#00843D]/10 flex items-center justify-center">
                        ✓
                    </span>
                    <span>{{ session('success') }}</span>
                </div>

                <button onclick="this.parentElement.remove()"
                        class="text-[#00843D] hover:text-[#006B32]">
                    ✕
                </button>
            </div>
        @endif

        {{-- Banner gains --}}
        <a href="{{ route('admin.agencies.earnings', ['agencies' => $agencies]) }}"
           class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between hover:shadow-md hover:border-[#00843D]/20 transition-all duration-200">

            <div class="flex items-center gap-4">

                <div class="w-11 h-11 rounded-2xl bg-[#00843D]/10 text-[#00843D] flex items-center justify-center group-hover:bg-[#00843D] group-hover:text-white transition-colors">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v1m0 10v1m-8-5a8 8 0 1016 0 8 8 0 00-16 0z"/>
                    </svg>
                </div>

                <div>
                    <h4 class="font-black text-gray-900 group-hover:text-[#00843D] transition-colors">
                        Consulter les gains des agences
                    </h4>

                    <p class="text-xs text-gray-400 mt-0.5">
                        Analyse financière globale et commissions réseau
                    </p>
                </div>

            </div>

            <div class="hidden sm:flex items-center text-xs font-bold text-[#00843D] gap-1 group-hover:translate-x-1 transition-transform">
                <span>Voir le rapport</span>
                <span>→</span>
            </div>
        </a>

        {{-- Liste agences --}}
        <div class="space-y-4">

            @forelse($agencies as $agency)

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6 hover:shadow-md hover:border-[#00843D]/10 transition">

                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">

                        {{-- Détails --}}
                        <div class="space-y-3 min-w-0">

                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="text-lg font-black text-gray-900">
                                    {{ $agency->name }}
                                </h2>

                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold
                                    {{ $agency->is_active
                                        ? 'bg-[#00843D]/10 text-[#00843D]'
                                        : 'bg-red-50 text-[#CE1126]' }}">

                                    <span class="w-1.5 h-1.5 rounded-full
                                        {{ $agency->is_active ? 'bg-[#00843D]' : 'bg-[#CE1126]' }}">
                                    </span>

                                    {{ $agency->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-gray-500">

                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-[#00843D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                    </svg>

                                    {{ $agency->counters_count }} comptoir(s)
                                </span>

                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-[#00843D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>

                                    {{ $agency->cities_count }} ville(s)
                                </span>

                                @if ($agency->contact_phone)
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-[#00843D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>

                                        {{ $agency->contact_phone }}
                                    </span>
                                @endif
                            </div>

                            {{-- Villes --}}
                            @if($agency->cities->count())
                                <div class="flex flex-wrap gap-1.5 pt-1">

                                    @foreach ($agency->cities as $city)

                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold
                                            {{ $city->is_active
                                                ? 'bg-[#00843D]/5 text-[#006B32] border border-[#00843D]/10'
                                                : 'bg-gray-50 text-gray-400 line-through border border-gray-100' }}">
                                            {{ $city->city }}
                                        </span>

                                    @endforeach

                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 self-start lg:self-center flex-shrink-0">

                            <form method="POST" action="{{ route('admin.agencies.toggle', $agency) }}">
                                @csrf

                                <button type="submit"
                                        class="px-3 py-2 rounded-xl text-[10px] font-bold transition border
                                        {{ $agency->is_active
                                            ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100'
                                            : 'border-[#00843D]/20 bg-[#00843D]/5 text-[#00843D] hover:bg-[#00843D]/10' }}">
                                    {{ $agency->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>

                            <a href="{{ route('admin.agencies.show', $agency) }}"
                               class="px-4 py-2 bg-gray-900 hover:bg-[#00843D] text-white rounded-xl text-[10px] font-bold transition flex items-center gap-1.5">

                                <span>Gérer</span>

                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>

            @empty

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">

                    <div class="w-14 h-14 bg-[#00843D]/5 text-[#00843D] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                    </div>

                    <h3 class="text-base font-black text-gray-900 mb-1">
                        Aucune agence partenaire
                    </h3>

                    <p class="text-gray-400 text-xs mb-5">
                        Commencez par enregistrer une première agence dans le système.
                    </p>

                    <a href="{{ route('admin.agencies.create') }}"
                       class="inline-flex items-center gap-2 text-sm font-bold text-[#00843D] hover:text-[#006B32]">
                        <span>Créer la première agence</span>
                        <span>→</span>
                    </a>
                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $agencies->links() }}
        </div>

    </div>
</div>
@endsection