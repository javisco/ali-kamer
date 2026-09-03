@extends('base')

@section('title', 'Dashboard Secrétaire')

@section('content')

<div class="bg-[#F7F7F2] min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">

        {{-- =========================================================
            EN-TÊTE
        ========================================================== --}}
        <div class="mb-7">

            <div class="flex items-center gap-3 mb-2">

                <div class="w-10 h-10 rounded-xl bg-[#016837]
                            flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 6v6l4 2m6-2a10 10 0 11-20 0
                                 10 10 0 0120 0z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-[10px] font-bold uppercase
                              tracking-[0.12em] text-[#F9A01B]">
                        Espace professionnel
                    </p>

                    <h1 class="text-2xl font-extrabold text-gray-900">
                        Interface Secrétaire
                    </h1>
                </div>

            </div>

            {{-- Comptoir --}}
            @if ($counter)

                <div class="flex items-center gap-2 mt-3">

                    <span class="w-7 h-7 rounded-lg bg-green-50
                                 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#016837]"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M17.657 16.657L13.414 21
                                     a2 2 0 01-2.828 0l-4.243-4.243
                                     a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0
                                     016 0z"/>
                        </svg>
                    </span>

                    <p class="text-sm text-gray-500">
                        {{ $counter->full_name }}
                    </p>

                </div>

            @else

                <div class="flex items-start gap-3
                            bg-red-50 border border-red-200
                            text-[#E30613] px-4 py-3 rounded-xl
                            mt-4 text-sm">

                    <span class="text-base">⚠</span>

                    <div>
                        <p class="font-bold">
                            Aucun comptoir assigné
                        </p>

                        <p class="text-xs mt-0.5 text-red-600">
                            Contactez l'administrateur.
                        </p>
                    </div>

                </div>

            @endif

        </div>


        {{-- =========================================================
            MESSAGE SUCCÈS
        ========================================================== --}}
        @if (session('success'))

            <div class="flex items-center gap-3
                        bg-green-50 border border-green-200
                        text-[#016837] px-4 py-3 rounded-xl
                        mb-6 text-sm">

                <div class="w-7 h-7 rounded-lg bg-green-100
                            flex items-center justify-center shrink-0">

                    <svg class="w-4 h-4 text-[#016837]"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>

                </div>

                <span>{{ session('success') }}</span>

            </div>

        @endif


        {{-- =========================================================
            ACTIONS PRINCIPALES
        ========================================================== --}}
        <div class="mb-3">

            <p class="text-[10px] font-bold uppercase
                      tracking-[0.12em] text-gray-400">
                Gestion des colis
            </p>

            <p class="text-sm text-gray-500 mt-1">
                Sélectionnez l'opération à effectuer.
            </p>

        </div>


        <div class="grid grid-cols-1 gap-3">


            {{-- =====================================================
                DÉPÔT
            ====================================================== --}}
            <a href="{{ route('secretary.deposit.page') }}"
               class="group bg-white rounded-2xl border border-gray-100
                      shadow-sm p-5
                      hover:shadow-md hover:border-green-200
                      transition-all duration-200
                      flex items-center gap-4">

                <div class="w-12 h-12 bg-green-50
                            rounded-xl flex items-center
                            justify-center flex-shrink-0
                            group-hover:bg-[#016837]
                            transition-colors">

                    <svg class="w-6 h-6 text-[#016837]
                                group-hover:text-white transition-colors"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M20 13V7a2 2 0 00-2-2h-3l-2-2
                                 H7a2 2 0 00-2 2v6m0 0l-2 2
                                 2 2m0-4h14m0 0l2 2-2 2"/>

                    </svg>

                </div>

                <div class="flex-1 min-w-0">

                    <div class="flex items-center justify-between gap-3">

                        <h2 class="font-bold text-gray-900
                                   group-hover:text-[#016837]
                                   transition-colors">
                            Enregistrer un dépôt
                        </h2>

                        <span class="text-gray-300
                                     group-hover:text-[#016837]
                                     transition-colors">
                            →
                        </span>

                    </div>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Le vendeur vous donne son code — saisissez-le ici
                    </p>

                </div>

            </a>


            {{-- =====================================================
                ARRIVÉES
            ====================================================== --}}
            <a href="{{ route('secretary.arrivals.page') }}"
               class="group bg-white rounded-2xl border border-gray-100
                      shadow-sm p-5
                      hover:shadow-md hover:border-yellow-200
                      transition-all duration-200
                      flex items-center gap-4">

                <div class="w-12 h-12 bg-yellow-50
                            rounded-xl flex items-center
                            justify-center flex-shrink-0
                            group-hover:bg-[#F9A01B]
                            transition-colors">

                    <svg class="w-6 h-6 text-[#F9A01B]
                                group-hover:text-white transition-colors"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 16l4-4 4 4 4-4 6 6"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 20h14"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M7 12V5h10v7"/>

                    </svg>

                </div>

                <div class="flex-1 min-w-0">

                    <div class="flex items-center gap-3">

                        <h2 class="font-bold text-gray-900
                                   group-hover:text-[#016837]
                                   transition-colors">
                            Valider les arrivées
                        </h2>

                        @if ($stats['pending_arrivals'] > 0)

                            <span class="bg-[#F9A01B] text-white
                                         text-[11px] font-bold
                                         min-w-6 h-6 px-2
                                         rounded-full
                                         inline-flex items-center
                                         justify-center shadow-sm">

                                {{ $stats['pending_arrivals'] }}

                            </span>

                        @endif

                    </div>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Colis arrivés à valider à votre comptoir
                    </p>

                </div>

                <span class="text-gray-300
                             group-hover:text-[#F9A01B]
                             transition-colors">
                    →
                </span>

            </a>


            {{-- =====================================================
                REMISE OTP
            ====================================================== --}}
            <a href="{{ route('secretary.handover.page') }}"
               class="group bg-white rounded-2xl border border-gray-100
                      shadow-sm p-5
                      hover:shadow-md hover:border-red-200
                      transition-all duration-200
                      flex items-center gap-4">

                <div class="w-12 h-12 bg-red-50
                            rounded-xl flex items-center
                            justify-center flex-shrink-0
                            group-hover:bg-[#E30613]
                            transition-colors">

                    <svg class="w-6 h-6 text-[#E30613]
                                group-hover:text-white transition-colors"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 7a2 2 0 114 0v1a2 2 0
                                 01-2 2h-1m-2-3a5 5 0 11-5 5
                                 h-1a2 2 0 00-2 2v1a2 2 0
                                 002 2h5"/>

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 15v2m0 4h.01"/>

                    </svg>

                </div>

                <div class="flex-1 min-w-0">

                    <div class="flex items-center gap-3">

                        <h2 class="font-bold text-gray-900
                                   group-hover:text-[#016837]
                                   transition-colors">
                            Remettre un colis
                        </h2>

                        @if ($stats['pending_handovers'] > 0)

                            <span class="bg-[#E30613] text-white
                                         text-[11px] font-bold
                                         min-w-6 h-6 px-2
                                         rounded-full
                                         inline-flex items-center
                                         justify-center shadow-sm">

                                {{ $stats['pending_handovers'] }}

                            </span>

                        @endif

                    </div>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Saisir le code OTP de l'acheteur
                    </p>

                </div>

                <span class="text-gray-300
                             group-hover:text-[#E30613]
                             transition-colors">
                    →
                </span>

            </a>

        </div>


        {{-- =========================================================
            LÉGENDE RAPIDE
        ========================================================== --}}
        <div class="mt-6 flex flex-wrap items-center justify-center
                    gap-x-5 gap-y-2 text-[10px] text-gray-400">

            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#016837]"></span>
                Dépôt
            </div>

            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#F9A01B]"></span>
                Arrivée
            </div>

            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#E30613]"></span>
                Remise OTP
            </div>

        </div>

    </div>
</div>

@endsection