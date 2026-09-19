@extends('base')
@section('title', 'En attente — Paiement transport')
@section('content')
    <div class="bg-slate-50 min-h-screen py-8 px-4 flex items-center justify-center">
        <div class="max-w-md w-full text-center">

            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl p-8 sm:p-10 mb-6 relative overflow-hidden">
                {{-- Accent décoratif arrière-plan --}}
                <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-accent-500/10 blur-2xl"></div>

                {{-- Icône animée --}}
                <div class="w-20 h-20 bg-warning-50 border border-accent-500/30 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xs">
                    <svg class="w-10 h-10 text-accent-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>

                <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tight mb-2">
                    En attente de confirmation
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 font-medium mb-6 leading-relaxed">
                    Veuillez valider le paiement USSD de 
                    <span class="font-extrabold text-primary-600">
                        {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }} FCFA
                    </span> 
                    directement sur votre téléphone.
                </p>

                {{-- Indication d'avancement --}}
                <div class="text-left space-y-3.5 mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3 text-xs">
                        <div class="w-6 h-6 rounded-xl bg-success-100 text-primary-600 font-black text-xs flex items-center justify-center shrink-0">
                            ✓
                        </div>
                        <span class="text-slate-600 font-bold">Colis disponible en agence</span>
                    </div>

                    <div class="flex items-center gap-3 text-xs">
                        <div class="w-6 h-6 rounded-xl bg-accent-500 text-slate-900 font-black text-xs flex items-center justify-center shrink-0 animate-pulse shadow-xs">
                            2
                        </div>
                        <span class="text-slate-900 font-black">
                            Confirmation du paiement transport (MoMo / OM)
                        </span>
                    </div>

                    <div class="flex items-center gap-3 text-xs opacity-60">
                        <div class="w-6 h-6 rounded-xl bg-slate-200 text-slate-500 font-black text-xs flex items-center justify-center shrink-0">
                            3
                        </div>
                        <span class="text-slate-500 font-bold">Réception du code OTP de retrait</span>
                    </div>
                </div>

                {{-- Status dynamique --}}
                <div class="bg-success-50/60 border border-success-100 rounded-xl p-3.5 text-xs font-bold text-primary-600 flex items-center justify-center gap-2" id="statusArea">
                    <span class="w-2 h-2 rounded-full bg-primary-600 animate-ping"></span>
                    <span>Vérification de la transaction en cours...</span>
                </div>
            </div>

            {{-- Lien retour --}}
            <a href="{{ route('buyer.orders.show', $order) }}" 
               class="inline-flex items-center gap-1.5 text-xs font-extrabold text-slate-600 hover:text-primary-600 transition-colors">
                <span>← Revenir au détail de la commande</span>
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
                            statusArea.innerHTML = '✅ Paiement confirmé ! Redirection en cours...';
                            statusArea.className = 'bg-success-100 border border-success-200 rounded-xl p-3.5 text-xs font-black text-primary-600';
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