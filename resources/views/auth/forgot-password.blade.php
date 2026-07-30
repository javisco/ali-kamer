@extends('base')

@section('title', 'Mot de passe oublié')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <div class="text-center mb-8">

            <div class="text-6xl mb-3">🔐</div>

            <h1 class="text-3xl font-bold text-gray-800">
                Mot de passe oublié
            </h1>

            <p class="mt-2 text-gray-500">
                Entrez votre adresse e-mail pour recevoir un lien de réinitialisation.
            </p>

        </div>

        @if(session('status'))
            <div class="mb-6 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">

            @csrf

            <div>

                <label for="email" class="block mb-2 font-semibold text-gray-700">
                    Adresse e-mail
                </label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    placeholder="exemple@email.com"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-blue-600 py-3 text-lg font-semibold text-white transition hover:bg-blue-700">

                Envoyer le lien de réinitialisation

            </button>

        </form>

        <div class="mt-6 text-center">

            <a href="{{ route('login.show') }}"
                class="text-blue-600 font-semibold hover:underline">

                Retour à la connexion

            </a>

        </div>

    </div>

</div>

@endsection