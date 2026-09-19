@extends('base')

@section('title', 'Valider les arrivées')

@section('content')

<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="flex items-center gap-3 mb-6">

            <a href="{{ route('secretary.dashboard') }}"
               class="w-9 h-9 flex items-center justify-center
                      rounded-xl bg-white border border-slate-100
                      text-slate-400 hover:text-primary-600
                      hover:border-success-100 transition">
                ←
            </a>

            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-accent-500">
                    Gestion des colis
                </p>

                <h1 class="text-2xl font-extrabold text-slate-900">
                    Colis à valider à l'arrivée
                </h1>
            </div>
        </div>

        {{-- Succès --}}
        @if (session('success'))
            <div class="bg-success-50 border border-success-200
                        text-primary-600 px-4 py-3 rounded-xl mb-6 text-sm
                        flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-primary-600"></span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Recherche --}}
        <div class="bg-white rounded-2xl border border-slate-100
                    shadow-sm p-5 mb-6">

            <div class="mb-3">
                <p class="text-sm font-bold text-slate-900">
                    Rechercher un colis
                </p>

                <p class="text-xs text-slate-400 mt-0.5">
                    Utilisez la référence de la commande.
                </p>
            </div>

            <form method="GET"
                  action="{{ route('secretary.arrivals.page') }}"
                  class="flex flex-col sm:flex-row gap-3">

                <input
                    type="text"
                    name="ref"
                    value="{{ request('ref') }}"
                    placeholder="ALK-2026-00001"
                    class="flex-1 border border-slate-200 bg-slate-50
                           rounded-xl px-4 py-2.5 text-sm
                           focus:outline-none focus:bg-white
                           focus:border-primary-600
                           focus:ring-2 focus:ring-success/10
                           uppercase font-mono transition">

                <button
                    class="bg-primary-600 hover:bg-primary-700
                           text-white font-bold px-5 py-2.5
                           rounded-xl text-sm transition shadow-sm">
                    Rechercher
                </button>

                @if (request('ref'))
                    <a href="{{ route('secretary.arrivals.page') }}"
                       class="px-4 py-2.5 border border-slate-200
                              rounded-xl text-sm text-slate-500
                              hover:bg-slate-50 transition text-center">
                        Effacer
                    </a>
                @endif
            </form>

            @error('ref')
                <p class="text-danger text-sm mt-2">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Résultat recherche --}}
        @if ($searched)

            <div class="mb-4">
                <p class="text-[11px] font-bold uppercase tracking-wider
                          text-slate-400 mb-2">
                    Résultat de la recherche
                </p>

                @include('secretary.arrivals._card', ['order' => $searched])
            </div>

        @else

            {{-- Liste complète --}}
            @if ($orders->isEmpty())

                <div class="bg-white rounded-2xl border border-slate-100
                            shadow-sm p-10 text-center">

                    <div class="w-12 h-12 mx-auto mb-3 rounded-2xl
                                bg-success-50 flex items-center justify-center">
                        <span class="text-xl text-primary-600">✓</span>
                    </div>

                    <p class="font-semibold text-slate-700">
                        Aucun colis en attente
                    </p>

                    <p class="text-slate-400 text-sm mt-1">
                        Tous les colis ont été traités.
                    </p>
                </div>

            @else

                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-slate-500">
                        <span class="font-bold text-slate-900">
                            {{ $orders->count() }}
                        </span>
                        colis en attente
                    </p>

                    <span class="w-2 h-2 rounded-full bg-accent-500"></span>
                </div>

                <div class="space-y-4">
                    @foreach ($orders as $order)
                        @include('secretary.arrivals._card', compact('order'))
                    @endforeach
                </div>

            @endif
        @endif

    </div>
</div>

@endsection