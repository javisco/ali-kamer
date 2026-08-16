@extends('base')
@section('title', 'Valider les arrivées')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4">

            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('secretary.dashboard') }}" class="text-gray-400 hover:text-gray-600">←</a>
                <h1 class="text-2xl font-extrabold text-gray-900">Colis à valider à l'arrivée</h1>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Recherche par référence --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                <form method="GET" action="{{ route('secretary.arrivals.page') }}" class="flex gap-3">
                    <input type="text" name="ref" value="{{ request('ref') }}"
                        placeholder="Référence commande — Ex: ALK-2026-00001"
                        class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500 uppercase font-mono">
                    <button
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                           px-5 py-2.5 rounded-xl text-sm transition">
                        Rechercher
                    </button>
                    @if (request('ref'))
                        <a href="{{ route('secretary.arrivals.page') }}"
                            class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-500 hover:bg-gray-50">
                            Effacer
                        </a>
                    @endif
                </form>
                @error('ref')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Résultat recherche --}}
            @if ($searched)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-2">Résultat de la recherche</p>
                    @include('secretary.arrivals._card', ['order' => $searched])
                </div>
            @else
                {{-- Liste complète --}}
                @if ($orders->isEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
                        <p class="text-gray-400 text-sm">Aucun colis en attente de validation.</p>
                    </div>
                @else
                    <p class="text-xs text-gray-500 mb-3">{{ $orders->count() }} colis en attente</p>
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
