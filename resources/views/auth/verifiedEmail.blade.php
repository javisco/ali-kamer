<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vérification de l'e-mail — {{ config('app.name', 'Ali-Kamer') }}</title>

    {{-- Importation CSS/JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50/50">

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-lg">

            <!-- Logo Ali-Kamer au-dessus de la carte -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center gap-3 p-3 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-center p-1.5 rounded-xl bg-slate-50">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-14 w-auto object-contain">
                    </div>
                </a>
            </div>

            <!-- Carte Principale -->
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-100">

                <!-- En-tête avec gradient Ali-Kamer -->
                <div class="bg-gradient-to-br from-[#004d28] via-[#006837] to-[#046A38] text-white px-8 py-8 relative overflow-hidden text-center">
                    <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-28 h-28 rounded-full bg-[#FFC20E]/20 blur-lg"></div>

                    <div class="relative z-10 flex flex-col items-center">
                        <!-- Badge / Icône Enveloppe -->
                        <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shadow-lg mb-4 text-3xl">
                            📩
                        </div>
                        
                        <h1 class="text-2xl sm:text-3xl font-black">Vérifiez votre e-mail</h1>
                        <p class="mt-2 text-emerald-100/90 text-xs sm:text-sm max-w-xs leading-relaxed">
                            Une dernière étape avant d'accéder à l'ensemble de votre compte ALI-KAMER.
                        </p>
                    </div>
                </div>

                <!-- Corps de la carte -->
                <div class="p-6 sm:p-8 space-y-6">

                    <!-- Message explicatif -->
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed text-center">
                        Nous avons envoyé un lien de confirmation à votre adresse e-mail. Veuillez cliquer dessus pour activer votre compte.
                    </p>

                    <!-- Alerte Succès -->
                    @if(session('message'))
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs sm:text-sm text-emerald-700 font-medium flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ session('message') }}</span>
                        </div>
                    @endif

                    <!-- Boîte Conseils / Assistance -->
                    <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-5">
                        <h2 class="font-bold text-amber-900 text-xs flex items-center gap-2 mb-2">
                            <span>💡</span> Vous ne trouvez pas l'e-mail ?
                        </h2>
                        <ul class="list-disc pl-5 space-y-1 text-[11px] sm:text-xs text-amber-900/80 leading-relaxed">
                            <li>Patientez quelques minutes (certains fournisseurs prennent du temps).</li>
                            <li>Vérifiez votre dossier <strong>Spam</strong> ou <strong>Courrier indésirable</strong>.</li>
                            <li>Assurez-vous que l'adresse e-mail renseignée lors de l'inscription est exacte.</li>
                        </ul>
                    </div>

                    <!-- Action : Renvoyer l'e-mail -->
                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-2xl bg-[#006837] hover:bg-[#004d28] py-3.5 text-sm font-bold text-white shadow-lg shadow-[#006837]/20 transition active:scale-[0.99] flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Renvoyer l'e-mail de vérification</span>
                        </button>
                    </form>

                </div>

                <!-- Footer carte / Retour à la connexion -->
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 text-center">
                    <a href="{{ route('login.show') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#006837] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Retour à la connexion</span>
                    </a>
                </div>

            </div>

            <!-- Copyright -->
            <p class="text-center text-[11px] text-slate-400 mt-6">
                &copy; {{ date('Y') }} {{ config('app.name', 'Ali-Kamer') }}. Tous droits réservés.
            </p>

        </div>
    </div>

</body>
</html>