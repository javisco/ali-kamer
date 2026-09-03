@extends('layouts.buyer')

@section('title', 'Historique des transactions')

@section('content')

<div class="min-h-screen bg-[#F7F7F2] py-6 sm:py-8">
    <div class="max-w-3xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="flex items-center justify-between mb-6">

            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#F9A01B]"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider
                                 text-[#F9A01B]">
                        Mon portefeuille
                    </span>
                </div>

                <h1 class="text-2xl font-extrabold text-[#0a1b12]">
                    Historique des transactions
                </h1>
            </div>

            <div class="w-10 h-10 rounded-xl bg-[#016837] text-white
                        flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

        </div>

        @if($transactions->isEmpty())

            {{-- Aucun mouvement --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm
                        p-10 text-center">

                <div class="mx-auto w-14 h-14 rounded-2xl bg-[#F9A01B]/10
                            flex items-center justify-center mb-4">

                    <svg class="w-7 h-7 text-[#F9A01B]" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>

                </div>

                <h2 class="text-base font-bold text-[#0a1b12]">
                    Aucun mouvement
                </h2>

                <p class="text-gray-400 text-sm mt-1">
                    Aucune transaction pour l'instant.
                </p>

            </div>

        @else

            {{-- Liste des transactions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm
                        overflow-hidden">

                <div class="divide-y divide-gray-100">

                    @foreach($transactions as $tx)

                        <div class="flex items-center justify-between
                                    px-4 sm:px-5 py-4
                                    hover:bg-[#F7F7F2]/70 transition">

                            <div class="flex-1 min-w-0 pr-3">

                                <div class="flex items-center gap-2">

                                    {{-- Indicateur --}}
                                    <span class="w-7 h-7 rounded-lg flex-shrink-0
                                        flex items-center justify-center
                                        {{ $tx->isCredit()
                                            ? 'bg-[#016837]/10 text-[#016837]'
                                            : 'bg-[#E30613]/10 text-[#E30613]' }}">

                                        @if($tx->isCredit())
                                            <svg class="w-3.5 h-3.5" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                            </svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                            </svg>
                                        @endif

                                    </span>

                                    <p class="text-sm font-bold text-[#0a1b12] truncate">
                                        {{ $tx->typeLabel() }}
                                    </p>

                                </div>

                                @if($tx->note)
                                    <p class="text-xs text-gray-400 mt-1 ml-9 truncate">
                                        {{ $tx->note }}
                                    </p>
                                @endif

                                <p class="text-[11px] text-gray-300 mt-1 ml-9">
                                    {{ $tx->created_at->format('d/m/Y à H:i') }}
                                </p>

                            </div>

                            <div class="text-right flex-shrink-0">

                                <p class="font-extrabold text-sm
                                    {{ $tx->isCredit()
                                        ? 'text-[#016837]'
                                        : 'text-[#E30613]' }}">

                                    {{ $tx->isCredit() ? '+' : '-' }}
                                    {{ number_format($tx->amount, 0, ',', ' ') }} FCFA

                                </p>

                                <span class="inline-block mt-1 text-[9px] font-bold
                                    uppercase tracking-wide
                                    {{ $tx->isCredit()
                                        ? 'text-[#016837] bg-[#016837]/10'
                                        : 'text-[#E30613] bg-[#E30613]/10' }}
                                    px-1.5 py-0.5 rounded">

                                    {{ $tx->isCredit() ? 'Crédit' : 'Débit' }}

                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-5">
                {{ $transactions->links() }}
            </div>

        @endif

    </div>
</div>

@endsection