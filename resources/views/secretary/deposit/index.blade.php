@extends('base')
@section('title', 'Enregistrer un dépôt')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-lg mx-auto px-4">

            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('secretary.dashboard') }}" class="text-gray-400 hover:text-gray-600">←</a>
                <h1 class="text-2xl font-extrabold text-gray-900">Enregistrer un dépôt</h1>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Saisie du deposit_code --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-gray-800 mb-2">Code de dépôt vendeur</h2>
                <p class="text-sm text-gray-500 mb-5">
                    Demandez au vendeur son code à 8 caractères et saisissez-le ci-dessous.
                </p>

                <form action="{{ route('secretary.deposit.search') }}" method="POST">
                    @csrf

                    <div class="flex gap-3">
                        <input type="text" name="deposit_code" maxlength="8" required placeholder="Ex: AB3D7F2K"
                            class="flex-1 border border-gray-300 rounded-xl px-4 py-3
                              text-center text-xl font-mono tracking-[0.3em] uppercase
                              focus:ring-2 focus:ring-indigo-500
                              @error('deposit_code') border-red-400 @enderror"
                            autofocus>
                        <button
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                               px-6 py-3 rounded-xl transition">
                            Rechercher
                        </button>
                    </div>
                    @error('deposit_code')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </form>
            </div>

        </div>
    </div>
@endsection
