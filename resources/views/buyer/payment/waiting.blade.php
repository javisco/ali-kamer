@extends('base')

@section('title', 'Paiement en attente')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">

        {{-- ===========================
        Fil d'Ariane
    ============================ --}}
        <nav class="flex items-center text-sm text-gray-500 mb-6">
            <a href="{{ route('buyer.orders.index') }}" class="hover:text-blue-600 transition">
                Mes commandes
            </a>

            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>

            <span class="text-gray-800 font-medium">
                Paiement
            </span>
        </nav>

        {{-- ===========================
        En-tête
    ============================ --}}

        <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-3xl shadow-xl overflow-hidden">

            <div class="px-8 py-10 text-white">

                <div class="flex flex-col lg:flex-row justify-between gap-8">

                    <div>

                        <div
                            class="inline-flex items-center px-4 py-2 rounded-full bg-white/15 backdrop-blur-sm text-sm font-medium mb-5">
                            Paiement sécurisé Ali-Kamer
                        </div>

                        <h1 class="text-3xl lg:text-4xl font-bold mb-3">
                            Confirmation du paiement
                        </h1>

                        <p class="text-blue-100 text-lg leading-relaxed max-w-2xl">
                            Une demande de paiement a été envoyée sur votre téléphone.
                            Ouvrez la notification Mobile Money et confirmez la transaction
                            pour finaliser votre commande.
                        </p>

                    </div>

                    <div class="flex justify-center items-center">

                        <div class="relative">

                            <div class="absolute inset-0 rounded-full bg-white opacity-20 animate-ping"></div>

                            <div
                                class="relative w-28 h-28 rounded-full bg-white flex items-center justify-center shadow-2xl">

                                <svg class="w-14 h-14 text-blue-600 animate-spin" style="animation-duration:2s"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V3m0 18v-3m6-6h3M3 12h3m9.364-6.364l2.121-2.121M4.515 19.485l2.121-2.121m0-10.728L4.515 4.515m14.97 14.97l-2.121-2.121" />
                                </svg>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- ===========================
        Cartes principales
    ============================ --}}

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mt-8">

            {{-- Carte principale --}}
            <div class="xl:col-span-2">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                    <div class="px-8 py-6 border-b bg-gray-50">

                        <div class="flex items-center justify-between">

                            <div>

                                <h2 class="text-2xl font-bold text-gray-800">
                                    Paiement en cours
                                </h2>

                                <p class="text-gray-500 mt-1">
                                    Veuillez confirmer la demande sur votre téléphone.
                                </p>

                            </div>

                            <span id="paymentBadge"
                                class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 font-semibold">

                                En attente

                            </span>

                        </div>

                    </div>

                    <div class="p-8">

                        {{-- Bloc d'information --}}
                        <div class="rounded-2xl bg-blue-50 border border-blue-100 p-6">

                            <div class="flex items-start">

                                <div class="flex-shrink-0">

                                    <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">

                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01" />

                                        </svg>

                                    </div>

                                </div>

                                <div class="ml-5">

                                    <h3 class="font-bold text-blue-900 text-lg">
                                        Action requise
                                    </h3>

                                    <p class="mt-2 text-blue-800 leading-relaxed">

                                        Vérifiez votre téléphone Mobile Money.

                                        Une notification de paiement vous a été envoyée.

                                        Saisissez votre code secret pour confirmer la transaction.

                                    </p>

                                </div>

                            </div>

                        </div>

                        {{-- Les informations de la commande seront ajoutées ici --}}
                        {{-- ===========================
                        Informations de la commande
                    ============================ --}}

                        <div class="mt-8">

                            <h3 class="text-xl font-bold text-gray-800 mb-6">
                                Détails de la transaction
                            </h3>

                            <div class="grid md:grid-cols-2 gap-6">

                                {{-- Référence --}}
                                <div class="rounded-2xl border border-gray-200 p-5">

                                    <p class="text-sm text-gray-500 mb-2">
                                        Référence de commande
                                    </p>

                                    <p class="font-bold text-lg text-gray-900 break-all">
                                        {{ $order->reference }}
                                    </p>

                                </div>

                                {{-- Référence Campay --}}
                                <div class="rounded-2xl border border-gray-200 p-5">

                                    <p class="text-sm text-gray-500 mb-2">
                                        Référence Campay
                                    </p>

                                    <p class="font-semibold text-gray-800 break-all">
                                        {{ $order->payment->provider_reference ?? 'En attente...' }}
                                    </p>

                                </div>

                                {{-- Téléphone --}}
                                <div class="rounded-2xl border border-gray-200 p-5">

                                    <p class="text-sm text-gray-500 mb-2">
                                        Numéro Mobile Money
                                    </p>

                                    <p class="font-semibold text-gray-800">
                                        {{ $order->payment->payer_phone }}
                                    </p>

                                </div>

                                {{-- Méthode --}}
                                <div class="rounded-2xl border border-gray-200 p-5">

                                    <p class="text-sm text-gray-500 mb-2">
                                        Moyen de paiement
                                    </p>

                                    <p class="font-semibold text-gray-800">
                                        {{ strtoupper($order->payment->method) }}
                                    </p>

                                </div>

                            </div>

                        </div>

                        {{-- ===========================
                        Résumé financier
                    ============================ --}}

                        <div class="mt-10">

                            <h3 class="text-xl font-bold text-gray-800 mb-6">
                                Résumé
                            </h3>

                            <div class="rounded-3xl border border-gray-200 overflow-hidden">

                                <div class="flex justify-between px-6 py-4 border-b">

                                    <span class="text-gray-600">
                                        Sous-total
                                    </span>

                                    <span class="font-semibold">
                                        {{ number_format($order->subtotal, 0, ',', ' ') }} FCFA
                                    </span>

                                </div>

                                <div class="flex justify-between px-6 py-4 border-b">

                                    <span class="text-gray-600">
                                        Livraison
                                    </span>

                                    <span class="font-semibold">
                                        {{ number_format($order->shipping_fee, 0, ',', ' ') }} FCFA
                                    </span>

                                </div>

                                <div class="flex justify-between px-6 py-4 border-b">

                                    <span class="text-gray-600">
                                        Protection acheteur
                                    </span>

                                    <span class="font-semibold">
                                        {{ number_format($order->protection_fee, 0, ',', ' ') }} FCFA
                                    </span>

                                </div>

                                <div class="flex justify-between px-6 py-4 border-b">

                                    <span class="text-gray-600">
                                        Frais de paiement
                                    </span>

                                    <span class="font-semibold">
                                        {{ number_format($order->gateway_fee, 0, ',', ' ') }} FCFA
                                    </span>

                                </div>

                                <div class="flex justify-between px-6 py-6 bg-blue-50">

                                    <span class="text-xl font-bold text-blue-900">
                                        Total à payer
                                    </span>

                                    <span class="text-3xl font-extrabold text-blue-700">

                                        {{ number_format($order->total_amount, 0, ',', ' ') }}

                                        FCFA

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================
            Colonne de droite
        ====================================== --}}

            <div>

                {{-- Etat du paiement --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-7">

                    <h3 class="text-xl font-bold mb-6">

                        Etat du paiement

                    </h3>

                    <div class="flex justify-center">

                        <div class="relative">

                            <svg class="w-32 h-32 transform -rotate-90">

                                <circle cx="64" cy="64" r="56" stroke="#e5e7eb" stroke-width="10"
                                    fill="none" />

                                <circle cx="64" cy="64" r="56" stroke="#2563eb" stroke-width="10"
                                    fill="none" stroke-linecap="round" stroke-dasharray="351" stroke-dashoffset="260"
                                    id="progressCircle" />

                            </svg>

                            <div class="absolute inset-0 flex flex-col justify-center items-center">

                                <span id="timer" class="text-3xl font-bold text-blue-700">

                                    10:00

                                </span>

                                <span class="text-gray-500 text-sm">

                                    restantes

                                </span>

                            </div>

                        </div>

                    </div>

                    <p class="text-center text-gray-500 mt-6 leading-relaxed">

                        Vous disposez de quelques minutes pour confirmer la
                        demande de paiement reçue sur votre téléphone.

                    </p>

                </div>

                <div class="h-6"></div>
                {{-- ===========================
                Progression du paiement
            ============================ --}}

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-7">

                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        Progression
                    </h3>

                    <div class="space-y-8">

                        {{-- Etape 1 --}}
                        <div class="flex">

                            <div class="mr-4">

                                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">

                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />

                                    </svg>

                                </div>

                            </div>

                            <div>

                                <h4 class="font-semibold text-gray-800">
                                    Demande envoyée
                                </h4>

                                <p class="text-sm text-gray-500 mt-1">
                                    La demande de paiement a bien été envoyée à votre téléphone.
                                </p>

                            </div>

                        </div>

                        {{-- Etape 2 --}}
                        <div class="flex">

                            <div class="mr-4">

                                <div id="step2"
                                    class="w-10 h-10 rounded-full bg-blue-600 animate-pulse flex items-center justify-center">

                                    <svg class="w-5 h-5 text-white animate-spin" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V3m0 18v-3m6-6h3M3 12h3" />

                                    </svg>

                                </div>

                            </div>

                            <div>

                                <h4 class="font-semibold text-gray-800">
                                    Confirmation du client
                                </h4>

                                <p class="text-sm text-gray-500 mt-1">

                                    Saisissez votre code secret Mobile Money.

                                </p>

                            </div>

                        </div>

                        {{-- Etape 3 --}}
                        <div class="flex">

                            <div class="mr-4">

                                <div id="step3"
                                    class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">

                                    <span class="font-bold text-white">
                                        3
                                    </span>

                                </div>

                            </div>

                            <div>

                                <h4 class="font-semibold text-gray-800">
                                    Validation Ali-Kamer
                                </h4>

                                <p class="text-sm text-gray-500 mt-1">

                                    Nous attendons la confirmation de Campay.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="h-6"></div>

                {{-- ===========================
                Conseils
            ============================ --}}

                <div class="bg-blue-50 border border-blue-100 rounded-3xl p-6">

                    <h3 class="font-bold text-blue-900 mb-4">

                        Conseils

                    </h3>

                    <ul class="space-y-3 text-sm text-blue-900">

                        <li>
                            • Ne quittez pas cette page avant la fin du paiement.
                        </li>

                        <li>
                            • Vérifiez votre téléphone Mobile Money.
                        </li>

                        <li>
                            • Ne communiquez jamais votre code secret.
                        </li>

                        <li>
                            • Si aucune notification n'arrive, vous pourrez relancer le paiement.
                        </li>

                    </ul>

                </div>

                <div class="h-6"></div>

                {{-- ===========================
                Etat dynamique
            ============================ --}}

                <div id="successBox" class="hidden rounded-2xl border border-green-300 bg-green-50 p-5">

                    <h3 class="font-bold text-green-700 text-lg">

                        Paiement confirmé

                    </h3>

                    <p class="text-green-700 mt-2">

                        Votre paiement a été reçu avec succès.
                        Vous allez être redirigé automatiquement...

                    </p>

                </div>

                <div id="failedBox" class="hidden rounded-2xl border border-red-300 bg-red-50 p-5">

                    <h3 class="font-bold text-red-700 text-lg">

                        Paiement échoué

                    </h3>

                    <p class="text-red-700 mt-2">

                        Le paiement n'a pas pu être validé.
                        Vous pourrez recommencer la procédure.

                    </p>

                </div>

                <div class="h-6"></div>

                {{-- ===========================
                Actions
            ============================ --}}

                <div class="space-y-4">

                    <button id="refreshButton"
                        class="w-full bg-blue-600 hover:bg-blue-700 transition text-white rounded-xl py-4 font-semibold">

                        Actualiser maintenant

                    </button>

                    <a href="{{ route('buyer.orders.index') }}"
                        class="block text-center border border-gray-300 rounded-xl py-4 font-semibold hover:bg-gray-50 transition">

                        Retour à mes commandes

                    </a>

                </div>

            </div>

        </div>
        {{-- ===========================
    Scripts
=========================== --}}
    @endsection

    @push('scripts')
        <script>
            /*
                    |--------------------------------------------------------------------------
                    | Configuration
                    |--------------------------------------------------------------------------
                    */

            // Route AJAX qui vérifie le statut de la commande
            const statusUrl = "{{ route('buyer.orders.status', $order) }}";

            // Lorsque le paiement est validé on redirige ici.
            // (à modifier si tu ajoutes une page détail commande)
            const successRedirect =
                "{{ route('buyer.orders.index') }}";

            /*
            |--------------------------------------------------------------------------
            | Eléments HTML
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

            const circumference = 351;

            function updateTimer() {
                if (remaining <= 0) {
                    return;
                }

                remaining--;

                const minutes = Math.floor(remaining / 60);

                const seconds = remaining % 60;

                timer.innerHTML =
                    `${minutes}:${seconds.toString().padStart(2,'0')}`;

                const progress =
                    circumference -
                    (remaining / 600) * circumference;

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

                    /*
                    ---------------------------------------------------------------
                    Paiement confirmé
                    ---------------------------------------------------------------
                    */

                    case "paid":

                        badge.innerHTML = "Payé";

                        badge.className =
                            "px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold";

                        successBox.classList.remove("hidden");

                        step2.className =
                            "w-10 h-10 rounded-full bg-green-500 flex items-center justify-center";

                        step2.innerHTML =
                            `<svg class="w-5 h-5 text-white"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>`;

                        step3.className =
                            "w-10 h-10 rounded-full bg-green-500 flex items-center justify-center";

                        step3.innerHTML =
                            `<svg class="w-5 h-5 text-white"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>`;

                        clearInterval(polling);

                        setTimeout(function() {

                            window.location.href = successRedirect;

                        }, 3000);

                        break;

                        /*
                        ---------------------------------------------------------------
                        Paiement échoué
                        ---------------------------------------------------------------
                        */

                    case "failed":

                        badge.innerHTML = "Echec";

                        badge.className =
                            "px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold";

                        failedBox.classList.remove("hidden");

                        clearInterval(polling);

                        break;

                        /*
                        ---------------------------------------------------------------
                        Paiement toujours en attente
                        ---------------------------------------------------------------
                        */

                    default:

                        break;

                }

            }

            /*
            |--------------------------------------------------------------------------
            | Bouton actualiser
            |--------------------------------------------------------------------------
            */

            refreshButton.addEventListener("click", function() {

                checkStatus();

            });

            /*
            |--------------------------------------------------------------------------
            | Polling automatique
            |--------------------------------------------------------------------------
            */

            checkStatus();

            const polling = setInterval(function() {

                checkStatus();

            }, 5000);
        </script>
    @endpush
