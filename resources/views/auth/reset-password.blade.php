@extends('base')

@section('title', 'Réinitialisation du mot de passe')

@section('content')

    <div class="max-w-xl mx-auto py-12">

        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

            <!-- En-tête -->
            <div class="bg-blue-600 text-white px-8 py-8">

                <h1 class="text-3xl font-bold">
                    Nouveau mot de passe
                </h1>

                <p class="mt-2 text-blue-100">
                    Choisissez un nouveau mot de passe sécurisé pour votre compte ALI-KAMER.
                </p>

            </div>

            <!-- Corps -->

            <div class="p-8">

                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-green-300 bg-green-100 px-4 py-3 text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())

                    <div class="mb-6 rounded-lg border border-red-300 bg-red-100 px-4 py-3">

                        <ul class="list-disc pl-5 text-red-700">

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

                        <label class="block text-gray-700 font-semibold mb-2">

                            Adresse e-mail

                        </label>

                        <input type="email" name="email"  value="{{ old('email', $email) }}" readonly
                            class="w-full rounded-lg border bg-gray-100 px-4 py-3 text-gray-700">

                    </div>

                    <!-- Token -->

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Nouveau mot de passe -->

                    <div class="mb-5">

                        <label class="block text-gray-700 font-semibold mb-2">

                            Nouveau mot de passe

                        </label>

                        <input type="password" name="password" placeholder="Minimum 8 caractères"
                            class="w-full rounded-lg border px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                        @error('password')
                            <p class="mt-2 text-sm text-red-500">

                                {{ $message }}

                            </p>
                        @enderror

                    </div>

                    <!-- Confirmation -->

                    <div class="mb-8">

                        <label class="block text-gray-700 font-semibold mb-2">

                            Confirmer le mot de passe

                        </label>

                        <input type="password" name="password_confirmation" placeholder="Retapez votre mot de passe"
                            class="w-full rounded-lg border px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <!-- Conseils -->

                    <div class="mb-8 rounded-xl border border-blue-200 bg-blue-50 p-5">

                        <h2 class="mb-3 font-semibold text-blue-700">

                            Conseils de sécurité

                        </h2>

                        <ul class="list-disc pl-5 space-y-2 text-sm text-gray-700">

                            <li>Utilisez au moins 8 caractères.</li>

                            <li>Mélangez lettres majuscules, minuscules et chiffres.</li>

                            <li>Ajoutez des caractères spéciaux pour renforcer votre mot de passe.</li>

                            <li>N'utilisez pas le même mot de passe sur plusieurs sites.</li>

                        </ul>

                    </div>

                    <!-- Bouton -->

                    <button type="submit"
                        class="w-full rounded-lg bg-blue-600 py-3 font-semibold text-white transition hover:bg-blue-700">

                        Modifier mon mot de passe

                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection
