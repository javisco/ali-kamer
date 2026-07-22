@extends('base')

@section('title', 'Connexion')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        @if(session('fail'))
            <div class="mb-6 rounded-lg border border-red-300 bg-red-50 px-5 py-4 text-red-700 shadow">
                {{ session('fail') }}
            </div>
        @endif

        @if(session('register'))
            <div class="mb-6 rounded-lg border border-green-300 bg-green-50 px-5 py-4 text-green-700 shadow">
                {{ session('register') }}
            </div>
        @endif

        <div class="rounded-2xl bg-white shadow-xl border border-gray-100 p-8">

            <!-- Logo -->

            <div class="text-center mb-8">

                <h1 class="text-4xl font-bold text-blue-600">
                    ALI-KAMER
                </h1>

                <p class="mt-2 text-gray-500">
                    Connectez-vous à votre espace personnel
                </p>

            </div>

            <form
                action="{{ route('login') }}"
                method="POST"
                class="space-y-6">

                @csrf

                <!-- Email -->

                <div>

                    <label
                        for="email"
                        class="block mb-2 font-semibold text-gray-700">

                        Adresse e-mail

                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="exemple@email.com"
                        class="w-full rounded-xl border px-4 py-3
                        @error('email')
                        border-red-500
                        @else
                        border-gray-300
                        @enderror
                        focus:outline-none focus:ring-2 focus:ring-blue-500">

                    @error('email')

                        <p class="mt-2 text-sm text-red-600">

                            {{ $message }}

                        </p>

                    @enderror

                </div>

                <!-- Password -->

                <div>

                    <label
                        for="password"
                        class="block mb-2 font-semibold text-gray-700">

                        Mot de passe

                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="********"
                        class="w-full rounded-xl border px-4 py-3
                        @error('password')
                        border-red-500
                        @else
                        border-gray-300
                        @enderror
                        focus:outline-none focus:ring-2 focus:ring-blue-500">

                    @error('password')

                        <p class="mt-2 text-sm text-red-600">

                            {{ $message }}

                        </p>

                    @enderror

                </div>

                <!-- Options -->

                <div class="flex items-center justify-between">

                    <label class="flex items-center gap-2">

                        <input
                            type="checkbox"
                            name="remember"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                        <span class="text-sm text-gray-600">

                            Se souvenir de moi

                        </span>

                    </label>

                    <a
                        href="#"
                        class="text-sm text-blue-600 hover:underline">

                        Mot de passe oublié ?

                    </a>

                </div>

                <!-- Bouton -->

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 py-3 text-lg font-semibold text-white transition hover:bg-blue-700">

                    Se connecter

                </button>

            </form>

            <div class="mt-8 border-t pt-6 text-center">

                <p class="text-gray-600">

                    Vous n'avez pas encore de compte ?

                </p>

                <a
                    href="{{ route('register.show') }}"
                    class="mt-3 inline-block rounded-xl border border-blue-600 px-6 py-3 font-semibold text-blue-600 transition hover:bg-blue-600 hover:text-white">

                    Créer un compte

                </a>

            </div>

        </div>

    </div>

</div>

@endsection