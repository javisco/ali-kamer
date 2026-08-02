<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'ALI-KAMER')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    <!-- Navigation responsive -->
    <nav class="bg-white w-full shadow-md fixed top-0 left-0 z-50 px-4 py-2 border-b border-gray-100">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-3">

            <!-- Logo ALI-KAMER (SVG Intégré) -->
            <a href="{{ route('buyer.home') }}"
                class="flex items-center group transition-transform duration-200 hover:scale-105">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 560 120" class="h-10 md:h-12 w-auto">

                    <defs>

                        <!-- Vert -->
                        <linearGradient id="greenGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#16A34A" />
                            <stop offset="100%" stop-color="#065F46" />
                        </linearGradient>

                        <!-- Orange -->
                        <linearGradient id="orangeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#FB923C" />
                            <stop offset="100%" stop-color="#EA580C" />
                        </linearGradient>

                    </defs>

                    <!-- ===================== -->
                    <!-- LOGO -->
                    <!-- ===================== -->

                    <g transform="translate(8,10)">

                        <!-- Cercle -->

                        <circle cx="45" cy="45" r="38" fill="none" stroke="url(#greenGrad)"
                            stroke-width="5" />

                        <!-- A -->

                        <path d="M28 78
               L45 18
               L62 78" fill="none" stroke="url(#orangeGrad)" stroke-width="7" stroke-linecap="round"
                            stroke-linejoin="round" />

                        <!-- Barre du A -->

                        <line x1="36" y1="54" x2="54" y2="54" stroke="url(#greenGrad)"
                            stroke-width="6" stroke-linecap="round" />

                        <!-- Coche -->

                        <path d="M56 67
               L64 75
               L77 56" fill="none" stroke="#16A34A" stroke-width="5" stroke-linecap="round"
                            stroke-linejoin="round" />

                    </g>

                    <!-- ===================== -->
                    <!-- TEXTE -->
                    <!-- ===================== -->

                    <text x="100" y="58" font-size="44" font-family="Poppins, Arial, sans-serif" font-weight="800"
                        fill="#F97316">

                        Ali-

                    </text>

                    <text x="172" y="58" font-size="44" font-family="Poppins, Arial, sans-serif" font-weight="800"
                        fill="#065F46">

                        Kamer

                    </text>

                    <!-- ===================== -->
                    <!-- SLOGAN -->
                    <!-- ===================== -->

                    <text x="101" y="83" font-size="12" font-family="Poppins, Arial, sans-serif" letter-spacing="1"
                        fill="#64748B">

                        ACHETEZ ET VENDEZ SANS STRESS

                    </text>

                </svg>

            </a>

            <!-- Liens principaux -->
            <ul class="flex items-center gap-2 md:gap-6 text-sm md:text-base font-medium">
                <li>
                    <a href="{{ route('buyer.home') }}"
                        class="text-gray-600 hover:text-blue-600 px-2 py-1 transition-colors">
                        Accueil
                    </a>
                </li>
                <li>
                    <a href="{{ route('login.show') }}"
                        class="text-gray-600 hover:text-blue-600 px-2 py-1 transition-colors">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('messaging.index') }}"
                        class="text-gray-600 hover:text-blue-600 px-2 py-1 transition-colors">
                        messages
                    </a>
                </li>
            </ul>

            <!-- Zone Connexion / Inscription / Déconnexion -->
            <div class="flex items-center gap-2 text-sm md:text-base">
                @guest
                    <a href="{{ route('register.show') }}"
                        class="text-gray-600 hover:text-blue-600 font-medium px-3 py-1 transition-colors">
                        S'inscrire
                    </a>
                    <a href="{{ route('login.show') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-1.5 transition-colors shadow-sm">
                        Se connecter
                    </a>
                @endguest

                @auth
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="rounded-lg px-4 py-1.5 bg-rose-500 hover:bg-rose-600 text-white font-medium transition-colors shadow-sm">
                            Déconnexion
                        </button>
                    </form>
                @endauth
            </div>

        </div>
    </nav>

    <!-- Espace sous la navbar fixe -->
    <div class="pt-32 sm:pt-24"></div>

    @yield('content')
    @stack('scripts')
</body>

</html>
