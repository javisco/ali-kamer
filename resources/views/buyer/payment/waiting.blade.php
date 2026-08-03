@extends('base')

@section('title', 'Paiement en attente')

@section('content')
    <div class="min-h-[85vh] flex flex-col justify-center items-center px-4 py-6 bg-gray-50/50">

        <div class="w-full max-w-4xl space-y-4">

            {{-- Fil d'Ariane compact --}}
            <nav class="flex items-center text-xs text-gray-500">
                <a href="{{ route('buyer.orders.index') }}" class="hover:text-blue-600 transition">
                    Mes commandes
                </a>
                <svg class="w-3 h-3 mx-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-800 font-medium">Paiement</span>
            </nav>

            {{-- Carte Principale Centrée --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- En-tête Compact --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5 text-white">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="relative flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white animate-spin" style="animation-duration:2s" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V3m0 18v-3m6-6h3M3 12h3m9.364-6.364l2.121-2.121M4.515 19.485l2.121-2.121m0-10.728L4.515 4.515m14.97 14.97l-2.121-2.121" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold leading-tight">Confirmation du paiement</h1>
                                <p class="text-blue-100 text-xs mt-0.5">Paiement sécurisé Ali-Kamer</p>
                            </div>
                        </div>

                        <span id="paymentBadge" class="px-3 py-1 rounded-full bg-yellow-400/20 backdrop-blur-md text-yellow-100 text-xs font-semibold border border-yellow-300/30">
                            En attente
                        </span>
                    </div>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Dynamic Status Messages --}}
                    <div id="successBox" class="hidden rounded-xl border border-green-200 bg-green-50 p-4">
                        <h3 class="font-bold text-green-800 text-sm">Paiement confirmé !</h3>
                        <p class="text-green-700 text-xs mt-1">Votre paiement a été reçu avec succès. Redirection en cours...</p>
                    </div>

                    <div id="failedBox" class="hidden rounded-xl border border-red-200 bg-red-50 p-4">
                        <h3 class="font-bold text-red-800 text-sm">Paiement échoué</h3>
                        <p class="text-red-700 text-xs mt-1">Le paiement n'a pas pu être validé. Veuillez réessayer.</p>
                    </div>

                    {{-- Zone d'Action Requise + Timer en Ligne --}}
                    <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M12 6a1 1 0 110 2 1 1 0 010-2zm0 4v4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-blue-950 text-sm">Validez sur votre téléphone</h3>
                                <p class="text-xs text-blue-800 mt-0.5">Saisissez le code secret Mobile Money pour valider la transaction.</p>
                            </div>
                        </div>

                        {{-- Timer Mini SVG --}}
                        <div class="flex items-center gap-3 bg-white px-3 py-1.5 rounded-lg border border-blue-100 shadow-sm flex-shrink-0">
                            <div class="relative w-8 h-8 flex items-center justify-center">
                                <svg class="w-8 h-8 transform -rotate-90">
                                    <circle cx="16" cy="16" r="13" stroke="#e5e7eb" stroke-width="3" fill="none" />
                                    <circle cx="16" cy="16" r="13" stroke="#2563eb" stroke-width="3" fill="none" stroke-linecap="round" stroke-dasharray="82" stroke-dashoffset="0" id="progressCircle" />
                                </svg>
                            </div>
                            <div class="text-right">
                                <span id="timer" class="text-sm font-bold text-blue-700 block leading-none">10:00</span>
                                <span class="text-[10px] text-gray-400">restantes</span>
                            </div>
                        </div>
                    </div>

                    {{-- Progression Compacte en Ligne --}}
                    <div class="border-t border-b border-gray-100 py-4">
                        <div class="grid grid-cols-3 gap-2 text-center relative">
                            {{-- Étape 1 --}}
                            <div class="flex flex-col items-center">
                                <div class="w-7 h-7 rounded-full bg-green-500 text-white flex items-center justify-center text-xs mb-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-800">Demande envoyée</span>
                            </div>

                            {{-- Étape 2 --}}
                            <div class="flex flex-col items-center">
                                <div id="step2" class="w-7 h-7 rounded-full bg-blue-600 animate-pulse text-white flex items-center justify-center text-xs mb-1">
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V3m0 18v-3m6-6h3M3 12h3" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-800">Confirmation client</span>
                            </div>

                            {{-- Étape 3 --}}
                            <div class="flex flex-col items-center">
                                <div id="step3" class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 font-bold flex items-center justify-center text-xs mb-1">
                                    3
                                </div>
                                <span class="text-[11px] font-semibold text-gray-500">Validation finalisée</span>
                            </div>
                        </div>
                    </div>

                    {{-- Détails + Résumé en 2 colonnes ultra compactes --}}
                    <div class="grid md:grid-cols-2 gap-4 text-xs">

                        {{-- Infos Transaction --}}
                        <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-100 space-y-2">
                            <h4 class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-2">Informations</h4>
                            <div class="flex justify-between border-b border-gray-200/60 pb-1.5">
                                <span class="text-gray-500">Réf. Commande:</span>
                                <span class="font-semibold text-gray-800 break-all">{{ $order->reference }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-200/60 pb-1.5">
                                <span class="text-gray-500">Réf. Campay:</span>
                                <span class="font-semibold text-gray-800 break-all">{{ $order->payment->provider_reference ?? 'En attente...' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-200/60 pb-1.5">
                                <span class="text-gray-500">Numéro MoMo:</span>
                                <span class="font-semibold text-gray-800">{{ $order->payment->payer_phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Moyen de paiement:</span>
                                <span class="font-semibold text-gray-800 uppercase">{{ $order->payment->method }}</span>
                            </div>
                        </div>

                        {{-- Résumé Financier --}}
                        <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-100 space-y-2 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-2">Montants</h4>
                                <div class="flex justify-between text-gray-600 mb-1">
                                    <span>Sous-total:</span>
                                    <span>{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="flex justify-between text-gray-600 mb-1">
                                    <span>Frais (Livraison/Gestion):</span>
                                    <span>{{ number_format($order->shipping_fee + $order->protection_fee + $order->gateway_fee, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-200 font-bold text-sm text-blue-900 bg-blue-100/40 -mx-3.5 -mb-3.5 p-3 rounded-b-xl">
                                <span>Total à payer:</span>
                                <span class="text-base text-blue-700 font-extrabold">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>

                    </div>

                    {{-- Conseil rapide --}}
                    <p class="text-[11px] text-gray-400 text-center">
                        🔒 Ne quittez pas cette page et ne partagez jamais votre code secret Mobile Money.
                    </p>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button id="refreshButton" class="flex-1 bg-blue-600 hover:bg-blue-700 transition text-white rounded-xl py-2.5 px-4 font-semibold text-xs shadow-sm">
                            Actualiser le statut
                        </button>
                        <a href="{{ route('buyer.orders.index') }}" class="flex-1 text-center border border-gray-300 rounded-xl py-2.5 px-4 font-semibold text-xs text-gray-700 hover:bg-gray-50 transition">
                            Retour aux commandes
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        /*
        |--------------------------------------------------------------------------
        | Configuration
        |--------------------------------------------------------------------------
        */
        const statusUrl = "{{ route('buyer.orders.status', $order) }}";
        const successRedirect = "{{ route('buyer.orders.index') }}";

        /*
        |--------------------------------------------------------------------------
        | Éléments HTML
        |--------------------------------------------------------------------------
        */
        const badge = document.getElementById('paymentBadge');
        const timer = document.getElementById('timer');
        const successBox = document.getElementById('successBox');
        const failedBox = document.getElementById('failedBox');
        const refreshButton = document.getElementById('refreshButton');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        const progressCircle = document.getElementById('progressCircle');

        /*
        |--------------------------------------------------------------------------
        | Compte à rebours
        |--------------------------------------------------------------------------
        */
        let remaining = 600; // 10 minutes
        const circumference = 82; // Ajusté à la nouvelle taille de cercle (r=13)

        function updateTimer() {
            if (remaining <= 0) return;

            remaining--;

            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;

            timer.innerHTML = `${minutes}:${seconds.toString().padStart(2,'0')}`;

            const progress = circumference - (remaining / 600) * circumference;
            progressCircle.style.strokeDashoffset = progress;
        }

        setInterval(updateTimer, 1000);

        /*
        |--------------------------------------------------------------------------
        | Vérification Campay
        |--------------------------------------------------------------------------
        */
        async function checkStatus() {
            try {
                const response = await fetch(statusUrl, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json"
                    }
                });

                const data = await response.json();
                handleStatus(data.status);
            } catch (error) {
                console.error(error);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Mise à jour de l'interface
        |--------------------------------------------------------------------------
        */
        function handleStatus(status) {
            switch (status) {
                case "paid":
                    badge.innerHTML = "Payé";
                    badge.className = "px-3 py-1 rounded-full bg-green-500/20 text-green-100 text-xs font-semibold border border-green-300/30";

                    successBox.classList.remove("hidden");

                    step2.className = "w-7 h-7 rounded-full bg-green-500 text-white flex items-center justify-center text-xs mb-1";
                    step2.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;

                    step3.className = "w-7 h-7 rounded-full bg-green-500 text-white flex items-center justify-center text-xs mb-1";
                    step3.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;

                    clearInterval(polling);

                    setTimeout(function() {
                        window.location.href = successRedirect;
                    }, 3000);
                    break;

                case "failed":
                    badge.innerHTML = "Échec";
                    badge.className = "px-3 py-1 rounded-full bg-red-500/20 text-red-100 text-xs font-semibold border border-red-300/30";

                    failedBox.classList.remove("hidden");
                    clearInterval(polling);
                    break;

                default:
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Events & Polling
        |--------------------------------------------------------------------------
        */
        refreshButton.addEventListener("click", function() {
            checkStatus();
        });

        checkStatus();
        const polling = setInterval(function() {
            checkStatus();
        }, 5000);
    </script>
@endpush