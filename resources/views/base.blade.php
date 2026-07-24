<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="">
    <!-- Navigation responsive -->
    <nav class="bg-white w-full shadow-md fixed top-0 left-0 z-50 px-4 py-3">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-3">
            
            <!-- Logo -->
            <a href="{{ route('buyer.home') }}" class="text-blue-600 text-xl md:text-2xl font-bold tracking-wide">
                ALI-KAMER
            </a>

            <!-- Liens principaux -->
            <ul class="flex items-center gap-2 md:gap-6 text-sm md:text-base">
                <li>
                    <a href="{{ route('buyer.home') }}" class="hover:text-blue-700 px-2 py-1 transition-colors">
                        Accueil
                    </a>
                </li>
                <li>
                    <a href="{{ route('login.show') }}" class="hover:text-blue-700 px-2 py-1 transition-colors">
                        Dashboard
                    </a>
                </li>
            </ul>

            <!-- Zone Connexion / Inscription / Déconnexion -->
            <div class="flex items-center gap-2 text-sm md:text-base">
                @guest
                    <a href="{{ route('register.show') }}" class="hover:text-blue-700 px-3 py-1">
                        S'inscrire
                    </a>
                    <a href="{{ route('login.show') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded px-3 py-1 transition-colors">
                        Se connecter
                    </a>
                @endguest

                @auth
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="rounded px-4 py-1 bg-red-500 hover:bg-red-600 text-white font-medium transition-colors">
                            Déconnexion
                        </button>
                    </form>
                @endauth
            </div>

        </div>
    </nav>

    <!-- Espace sous la navbar fixe -->
    <div class="pt-28 sm:pt-20"></div>

    @yield('content')
</body>

</html>