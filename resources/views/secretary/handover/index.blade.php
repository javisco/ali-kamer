@extends('base')

@section('title', 'Remise de colis')

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
                <p class="text-[10px] font-bold uppercase
                          tracking-wider text-accent-500">
                    Livraison
                </p>

                <h1 class="text-2xl font-extrabold text-slate-900">
                    Remise de colis
                </h1>
            </div>

        </div>

        {{-- Succès --}}
        @if (session('success'))

            <div class="bg-success-50 border border-success-200
                        text-primary-600 px-4 py-3 rounded-xl mb-6
                        text-sm flex items-center gap-2">

                <span class="w-2 h-2 rounded-full bg-primary-600"></span>

                {{ session('success') }}

            </div>

        @endif

        {{-- Erreur OTP --}}
        @if ($errors->has('otp'))

            <div class="bg-danger-50 border border-danger-200
                        text-danger px-4 py-3 rounded-xl mb-6
                        text-sm flex items-center gap-2">

                <span class="w-2 h-2 rounded-full bg-danger"></span>

                {{ $errors->first('otp') }}

            </div>

        @endif

        @if ($orders->isEmpty())

            <div class="bg-white rounded-2xl border border-slate-100
                        shadow-sm p-10 text-center">

                <div class="w-12 h-12 mx-auto mb-3 rounded-2xl
                            bg-success-50 flex items-center justify-center">

                    <span class="text-xl text-primary-600">✓</span>

                </div>

                <p class="font-semibold text-slate-700">
                    Aucun colis en attente de remise.
                </p>

                <p class="text-slate-400 text-sm mt-1">
                    Les colis prêts à être remis apparaîtront ici.
                </p>

            </div>

        @else

            <div class="flex items-center justify-between mb-3">

                <p class="text-xs text-slate-500">
                    <span class="font-bold text-slate-900">
                        {{ $orders->count() }}
                    </span>
                    colis en attente — saisir le code OTP de l'acheteur
                </p>

                <span class="w-2 h-2 rounded-full bg-accent-500"></span>

            </div>

            <div class="space-y-5">

                @foreach ($orders as $order)

                    @php
                        // Le colis est bloqué si transport non payé
                        $transportBlocked =
                            !$order->shipment->shipping_included &&
                            $order->shipment->transport_fee > 0 &&
                            !$order->shipment->transport_fee_paid;
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-100
                                shadow-sm p-5 hover:shadow-md
                                transition-all">

                        {{-- Infos colis --}}
                        <div class="flex items-start justify-between mb-4">

                            <div>

                                <p class="font-bold text-slate-900">
                                    {{ $order->reference }}
                                </p>

                                <p class="text-sm text-slate-500 mt-0.5">
                                    {{ $order->shipment->recipient_name }}
                                    — {{ $order->buyer->phone }}
                                </p>

                                <p class="text-xs text-slate-400 mt-0.5">
                                    Arrivé le
                                    {{ $order->shipment->arrived_at?->format('d/m/Y à H:i') }}
                                </p>

                            </div>

                            {{-- Badge timer --}}
                            @if ($order->timer_deadline)

                                <div class="text-right">

                                    <p class="text-xs text-slate-400">
                                        Expire
                                    </p>

                                    <p class="text-xs font-bold
                                        {{ $order->isTimerExpired()
                                            ? 'text-danger'
                                            : 'text-accent-500' }}">

                                        {{ $order->timer_deadline->format('d/m H:i') }}

                                    </p>

                                </div>

                            @endif

                        </div>

                        {{-- Transport non payé --}}
                        @if ($transportBlocked)

                            <div class="bg-warning-50 border border-warning-200
                                        rounded-xl p-3 mb-4">

                                <div class="flex items-start gap-3">

                                    <div class="w-8 h-8 rounded-lg
                                                bg-accent-500/15
                                                flex items-center justify-center
                                                shrink-0">

                                        <span class="text-accent-500 font-bold">
                                            !
                                        </span>

                                    </div>

                                    <div>

                                        <p class="text-sm font-bold text-accent-600">
                                            Frais transport non payés
                                        </p>

                                        <p class="text-xs text-accent-600 mt-1">
                                            L'acheteur doit payer
                                            {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }}
                                            FCFA avant de récupérer son colis.
                                            Le paiement se fait depuis son espace commande.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @endif

                        {{-- Formulaire OTP --}}
                        <form method="POST"
                              action="{{ route('secretary.handover.otp', $order) }}">
                            @csrf

                            <div class="flex flex-col sm:flex-row gap-3">

                                <input
                                    type="text"
                                    name="otp"
                                    maxlength="6"
                                    placeholder="_ _ _ _ _ _"
                                    {{ $transportBlocked ? 'disabled' : '' }}
                                    class="flex-1 border border-slate-200
                                           bg-slate-50 rounded-xl px-4 py-3
                                           text-center text-xl font-mono
                                           tracking-[0.4em]
                                           focus:outline-none focus:bg-white
                                           focus:border-primary-600
                                           focus:ring-2
                                           focus:ring-success/10
                                           {{ $transportBlocked
                                               ? 'bg-slate-100 text-slate-400 cursor-not-allowed'
                                               : '' }}">

                                <button
                                    {{ $transportBlocked ? 'disabled' : '' }}
                                    class="px-6 py-3 rounded-xl font-bold
                                           text-sm transition-all
                                           {{ $transportBlocked
                                               ? 'bg-slate-200 text-slate-400 cursor-not-allowed'
                                               : 'bg-primary-600 hover:bg-primary-700
                                                  text-white shadow-sm hover:shadow-md' }}">

                                    {{ $transportBlocked
                                        ? 'Paiement requis'
                                        : 'Valider' }}

                                </button>

                            </div>

                        </form>

                    </div>

                @endforeach

            </div>

        @endif

    </div>
</div>

@endsection