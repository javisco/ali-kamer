@extends('base')

@section('title', 'Valider les arrivées')

@section('content')

<div class="bg-[#F7F7F2] min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="flex items-center gap-3 mb-6">

            <a href="{{ route('secretary.dashboard') }}"
               class="w-9 h-9 flex items-center justify-center
                      rounded-xl bg-white border border-gray-100
                      text-gray-400 hover:text-[#016837]
                      hover:border-green-100 transition">
                ←
            </a>

            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#F9A01B]">
                    Gestion des colis
                </p>

                <h1 class="text-2xl font-extrabold text-gray-900">
                    Colis à valider à l'arrivée
                </h1>
            </div>
        </div>

        {{-- Succès --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200
                        text-[#016837] px-4 py-3 rounded-xl mb-6 text-sm
                        flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#016837]"></span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Recherche --}}
        <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm p-5 mb-6">

            <div class="mb-3">
                <p class="text-sm font-bold text-gray-900">
                    Rechercher un colis
                </p>

                <p class="text-xs text-gray-400 mt-0.5">
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
                    class="flex-1 border border-gray-200 bg-gray-50
                           rounded-xl px-4 py-2.5 text-sm
                           focus:outline-none focus:bg-white
                           focus:border-[#016837]
                           focus:ring-2 focus:ring-green-500/10
                           uppercase font-mono transition">

                <button
                    class="bg-[#016837] hover:bg-[#0a542d]
                           text-white font-bold px-5 py-2.5
                           rounded-xl text-sm transition shadow-sm">
                    Rechercher
                </button>

                @if (request('ref'))
                    <a href="{{ route('secretary.arrivals.page') }}"
                       class="px-4 py-2.5 border border-gray-200
                              rounded-xl text-sm text-gray-500
                              hover:bg-gray-50 transition text-center">
                        Effacer
                    </a>
                @endif
            </form>

            @error('ref')
                <p class="text-[#E30613] text-sm mt-2">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Résultat recherche --}}
        @if ($searched)

            <div class="mb-4">
                <p class="text-[11px] font-bold uppercase tracking-wider
                          text-gray-400 mb-2">
                    Résultat de la recherche
                </p>

                @include('secretary.arrivals._card', ['order' => $searched])
            </div>

        @else

            {{-- Liste complète --}}
            @if ($orders->isEmpty())

                <div class="bg-white rounded-2xl border border-gray-100
                            shadow-sm p-10 text-center">

                    <div class="w-12 h-12 mx-auto mb-3 rounded-2xl
                                bg-green-50 flex items-center justify-center">
                        <span class="text-xl text-[#016837]">✓</span>
                    </div>

                    <p class="font-semibold text-gray-700">
                        Aucun colis en attente
                    </p>

                    <p class="text-gray-400 text-sm mt-1">
                        Tous les colis ont été traités.
                    </p>
                </div>

            @else

                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-gray-500">
                        <span class="font-bold text-gray-900">
                            {{ $orders->count() }}
                        </span>
                        colis en attente
                    </p>

                    <span class="w-2 h-2 rounded-full bg-[#F9A01B]"></span>
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