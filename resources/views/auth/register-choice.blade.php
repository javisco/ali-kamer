<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire — Ali-Kamer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800">

<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-slate-50">
<div class="w-full max-w-2xl">

    {{-- Logo & En-tête --}}
    <div class="text-center mb-10">
        <a href="/" class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-md mb-4 transition hover:scale-105">
            <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer" class="h-12 w-auto object-contain">
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900">Rejoindre Ali-Kamer</h1>
        <p class="text-slate-500 mt-2">Achetez et vendez sans stress</p>
    </div>

    {{-- Choix Acheteur / Vendeur --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">

        {{-- Compte Acheteur --}}
        <a href="{{ route('register.buyer') }}"
           class="group bg-white rounded-3xl border-2 border-slate-100 shadow-sm
                  hover:border-primary-600 hover:shadow-xl transition-all p-8 text-center block">
            <div class="w-20 h-20 bg-success-50 rounded-2xl flex items-center justify-center
                        mx-auto mb-5 group-hover:bg-primary-600/10 transition-colors text-4xl">
                🛒
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 mb-2">Je veux acheter</h2>
            <p class="text-sm text-slate-500 leading-relaxed">
                Parcourez des milliers de produits de vendeurs camerounais
                et recevez-les où vous voulez.
            </p>
            <div class="mt-6 inline-block bg-primary-600 text-white font-bold
                        px-6 py-2.5 rounded-xl text-sm group-hover:bg-primary-800 shadow-md shadow-primary-600/20 transition">
                Créer un compte acheteur
            </div>
        </a>

        {{-- Compte Vendeur --}}
        <a href="{{ route('register.seller') }}"
           class="group bg-white rounded-3xl border-2 border-slate-100 shadow-sm
                  hover:border-warning hover:shadow-xl transition-all p-8 text-center block">
            <div class="w-20 h-20 bg-warning-50 rounded-2xl flex items-center justify-center
                        mx-auto mb-5 group-hover:bg-warning/20 transition-colors text-4xl">
                🏪
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 mb-2">Je veux vendre</h2>
            <p class="text-sm text-slate-500 leading-relaxed">
                Ouvrez votre boutique, publiez vos produits et touchez
                des acheteurs dans tout le Cameroun.
            </p>
            <div class="mt-6 inline-block bg-slate-900 text-white font-bold
                        px-6 py-2.5 rounded-xl text-sm group-hover:bg-primary-600 transition">
                Créer un compte vendeur
            </div>
        </a>

    </div>

    <!-- Redirection connexion -->
    <p class="text-center text-sm text-slate-500">
        Déjà un compte ?
        <a href="{{ route('login.show') }}"
           class="text-primary-600 font-semibold hover:text-primary-800 hover:underline">Se connecter</a>
    </p>

</div>
</div>

</body>
</html>