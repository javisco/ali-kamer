@extends('base')
@section('title', 'En attente — Paiement transport')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-md mx-auto px-4 text-center">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 mb-6">

                <div
                    class="w-20 h-20 bg-orange-50 rounded-full flex items-center
                    justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-orange-500 animate-pulse" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2
                             2v14a2 2 0 002 2z" />
                    </svg>
                </div>

                <h1 class="text-xl font-extrabold text-gray-900 mb-2">
                    En attente de confirmation
                </h1>
                <p class="text-sm text-gray-500 mb-6">
                    Confirmez le paiement de
                    <strong>
                        {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }} FCFA
                    </strong>
                    sur votre téléphone.
                </p>

                {{-- Étapes --}}
                <div class="text-left space-y-3 mb-6">
                    <div class="flex items-center gap-3 text-sm">
                        <div
                            class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 font-bold
                            text-xs flex items-center justify-center flex-shrink-0">
                            ✓</div>
                        <span class="text-gray-600">Commande et colis arrivés</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div
                            class="w-6 h-6 rounded-full bg-orange-500 text-white font-bold
                            text-xs flex items-center justify-center flex-shrink-0 animate-pulse">
                            2</div>
                        <span class="text-gray-800 font-medium">
                            Confirmez le paiement transport sur votre téléphone
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div
                            class="w-6 h-6 rounded-full bg-gray-100 text-gray-400 font-bold
                            text-xs flex items-center justify-center flex-shrink-0">
                            3</div>
                        <span class="text-gray-400">Recevoir votre code OTP de retrait</span>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-3 text-sm text-gray-500" id="statusArea">
                    Vérification en cours...
                </div>
            </div>

            <a href="{{ route('buyer.orders.show', $order) }}" class="text-sm text-indigo-600 hover:underline">
                Voir ma commande
            </a>

        </div>
    </div>

    @push('scripts')
        <script>
            // Polling toutes les 5 secondes pour vérifier si le transport est payé
            const statusArea = document.getElementById('statusArea');

            const check = setInterval(function() {
                fetch('{{ route('buyer.orders.transport.status', $order) }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.paid) {
                            clearInterval(check);
                            statusArea.innerHTML = '✅ Paiement confirmé ! Votre OTP vous a été envoyé.';
                            statusArea.className = 'bg-emerald-50 rounded-xl p-3 text-sm text-emerald-600';
                            setTimeout(() => {
                                window.location.href = '{{ route('buyer.orders.show', $order) }}';
                            }, 2000);
                        }
                    })
                    .catch(() => {});
            }, 5000);
        </script>
    @endpush
@endsection
