@extends('base')
@section('title', 'Dashboard Secrétaire')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-3xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Interface Secrétaire</h1>

    @if($counter)
        <p class="text-sm text-gray-500 mb-6">
            📍 {{ $counter->full_name }}
        </p>
    @else
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
            ⚠ Aucun comptoir assigné. Contactez l'administrateur.
        </div>
    @endif

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Trois actions principales --}}
    <div class="grid grid-cols-1 gap-4">

        {{-- Dépôt --}}
        <a href="{{ route('secretary.deposit.page') }}"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6
                  hover:shadow-md hover:border-indigo-200 transition flex items-center gap-5">
            <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                📦
            </div>
            <div>
                <h2 class="font-bold text-gray-900">Enregistrer un dépôt</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Le vendeur vous donne son code — saisissez-le ici
                </p>
            </div>
        </a>

        {{-- Arrivées --}}
        <a href="{{ route('secretary.arrivals.page') }}"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6
                  hover:shadow-md hover:border-teal-200 transition flex items-center gap-5">
            <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                🚚
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-gray-900">Valider les arrivées</h2>
                    @if($stats['pending_arrivals'] > 0)
                        <span class="bg-teal-600 text-white text-xs font-bold px-2.5 py-0.5 rounded-full">
                            {{ $stats['pending_arrivals'] }}
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-0.5">
                    Colis arrivés à valider à votre comptoir
                </p>
            </div>
        </a>

        {{-- Remises OTP --}}
        <a href="{{ route('secretary.handover.page') }}"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6
                  hover:shadow-md hover:border-orange-200 transition flex items-center gap-5">
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                🔑
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-gray-900">Remettre un colis</h2>
                    @if($stats['pending_handovers'] > 0)
                        <span class="bg-orange-500 text-white text-xs font-bold px-2.5 py-0.5 rounded-full">
                            {{ $stats['pending_handovers'] }}
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-0.5">
                    Saisir le code OTP de l'acheteur
                </p>
            </div>
        </a>

    </div>
</div>
</div>
@endsection