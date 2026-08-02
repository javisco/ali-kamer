@extends('base')

@section('title', 'En attente de paiement')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-md mx-auto px-4 text-center">

            {{-- Animation d'attente --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 mb-6">

                {{-- Icône animée --}}
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-indigo-600 animate-pulse" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>

                <h1 class="text-xl font-extrabold text-gray-900 mb-2">
                    En attente de confirmation
                </h1>
                <p class="text-sm text-gray-500 mb-6">
                    Vérifiez votre téléphone <strong>(+237 {{ $order->payment->payer_phone }})</strong>
                    et confirmez le paiement de
                    <strong>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</strong>.
                </p>

                {{-- Étapes --}}
                <div class="text-left space-y-3 mb-6">
                    <div class="flex items-center gap-3 text-sm">
                        <div
                            class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 font-bold
                            text-xs flex items-center justify-center flex-shrink-0">
                            ✓</div>
                        <span class="text-gray-600">Commande créée</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div
                            class="w-6 h-6 rounded-full bg-indigo-600 text-white font-bold
                            text-xs flex items-center justify-center flex-shrink-0 animate-pulse">
                            2</div>
                        <span class="text-gray-800 font-medium">Confirmez le paiement sur votre téléphone</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div
                            class="w-6 h-6 rounded-full bg-gray-100 text-gray-400 font-bold
                            text-xs flex items-center justify-center flex-shrink-0">
                            3</div>
                        <span class="text-gray-400">Commande confirmée</span>
                    </div>
                </div>

                {{-- Statut actuel --}}
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
            // Vérifier le statut de la commande toutes les 5 secondes
            // Rediriger automatiquement dès que le paiement est confirmé
            const orderId = {{ $order->id }};
            const statusArea = document.getElementById('statusArea');

            const checkStatus = setInterval(function() {
                fetch(`/commandes/${orderId}/statut`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'paid') {
                            // Paiement confirmé — rediriger vers le suivi commande
                            clearInterval(checkStatus);
                            statusArea.innerHTML = '✅ Paiement confirmé ! Redirection...';
                            statusArea.className = 'bg-emerald-50 rounded-xl p-3 text-sm text-emerald-600';
                            setTimeout(() => {
                                window.location.href = `/commandes/${orderId}`;
                            }, 1500);

                        } else if (data.status === 'failed') {
                            // Paiement échoué
                            clearInterval(checkStatus);
                            statusArea.innerHTML = '❌ Paiement échoué. Veuillez réessayer.';
                            statusArea.className = 'bg-red-50 rounded-xl p-3 text-sm text-red-600';
                        }
                    })
                    .catch(() => {}); // Ignorer les erreurs réseau
            }, 5000);
        </script>
    @endpush
@endsection
