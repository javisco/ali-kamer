<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte vendeur — Ali-Kamer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800">

<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-slate-50/50">
<div class="w-full max-w-2xl">

    {{-- En-tête principal avec marque & information de l'activité --}}
    <div class="mb-8 rounded-3xl bg-gradient-to-r from-blue-900 via-blue-700 to-blue-600 p-6 text-center text-white shadow-xl">
        <h1 class="text-3xl font-extrabold tracking-wide">Ali-Kamer</h1>
        <div class="mt-2 inline-block rounded-full bg-white/15 px-4 py-1 backdrop-blur-md">
            <span class="text-sm font-semibold text-blue-50">Création de compte vendeur</span>
        </div>
        <p class="mt-3 text-xs text-blue-100/80">
            Votre boutique sera activée après validation de votre identité (KYC)
        </p>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register.seller.post') }}" class="space-y-5">
        @csrf

        {{-- ── Infos personnelles ────────────────────────────────── --}}
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">

            <div class="bg-slate-50 border-b border-slate-100 px-6 py-4">
                <h2 class="font-bold text-slate-800">Vos informations personnelles</h2>
            </div>

            <div class="p-6 space-y-4">

                {{-- Nom --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nom complet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Jean Dupont"
                           class="w-full rounded-2xl border px-4 py-3.5 text-sm transition bg-slate-50/50
                                  @error('name') border-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror
                                  focus:outline-none">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- phone_momo OBLIGATOIRE --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Numéro Mobile Money <span class="text-red-500">*</span>
                        <span class="text-blue-600 font-normal normal-case">(pour recevoir vos paiements)</span>
                    </label>

                    {{-- Choix opérateur --}}
                    <div class="flex gap-3 mb-2">
                        <label class="flex-1 flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer
                                      {{ old('momo_operator') === 'mtn' ? 'border-yellow-400 bg-yellow-50' : 'border-slate-200 hover:border-blue-200' }}">
                            <input type="radio" name="momo_operator" value="mtn"
                                   {{ old('momo_operator') === 'mtn' ? 'checked' : '' }} required>
                            <span class="text-sm font-semibold text-yellow-700">MTN MoMo</span>
                        </label>
                        <label class="flex-1 flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer
                                      {{ old('momo_operator') === 'orange' ? 'border-orange-400 bg-orange-50' : 'border-slate-200 hover:border-blue-200' }}">
                            <input type="radio" name="momo_operator" value="orange"
                                   {{ old('momo_operator') === 'orange' ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-orange-600">Orange Money</span>
                        </label>
                    </div>
                    @error('momo_operator')<p class="text-xs text-red-600 mb-1">{{ $message }}</p>@enderror

                    <div class="flex rounded-2xl border overflow-hidden
                                @error('phone_momo') border-red-500 @else border-slate-200 @enderror
                                focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-100 transition">
                        <div class="px-3 flex items-center bg-slate-100 text-slate-600 text-xs font-semibold border-r border-slate-200">
                            🇨🇲 +237
                        </div>
                        <input type="tel" name="phone_momo" value="{{ old('phone_momo') }}" required
                               placeholder="6 XX XX XX XX"
                               class="flex-1 bg-transparent px-4 py-3.5 text-sm focus:outline-none">
                    </div>
                    @error('phone_momo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-slate-400">
                        Le nom enregistré sur cette puce doit correspondre à votre CNI.
                    </p>
                </div>

                {{-- phone OPTIONNEL --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Téléphone de contact
                        <span class="text-slate-400 font-normal normal-case">(optionnel)</span>
                    </label>
                    <div class="flex rounded-2xl border overflow-hidden
                                @error('phone') border-red-500 @else border-slate-200 @enderror
                                focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-100 transition">
                        <div class="px-3 flex items-center bg-slate-100 text-slate-600 text-xs font-semibold border-r border-slate-200">
                            🇨🇲 +237
                        </div>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               placeholder="6 XX XX XX XX"
                               class="flex-1 bg-transparent px-4 py-3.5 text-sm focus:outline-none">
                    </div>
                    @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="vous@exemple.cm"
                           class="w-full rounded-2xl border px-4 py-3.5 text-sm transition bg-slate-50/50
                                  @error('email') border-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror
                                  focus:outline-none">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Mot de passe --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required placeholder="Min 8 caractères"
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3.5 text-sm transition bg-slate-50/50 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none
                                      @error('password') border-red-500 @endif">
                        @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Confirmer <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required placeholder="Répéter"
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3.5 text-sm transition bg-slate-50/50 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none">
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Infos boutique ────────────────────────────────────── --}}
        <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">

            <div class="bg-blue-50/60 border-b border-blue-100 px-6 py-4">
                <h2 class="font-bold text-blue-950">Votre boutique</h2>
                <p class="text-xs text-blue-600/80 mt-0.5">
                    Créée automatiquement — activée après validation KYC
                </p>
            </div>

            <div class="p-6 space-y-4">

                {{-- Nom boutique --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nom de la boutique <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="shop_name" value="{{ old('shop_name') }}" required
                           placeholder="Ex: Tech Shop Douala"
                           class="w-full rounded-2xl border px-4 py-3.5 text-sm transition bg-slate-50/50
                                  @error('shop_name') border-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror
                                  focus:outline-none">
                    @error('shop_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Ville + Catégorie --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Ville <span class="text-red-500">*</span>
                        </label>
                        <select name="city" required
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3.5 text-sm bg-slate-50/50 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none
                                       @error('city') border-red-500 @endif">
                            <option value="">-- Choisir --</option>
                            @foreach(['Douala','Yaoundé','Bafoussam','Bamenda','Buea','Limbé',
                                      'Garoua','Maroua','Ngaoundéré','Bertoua','Ebolowa','Kribi'] as $city)
                                <option value="{{ $city }}"
                                        {{ old('city') === $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                        @error('city')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="category" required
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3.5 text-sm bg-slate-50/50 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none
                                       @error('category') border-red-500 @endif">
                            <option value="">-- Choisir --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}"
                                        {{ old('category') === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Description
                        <span class="text-slate-400 font-normal normal-case">(optionnel)</span>
                    </label>
                    <textarea name="description" rows="3" maxlength="500"
                              class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm bg-slate-50/50 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none resize-none"
                              placeholder="Décrivez votre boutique en quelques mots...">{{ old('description') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Note KYC --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50/50 p-4 text-sm text-blue-900">
            <p class="font-bold mb-1 flex items-center gap-1.5 text-blue-800">📋 Prochaine étape après l'inscription</p>
            <p class="text-xs leading-relaxed text-blue-700/90">
                Vous devrez soumettre un dossier KYC (CNI + selfie + numéro MoMo).
                Votre boutique sera activée après validation par notre équipe sous 24 à 48h.
            </p>
        </div>

        <button type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-blue-700 to-blue-600 py-3.5 font-bold text-white shadow-lg shadow-blue-200 transition hover:from-blue-800 hover:to-blue-700 active:scale-[0.99]">
            Créer mon compte vendeur
        </button>

        <p class="text-center text-xs text-slate-500 pt-2">
            Déjà un compte ?
            <a href="{{ route('login.show') }}" class="font-bold text-blue-600 hover:underline">
                Se connecter
            </a>
        </p>

        {{-- Bouton de retour placé en bas de la page --}}
        <div class="text-center pt-4 border-t border-slate-200/60 mt-6">
            <a href="{{ route('register.show') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition">
                ← Retour au choix des comptes
            </a>
        </div>

    </form>

</div>
</div>

</body>
</html>