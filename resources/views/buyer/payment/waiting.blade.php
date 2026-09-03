@extends('base')

@section('title', 'Paiement en attente')

@section('content')
    <div class="min-h-[85vh] flex flex-col justify-center items-center px-4 py-8 bg-slate-50">

        <div class="w-full max-w-4xl space-y-4">

            {{-- Fil d'Ariane compact --}}
            <nav class="flex items-center text-xs font-bold text-slate-400 uppercase tracking-wider">
                <a href="{{ route('buyer.orders.index') }}" class="hover:text-[#016837] transition-colors">
                    Mes commandes
                </a>
                <svg class="w-3 h-3 mx-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-700">Validation paiement</span>
            </nav>

            {{-- Carte Principale Centrée --}}
            <div class="bg-white rounded-3xl shadow-xs border border-slate-200 overflow-hidden">

                {{-- En-tête Institutionnel --}}
                <div class="bg-[#016837] px-6 py-5 text-white relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-32 h-32 rounded-full bg-[#F9A01B]/10 blur-xl"></div>
                    
                    <div class="flex flex-wrap items-center justify-between gap-4 relative z-10">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#F9A01B] animate-spin" style="animation-duration:2s" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V3m0 18v-3m6-6h3M3 12h3m9.364-6.364l2.121-2.121M4.515 19.485l2.121-2.121m0-10.728L4.515 4.515m14.97 14.97l-2.121-2.121" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg sm:text-xl font-black uppercase tracking-tight">Confirmation du paiement</h1>
                                <p class="text-emerald-100/80 text-xs font-medium mt-0.5">Paiement sécurisé Ali-Kamer</p>
                            </div>
                        </div>

                        <span id="paymentBadge" class="px-3.5 py-1 rounded-xl bg-[#F9A01B]/20 backdrop-blur-md text-[#F9A01B] text-xs font-black uppercase tracking-wider border border-[#F9A01B]/30">
                            En attente
                        </span>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-6">

                    {{-- Dynamic Status Messages --}}
                    <div id="successBox" class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-[#016837]">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <div>
                                <h3 class="font-black text-sm uppercase">Paiement confirmé !</h3>
                                <p class="font-medium mt-0.5">Votre paiement a été validé avec succès. Redirection en cours...</p>
                            </div>
                        </div>
                    </div>

                    <div id="failedBox" class="hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-bold text-[#E30613]">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <div>
                                <h3 class="font-black text-sm uppercase">Paiement échoué</h3>
                                <p class="font-medium mt-0.5">Le paiement n'a pas pu être validé. Veuillez réessayer.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Zone d'Action Requise + Timer --}}
                    <div class="bg-amber-50/60 border border-amber-200/80 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-[#F9A01B] text-slate-900 flex items-center justify-center shrink-0 shadow-xs font-black">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 18h.01M12 6a1 1 0 110 2 1 1 0 010-2zm0 4v4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-900 text-sm uppercase tracking-tight">Validez sur votre téléphone</h3>
                                <p class="text-xs text-slate-600 font-medium mt-0.5">Saisissez votre code secret Mobile Money pour autoriser le débit.</p>
                            </div>
                        </div>

                        {{-- Timer Mini SVG --}}
                        <div class="flex items-center gap-3 bg-white px-3.5 py-2 rounded-xl border border-amber-200/60 shadow-xs shrink-0">
                            <div class="relative w-8 h-8 flex items-center justify-center">
                                <svg class="w-8 h-8 transform -rotate-90">
                                    <circle cx="16" cy="16" r="13" stroke="#e2e8f0" stroke-width="3" fill="none" />
                                    <circle cx="16" cy="16" r="13" stroke="#016837" stroke-width="3" fill="none" stroke-linecap="round" stroke-dasharray="82" stroke-dashoffset="0" id="progressCircle" />
                                </svg>
                            </div>
                            <div class="text-right">
                                <span id="timer" class="text-sm font-black text-[#016837] block leading-none">10:00</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">restantes</span>
                            </div>
                        </div>
                    </div>

                    {{-- Progression Compacte --}}
                    <div class="border-y border-slate-100 py-4">
                        <div class="grid grid-cols-3 gap-2 text-center">
                            {{-- Étape 1 --}}
                            <div class="flex flex-col items-center">
                                <div class="w-7 h-7 rounded-xl bg-[#016837] text-white flex items-center justify-center text-xs mb-1 font-black shadow-xs">
                                    ✓
                                </div>
                                <span class="text-[11px] font-bold text-slate-800">Demande envoyée</span>
                            </div>

                            {{-- Étape 2 --}}
                            <div class="flex flex-col items-center">
                                <div id="step2" class="w-7 h-7 rounded-xl bg-[#F9A01B] animate-pulse text-slate-900 flex items-center justify-center text-xs mb-1 font-black shadow-xs">
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V3m0 18v-3m6-6h3M3 12h3" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-extrabold text-slate-900">Confirmation client</span>
                            </div>

                            {{-- Étape 3 --}}
                            <div class="flex flex-col items-center opacity-50">
                                <div id="step3" class="w-7 h-7 rounded-xl bg-slate-200 text-slate-600 font-black flex items-center justify-center text-xs mb-1">
                                    3
                                </div>
                                <span class="text-[11px] font-bold text-slate-500">Validation finalisée</span>
                            </div>
                        </div>
                    </div>

                    {{-- Détails + Résumé --}}
                    <div class="grid md:grid-cols-2 gap-4 text-xs">

                        {{-- Infos Transaction --}}
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-2.5">
                            <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider mb-2">Informations</h4>
                            <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                                <span class="text-slate-500 font-medium">Réf. Commande:</span>
                                <span class="font-bold text-slate-900 break-all">{{ $order->reference }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                                <span class="text-slate-500 font-medium">Réf. Campay:</span>
                                <span class="font-bold text-slate-900 break-all">{{ $order->payment->provider_reference ?? 'En attente...' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-200/60 pb-1.5">
                                <span class="text-slate-500 font-medium">Numéro MoMo:</span>
                                <span class="font-bold text-slate-900">{{ $order->payment->payer_phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500 font-medium">Moyen de paiement:</span>
                                <span class="font-extrabold text-[#016837] uppercase">{{ $order->payment->method }}</span>
                            </div>
                        </div>

                        {{-- Résumé Financier --}}
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-2 flex flex-col justify-between">
                            <div>
                                <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider mb-2">Montants</h4>
                                <div class="flex justify-between text-slate-600 font-medium mb-1">
                                    <span>Sous-total:</span>
                                    <span class="font-bold text-slate-800">{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="flex justify-between text-slate-600 font-medium mb-1">
                                    <span>Frais (Livraison/Gestion):</span>
                                    <span class="font-bold text-slate-800">{{ number_format($order->shipping_fee + $order->protection_fee + $order->gateway_fee, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-2.5 border-t border-slate-200 font-black text-sm bg-emerald-50/80 -mx-4 -mb-4 p-4 rounded-b-2xl border-t-emerald-100">
                                <span class="text-slate-800 uppercase text-xs tracking-wider">Total à payer:</span>
                                <span class="text-base text-[#016837]">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>

                    </div>

                    {{-- Conseil rapide --}}
                    <p class="text-[11px] text-slate-400 font-medium text-center leading-relaxed">
                        🔒 Ne quittez pas cette page et ne partagez jamais votre code secret Mobile Money.
                    </p>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button id="refreshButton" class="flex-1 bg-[#016837] hover:bg-[#01522b] active:bg-[#013d20] transition-all text-white rounded-xl py-3 px-4 font-extrabold text-xs shadow-md shadow-[#016837]/20 uppercase tracking-wider cursor-pointer">
                            Actualiser le statut
                        </button>
                        <a href="{{ route('buyer.orders.index') }}" class="flex-1 text-center border border-slate-300 rounded-xl py-3 px-4 font-extrabold text-xs text-slate-700 hover:bg-slate-100 transition-colors uppercase tracking-wider">
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
                    badge.className = "px-3.5 py-1 rounded-xl bg-emerald-500/20 text-emerald-200 text-xs font-black uppercase tracking-wider border border-emerald-400/30";

                    successBox.classList.remove("hidden");

                    step2.className = "w-7 h-7 rounded-xl bg-[#016837] text-white flex items-center justify-center text-xs mb-1 font-black shadow-xs";
                    step2.innerHTML = `✓`;

                    step3.className = "w-7 h-7 rounded-xl bg-[#016837] text-white flex items-center justify-center text-xs mb-1 font-black shadow-xs";
                    step3.innerHTML = `✓`;

                    clearInterval(polling);

                    setTimeout(function() {
                        window.location.href = successRedirect;
                    }, 3000);
                    break;

                case "failed":
                    badge.innerHTML = "Échec";
                    badge.className = "px-3.5 py-1 rounded-xl bg-[#E30613]/20 text-red-200 text-xs font-black uppercase tracking-wider border border-[#E30613]/30";

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