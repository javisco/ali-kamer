@extends('base')
@section('title', 'Remise de colis')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4">

            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('secretary.dashboard') }}" class="text-gray-400 hover:text-gray-600">←</a>
                <h1 class="text-2xl font-extrabold text-gray-900">Remise de colis</h1>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('otp'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ $errors->first('otp') }}
                </div>
            @endif

            @if ($orders->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
                    <p class="text-gray-400 text-sm">Aucun colis en attente de remise.</p>
                </div>
            @else
                <p class="text-xs text-gray-500 mb-3">
                    {{ $orders->count() }} colis en attente — saisir le code OTP de l'acheteur
                </p>

                <div class="space-y-5">
                    @foreach ($orders as $order)
                        @php
                            // Le colis est bloqué si transport non payé
                            $transportBlocked =
                                !$order->shipment->shipping_included &&
                                $order->shipment->transport_fee > 0 &&
                                !$order->shipment->transport_fee_paid;
                        @endphp

                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

                            {{-- Infos colis --}}
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $order->reference }}</p>
                                    <p class="text-sm text-gray-500 mt-0.5">
                                        {{ $order->shipment->recipient_name }}
                                        — {{ $order->buyer->phone }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Arrivé le {{ $order->shipment->arrived_at?->format('d/m/Y à H:i') }}
                                    </p>
                                </div>

                                {{-- Badge timer --}}
                                @if ($order->timer_deadline)
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">Expire</p>
                                        <p
                                            class="text-xs font-bold {{ $order->isTimerExpired() ? 'text-red-500' : 'text-orange-500' }}">
                                            {{ $order->timer_deadline->format('d/m H:i') }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            {{-- Alerte transport non payé --}}
                            @if ($transportBlocked)
                                <div class="bg-orange-50 border border-orange-200 rounded-xl p-3 mb-4">
                                    <p class="text-sm font-bold text-orange-700">
                                        ⚠ Frais transport non payés
                                    </p>
                                    <p class="text-xs text-orange-600 mt-1">
                                        L'acheteur doit payer
                                        {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }} FCFA
                                        avant de récupérer son colis.
                                        Le paiement se fait depuis son espace commande.
                                    </p>
                                </div>
                            @endif

                            {{-- Formulaire OTP --}}
                            <form method="POST" action="{{ route('secretary.handover.otp', $order) }}">
                                @csrf
                                <div class="flex gap-3">
                                    <input type="text" name="otp" maxlength="6" placeholder="_ _ _ _ _ _"
                                        {{ $transportBlocked ? 'disabled' : '' }}
                                        class="flex-1 border border-gray-300 rounded-xl px-4 py-3
                                          text-center text-xl font-mono tracking-[0.4em]
                                          focus:ring-2 focus:ring-indigo-500
                                          {{ $transportBlocked ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : '' }}">
                                    <button {{ $transportBlocked ? 'disabled' : '' }}
                                        class="px-6 py-3 rounded-xl font-bold text-sm transition
                                           {{ $transportBlocked
                                               ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                               : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                                        Valider
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
