@extends('base')

@section('title', 'Enregistrer un dépôt')

@section('content')

<div class="bg-slate-50 min-h-[calc(100vh-4rem)] py-10">

    <div class="max-w-lg mx-auto px-4 sm:px-6">

        {{-- En-tête --}}
        <div class="flex items-center gap-3 mb-8">

            <a href="{{ route('secretary.dashboard') }}"
               class="w-9 h-9 flex items-center justify-center
                      bg-white border border-slate-100
                      text-slate-400 hover:text-primary-600
                      hover:border-success-100 rounded-xl transition-all"
               title="Retour au tableau de bord">
                <svg class="w-5 h-5" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>

            <div>
                <p class="text-[10px] font-bold uppercase
                          tracking-wider text-accent-500">
                    Logistique
                </p>

                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                    Enregistrer un dépôt
                </h1>
            </div>

        </div>

        {{-- Alert succès --}}
        @if (session('success'))

            <div class="flex items-center gap-3
                        bg-success-50 border border-success-200
                        text-primary-600 px-4 py-3.5 rounded-2xl
                        mb-6 text-sm font-medium">

                <div class="w-7 h-7 rounded-lg bg-success-100
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-primary-600"
                         fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0
                                 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <span>{{ session('success') }}</span>
            </div>

        @endif

        {{-- Alert erreur --}}
        @if (session('error'))

            <div class="flex items-center gap-3
                        bg-danger-50 border border-danger-200
                        text-danger px-4 py-3.5 rounded-2xl
                        mb-6 text-sm font-medium">

                <div class="w-7 h-7 rounded-lg bg-danger-100
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-danger"
                         fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0
                                 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <span>{{ session('error') }}</span>
            </div>

        @endif

        {{-- Formulaire --}}
        <div class="bg-white rounded-3xl border border-slate-100
                    shadow-xl shadow-slate-200/40 p-6 sm:p-8">

            <div class="mb-6">

                <div class="w-12 h-12 bg-success-50 rounded-2xl
                            flex items-center justify-center mb-3">

                    <svg class="w-6 h-6 text-primary-600"
                         fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>

                </div>

                <h2 class="text-lg font-bold text-slate-900">
                    Code de dépôt vendeur
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Saisissez le code à 8 caractères fourni par le vendeur.
                </p>

            </div>

            <form action="{{ route('secretary.deposit.search') }}"
                  method="POST">
                @csrf

                <div class="flex flex-col sm:flex-row gap-3">

                    <div class="flex-1">

                        <input
                            type="text"
                            name="deposit_code"
                            maxlength="8"
                            required
                            placeholder="AB3D7F2K"
                            value="{{ old('deposit_code') }}"
                            class="w-full bg-slate-50 border border-slate-200
                                   rounded-2xl px-4 py-3.5
                                   text-center sm:text-left text-xl
                                   font-mono tracking-[0.25em]
                                   uppercase text-slate-900
                                   placeholder:text-slate-300
                                   focus:outline-none focus:bg-white
                                   focus:border-primary-600
                                   focus:ring-4 focus:ring-success/10
                                   transition-all
                                   @error('deposit_code')
                                       border-danger
                                       focus:ring-danger/10
                                   @enderror"
                            autofocus
                            autocomplete="off">

                    </div>

                    <button
                        type="submit"
                        class="bg-primary-600 hover:bg-primary-700
                               active:bg-primary-800
                               text-white font-semibold
                               px-6 py-3.5 rounded-2xl
                               transition-all shadow-md
                               flex items-center justify-center
                               gap-2 shrink-0">

                        <svg class="w-5 h-5"
                             fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0
                                     11-14 0 7 7 0 0114 0z"/>
                        </svg>

                        <span>Rechercher</span>

                    </button>

                </div>

                @error('deposit_code')
                    <p class="text-danger text-xs font-medium mt-2">
                        {{ $message }}
                    </p>
                @enderror

            </form>

            {{-- Petite indication visuelle --}}
            <div class="mt-6 pt-5 border-t border-slate-100
                        flex items-center gap-2">

                <span class="w-2 h-2 rounded-full bg-accent-500"></span>

                <p class="text-[11px] text-slate-400">
                    Le code permet d'identifier rapidement le colis du vendeur.
                </p>

            </div>

        </div>

    </div>
</div>

@endsection