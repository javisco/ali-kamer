@extends('base')
@section('title', 'Payer les frais de transport')

@section('content')
    <div class="bg-slate-50 min-h-screen py-8 px-4 flex items-center justify-center">
        <div class="max-w-md w-full">

            {{-- Titre de la page --}}
            <div class="mb-6 text-center sm:text-left">
                <div class="flex items-center justify-center sm:justify-start gap-2.5 mb-1">
                    <span class="w-3 h-3 rounded-full bg-accent-500 animate-pulse"></span>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tight">Frais de transport</h1>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Commande Réf : <span class="text-primary-600">{{ $order->reference }}</span>
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-danger-50 border border-danger-200 text-danger px-5 py-3.5 rounded-2xl mb-6 text-xs font-bold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Info colis --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 mb-5">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-success-50 text-primary-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-black text-slate-900 text-sm uppercase tracking-tight">Votre colis est disponible !</h2>
                        <p class="text-[11px] font-medium text-slate-500">Prêt pour le retrait en agence</p>
                    </div>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between items-center bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <span class="text-slate-500 font-medium">Agence de retrait</span>
                        <span class="font-bold text-slate-800">
                            {{ $order->shipment->destinationCounter->full_name ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <span class="text-slate-500 font-medium">Ville destination</span>
                        <span class="font-bold text-slate-800">{{ $order->shipment->destination_city }}</span>
                    </div>
                </div>
            </div>

            {{-- Carte Montant --}}
            <div class="bg-gradient-to-br from-success-800 to-slate-900 text-white rounded-3xl p-6 mb-5 text-center shadow-lg relative overflow-hidden border border-success-800/50">
                <div class="absolute -right-10 -bottom-10 w-32 h-32 rounded-full bg-accent-500/10 blur-2xl"></div>
                
                <p class="text-xs font-bold uppercase tracking-widest text-accent-500 mb-1">Montant du transport</p>
                <p class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }}
                    <span class="text-base font-bold text-accent-500">FCFA</span>
                </p>
                <p class="text-[11px] text-success-200 font-medium mt-2 leading-relaxed">
                    Ces frais seront reversés au vendeur ayant effectué l'expédition.
                </p>
            </div>

            {{-- Formulaire de paiement --}}
            <form method="POST" action="{{ route('buyer.orders.transport.pay', $order) }}"
                class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                @csrf

                {{-- Choix de l'opérateur --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                        Opérateur Mobile Money <span class="text-danger">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2.5 border border-slate-200 rounded-2xl p-3 cursor-pointer transition hover:bg-warning-50/50 has-[:checked]:border-accent-500 has-[:checked]:bg-warning-50/80">
                            <input type="radio" name="transport_operator" value="mtn"
                                class="w-4 h-4 text-accent-500 focus:ring-accent-500"
                                {{ old('transport_operator', $order->payment->payer_operator ?? $order->orderGroup->payer_operator) === 'mtn' ? 'checked' : '' }} required>
                            <span class="font-extrabold text-slate-800 text-xs">MTN MoMo</span>
                        </label>

                        <label class="flex items-center gap-2.5 border border-slate-200 rounded-2xl p-3 cursor-pointer transition hover:bg-accent-50/50 has-[:checked]:border-accent-500 has-[:checked]:bg-accent-50/80">
                            <input type="radio" name="transport_operator" value="orange"
                                class="w-4 h-4 text-accent-600 focus:ring-accent-500"
                                {{ old('transport_operator',$order->payment->payer_operator ?? $order->orderGroup->payer_operator) === 'orange' ? 'checked' : '' }}>
                            <span class="font-extrabold text-slate-800 text-xs">Orange Money</span>
                        </label>
                    </div>
                    @error('transport_operator')
                        <p class="text-danger text-xs font-semibold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Numéro Mobile Money --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Numéro de paiement <span class="text-danger">*</span>
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3.5 border border-r-0 border-slate-300 rounded-l-xl bg-slate-100 text-slate-600 text-xs font-bold">+237</span>
                        <input type="tel" name="transport_phone"
                            value="{{ old('transport_phone', $order->payment->payer_phone ?? $order->orderGroup->payer_phone) }}" required
                            placeholder="655123456"
                            class="flex-1 border border-slate-300 focus:border-primary-600 focus:ring-1 focus:ring-primary-500 rounded-r-xl px-4 py-2.5 text-sm font-bold text-slate-900 transition outline-none">
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium mt-1">
                        Numéro pré-rempli d'après la commande, vous pouvez le modifier.
                    </p>
                    @error('transport_phone')
                        <p class="text-danger text-xs font-semibold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bouton de validation --}}
                <button type="submit"
                    class="w-full bg-accent-600 hover:bg-accent-700 active:bg-accent-700 text-white font-extrabold py-3.5 px-4 rounded-xl transition-all shadow-md shadow-accent-600/20 text-sm flex items-center justify-center gap-2 group cursor-pointer">
                    <span>Valider & Payer {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }} FCFA</span>
                    <svg class="w-4 h-4 text-accent-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>

            <p class="text-[11px] text-center text-slate-400 font-medium mt-4 leading-relaxed">
                Validez le prompt USSD sur votre téléphone pour achever le règlement. 
                Le code OTP de retrait vous sera transmis dès la validation du paiement.
            </p>

        </div>
    </div>
@endsection