<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="flex justify-between items-center bg-white w-full h-14 shadow-2xl p-6 fixed top-0">
        <p class="text-blue-600 text-xl">ali-kamer</p>
        <ul class="flex gap-4">
            <li><a href="{{ route('index') }}" class="rounded px-8 py-1 text-blue-600">acceuil</a></li>


            <li><a href="{{ route('login.show') }}" class="rounded px-8 py-1 text-blue-600">dashboard</a></li>


        </ul>
        <ol class="flex gap-4">
            @guest
                <li><a href="{{ route('register.show') }}" class=" rounded  px-4 py-1 text-blue-600">
                        s'inscrire</a>
                </li>
                <li> <a href="{{ route('login.show') }}" class=" rounded  px-4 py-1 text-blue-600">se connecter</a> </li>
            @endguest
            @auth
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <BUtton class=" rounded  px-6 py-1 bg-red-500">Deconnexion</BUtton>
                    </form>
                </li>
            @endauth

        </ol>
    </div>
    @yield('content')
</body>

</html>
