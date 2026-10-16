@extends('layouts.buyer')
@section('title', 'Modifier mon profil')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ── TITRE ───────────────────────────────────────────────────── --}}
        <div class="flex items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#016837]">
                    <span class="inline-block h-2 w-2 rounded-full bg-[#016837]"></span>
                    Espace Acheteur
                </div>
                <h1 class="mt-1 text-2xl sm:text-3xl font-black text-slate-900">Mes informations</h1>
                <p class="mt-1 text-xs text-slate-500">
                    Modifiez les informations autorisées de votre compte acheteur.
                </p>
            </div>
            <a href="{{ route('buyer.profile') }}"
               class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition hover:bg-slate-50">
                ← Retour
            </a>
        </div>

        {{-- ── 1. INFORMATIONS PERSONNELLES ────────────────────────────── --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
            <h2 class="text-sm font-black uppercase tracking-tight text-slate-900 mb-1">Informations personnelles</h2>
            <p class="text-xs text-slate-400 mb-6">
                La modification de votre email ou de votre téléphone nécessite une nouvelle vérification.
            </p>

            <form method="POST" action="{{ route('buyer.profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Nom --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nom complet</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800
                                  focus:border-[#016837] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#016837]/20">
                    @error('name')<p class="mt-1 text-[11px] font-bold text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Adresse email
                        @if($user->email_verified_at)
                            <span class="ml-1 text-[10px] font-bold text-[#016837]">✓ vérifiée</span>
                        @else
                            <span class="ml-1 text-[10px] font-bold text-orange-600">⚠ non vérifiée</span>
                        @endif
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800
                                  focus:border-[#016837] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#016837]/20">
                    @error('email')<p class="mt-1 text-[11px] font-bold text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-[11px] text-slate-400">Toute modification annule la vérification et envoie un nouvel email.</p>
                </div>

                {{-- Téléphone --}}
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Téléphone
                        @if($user->phone_verified_at)
                            <span class="ml-1 text-[10px] font-bold text-[#016837]">✓ vérifié</span>
                        @else
                            <span class="ml-1 text-[10px] font-bold text-slate-400">non vérifié</span>
                        @endif
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                           inputmode="numeric" placeholder="6XXXXXXXX"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800
                                  focus:border-[#016837] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#016837]/20">
                    @error('phone')<p class="mt-1 text-[11px] font-bold text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-[11px] text-slate-400">Format : 9 chiffres commençant par 6 (ex. 655123456).</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('buyer.profile') }}"
                       class="rounded-2xl px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-100">
                        Annuler
                    </a>
                    <button type="submit"
                            class="rounded-2xl bg-[#016837] px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#015c30]">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

        {{-- ── 2. SÉCURITÉ DU COMPTE ───────────────────────────────────── --}}
        <div id="securite" class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs scroll-mt-24">
            <h2 class="text-sm font-black uppercase tracking-tight text-slate-900 mb-1">Sécurité du compte</h2>
            <p class="text-xs text-slate-400 mb-6">Modifiez votre mot de passe. Vous devrez saisir votre mot de passe actuel.</p>

            <form method="POST" action="{{ route('buyer.profile.password') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" required autocomplete="current-password"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800
                                  focus:border-[#016837] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#016837]/20">
                    @error('current_password')<p class="mt-1 text-[11px] font-bold text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password" required autocomplete="new-password"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800
                                  focus:border-[#016837] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#016837]/20">
                    @error('password')<p class="mt-1 text-[11px] font-bold text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-[11px] text-slate-400">Au moins 8 caractères.</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800
                                  focus:border-[#016837] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#016837]/20">
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="submit"
                            class="rounded-2xl bg-[#016837] px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#015c30]">
                        Mettre à jour le mot de passe
                    </button>
                </div>
            </form>
        </div>

        {{-- ── 3. INFORMATIONS NON MODIFIABLES ─────────────────────────── --}}
        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-xs">
            <h2 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Informations gérées par Ali-Kamer</h2>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                Votre <strong>TrustScore</strong>, votre <strong>niveau de risque</strong>, votre <strong>statut de sanction</strong>
                et vos données <strong>KYC</strong> sont calculés et gérés par la plateforme. Ils ne peuvent pas être modifiés
                manuellement depuis votre espace. Pour toute contestation, contactez le support.
            </p>
        </div>

    </div>
</div>
@endsection
