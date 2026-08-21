<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Réinitialisation du mot de passe — {{ config('app.name', 'Ali-Kamer') }}</title>

    {{-- Importation CSS/JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800">

    <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-slate-50">
        <div class="w-full max-w-xl">

            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-slate-100">

                <!-- En-tête -->
                <div class="bg-gradient-to-r from-blue-900 to-blue-700 text-white px-8 py-8 relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                    <div class="relative z-10">
                        <h1 class="text-3xl font-bold">Nouveau mot de passe</h1>
                        <p class="mt-2 text-blue-100 text-sm">
                            Choisissez un nouveau mot de passe sécurisé pour votre compte ALI-KAMER.
                        </p>
                    </div>
                </div>

                <!-- Corps -->
                <div class="p-8">

                    @if (session('status'))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
                            <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="mb-5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                                Adresse e-mail
                            </label>
                            <input type="email" name="email" value="{{ old('email', $email) }}" readonly
                                class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3.5 text-sm text-slate-600 focus:outline-none">
                        </div>

                        <!-- Token -->
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Nouveau mot de passe -->
                        <div class="mb-5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                                Nouveau mot de passe
                            </label>
                            <input type="password" name="password" placeholder="Minimum 8 caractères"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition">
                            @error('password')
                                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmation -->
                        <div class="mb-8">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                                Confirmer le mot de passe
                            </label>
                            <input type="password" name="password_confirmation" placeholder="Retapez votre mot de passe"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3.5 text-sm focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition">
                        </div>

                        <!-- Conseils -->
                        <div class="mb-8 rounded-2xl border border-blue-200/60 bg-blue-50/50 p-5">
                            <h2 class="mb-3 font-semibold text-blue-900 text-sm flex items-center gap-2">
                                <span>🛡️</span> Conseils de sécurité
                            </h2>
                            <ul class="list-disc pl-5 space-y-1.5 text-xs text-slate-600">
                                <li>Utilisez au moins 8 caractères.</li>
                                <li>Mélangez lettres majuscules, minuscules et chiffres.</li>
                                <li>Ajoutez des caractères spéciaux pour renforcer votre mot de passe.</li>
                                <li>N'utilisez pas le même mot de passe sur plusieurs sites.</li>
                            </ul>
                        </div>

                        <!-- Bouton -->
                        <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-blue-700 to-blue-600 py-3.5 font-bold text-white shadow-lg shadow-blue-200 transition hover:from-blue-800 hover:to-blue-700 active:scale-[0.99]">
                            Modifier mon mot de passe
                        </button>
                    </form>

                </div>

            </div>

        </div>
    </div>

</body>
</html>