@extends('base')

@section('title', 'Interface Secrétaire')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-4xl mx-auto px-4">

    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Interface Secrétaire</h1>
        @if($counter)
            {{-- Afficher le guichet principal du secrétaire --}}
            <p class="text-sm text-gray-500 mt-1">
                {{ $counter->full_name }}
            </p>
        @else
            {{-- Alerte si aucun guichet assigné --}}
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mt-3 text-sm">
                ⚠ Aucun guichet assigné à votre compte. Contactez l'administrateur.
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- ── SECTION 1 : Enregistrer un dépôt ─────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-gray-800 mb-1">📦 Enregistrer un dépôt</h2>
        <p class="text-sm text-gray-500 mb-4">
            Le vendeur vous donne un code. Saisissez-le pour enregistrer le colis.
        </p>

        <form method="POST" action="{{ route('secretary.deposit') }}" class="flex gap-3">
            @csrf
            <input type="text" name="deposit_code"
                   placeholder="Code 8 caractères"
                   maxlength="8"
                   class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-sm
                          uppercase tracking-widest font-mono focus:ring-2 focus:ring-indigo-500"
                   autofocus>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                           px-6 py-3 rounded-xl transition text-sm">
                Valider
            </button>
        </form>
        @error('deposit_code')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- ── SECTION 2 : Colis à valider à l'arrivée ──────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-gray-800 mb-4">
            🚚 Colis à valider à l'arrivée
            @if($pendingArrival->count())
                <span class="ml-2 bg-orange-100 text-orange-600 text-xs font-bold
                             px-2 py-0.5 rounded-full">
                    {{ $pendingArrival->count() }}
                </span>
            @endif
        </h2>

        @if($pendingArrival->isEmpty())
            <p class="text-gray-400 text-sm">Aucun colis en attente de validation.</p>
        @else
            <div class="space-y-4">
                @foreach($pendingArrival as $order)
                    <div class="border border-gray-100 rounded-xl p-4">

                        {{-- Infos commande --}}
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">
                                    {{ $order->reference }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Vendeur : {{ $order->shop->name }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    Destinataire : {{ $order->shipment->recipient_name }}
                                    — {{ $order->shipment->recipient_phone }}
                                </p>
                            </div>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                         bg-orange-100 text-orange-600">
                                {{ $order->status === 'in_transit' ? 'En transit' : 'Enregistré' }}
                            </span>
                        </div>

                        {{-- Formulaire validation arrivée --}}
                        <form method="POST"
                              action="{{ route('secretary.arrival', $order) }}"
                              class="space-y-3">
                            @csrf

                            {{-- Frais transport si non inclus --}}
                            @if(! $order->shipment->shipping_included)
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Frais de transport (FCFA)
                                        {{-- Payés en main propre par le vendeur --}}
                                        <span class="text-gray-400 font-normal">
                                            — saisis après paiement en main propre
                                        </span>
                                    </label>
                                    <input type="number"
                                           name="transport_fee"
                                           min="0"
                                           placeholder="Ex: 2500"
                                           class="w-full border border-gray-300 rounded-xl
                                                  px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                                </div>
                            @endif

                            <button class="w-full bg-teal-600 hover:bg-teal-700 text-white
                                          font-bold py-2.5 rounded-xl transition text-sm">
                                ✓ Valider l'arrivée
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ── SECTION 3 : Remise colis avec OTP ────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-bold text-gray-800 mb-1">🔑 Remise de colis</h2>
        <p class="text-sm text-gray-500 mb-4">
            L'acheteur vous donne verbalement son code OTP. Saisissez-le pour
            confirmer la remise et déclencher le paiement du vendeur.
        </p>

        {{-- Rechercher la commande par référence puis saisir l'OTP --}}
        <div x-data="{ step: 1, order: null, reference: '' }">

            {{-- Étape 1 : Référence de la commande --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Référence de la commande
                </label>
                <div class="flex gap-3 mb-4">
                    <input type="text"
                           placeholder="ALK-2026-XXXXX"
                           class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-sm
                                  uppercase font-mono focus:ring-2 focus:ring-indigo-500"
                           id="orderRefInput">
                    <button onclick="findOrder()"
                            class="bg-gray-800 hover:bg-gray-900 text-white font-bold
                                   px-6 py-3 rounded-xl transition text-sm">
                        Rechercher
                    </button>
                </div>

                {{-- Résultat de la recherche --}}
                <div id="orderResult" class="hidden border border-indigo-100 bg-indigo-50
                                              rounded-xl p-4 mb-4">
                    <p class="text-sm font-semibold text-indigo-800" id="orderInfo"></p>
                </div>

                {{-- Formulaire OTP --}}
                <form method="POST" id="otpForm" class="hidden" action="">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Code OTP de l'acheteur (6 chiffres)
                    </label>
                    <div class="flex gap-3">
                        <input type="text" name="otp"
                               maxlength="6"
                               placeholder="_ _ _ _ _ _"
                               class="flex-1 border border-gray-300 rounded-xl px-4 py-3
                                      text-center text-xl font-mono tracking-[0.5em]
                                      focus:ring-2 focus:ring-indigo-500">
                        <button class="bg-emerald-600 hover:bg-emerald-700 text-white
                                      font-bold px-6 py-3 rounded-xl transition text-sm">
                            Remettre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</div>

@push('scripts')
<script>
    // Rechercher une commande par référence pour la remise OTP
    function findOrder() {
        const ref = document.getElementById('orderRefInput').value.trim().toUpperCase();

        if (!ref) return;

        // Appel AJAX pour trouver la commande
        fetch(`/agence/recherche-commande?ref=${ref}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.found) {
                // Afficher les infos de la commande
                document.getElementById('orderResult').classList.remove('hidden');
                document.getElementById('orderInfo').textContent =
                    `Commande ${data.reference} — ${data.buyer} — ${data.destination_city}`;

                // Préparer le formulaire OTP avec la bonne route
                const form = document.getElementById('otpForm');
                form.classList.remove('hidden');
                form.action = `/agence/otp/${data.id}`;
            } else {
                alert('Commande introuvable ou non disponible pour remise.');
            }
        })
        .catch(() => alert('Erreur réseau. Réessayez.'));
    }
</script>
@endpush
@endsection