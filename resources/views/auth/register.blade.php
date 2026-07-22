@extends('base')

@section('title', 'Créer un compte')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-2xl">

        @if(session('fail'))
            <div class="mb-6 rounded-lg border border-red-300 bg-red-50 px-5 py-4 text-red-700 shadow">
                {{ session('fail') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

            <!-- En-tête -->

            <div class="bg-blue-600 px-8 py-8 text-center">

                <h1 class="text-4xl font-bold text-white">
                    ALI-KAMER
                </h1>

                <p class="mt-2 text-blue-100">
                    Créez votre compte gratuitement
                </p>

            </div>

            <!-- Formulaire -->

            <div class="p-8">

                <form
                    action="{{ route('register') }}"
                    method="POST"
                    class="space-y-6">

                    @csrf

                    <!-- Nom -->

                    <div>

                        <label
                            for="name"
                            class="block mb-2 font-semibold text-gray-700">

                            Nom complet

                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Ex : Jean Dupont"
                            class="w-full rounded-xl border px-4 py-3

                            @error('name')
                                border-red-500
                            @else
                                border-gray-300
                            @enderror

                            focus:outline-none focus:ring-2 focus:ring-blue-500">

                        @error('name')

                            <p class="mt-2 text-sm text-red-600">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>

                    <!-- Email -->

                    <div>

                        <label
                            for="email"
                            class="block mb-2 font-semibold text-gray-700">

                            Adresse e-mail

                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
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

                    <!-- Téléphone -->

                    <div>

                        <label
                            for="phone"
                            class="block mb-2 font-semibold text-gray-700">

                            Numéro de téléphone

                        </label>

                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="+237 6 XX XX XX XX"
                            class="w-full rounded-xl border px-4 py-3

                            @error('phone')
                                border-red-500
                            @else
                                border-gray-300
                            @enderror

                            focus:outline-none focus:ring-2 focus:ring-blue-500">

                        @error('phone')

                            <p class="mt-2 text-sm text-red-600">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>

                    <!-- Mot de passe -->

                    <div>

                        <label
                            for="password"
                            class="block mb-2 font-semibold text-gray-700">

                            Mot de passe

                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Minimum 8 caractères"
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

                    <!-- Confirmation -->

                    <div>

                        <label
                            for="password_confirmation"
                            class="block mb-2 font-semibold text-gray-700">

                            Confirmer le mot de passe

                        </label>

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3
                            focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <!-- Choix du rôle -->

                    <div>

                        <label class="block mb-4 font-semibold text-gray-700">

                            Vous souhaitez rejoindre ALI-KAMER en tant que

                        </label>

                        <div class="grid gap-4">

                            <label class="flex items-center gap-4 rounded-xl border border-gray-300 p-4 cursor-pointer hover:border-blue-600 hover:bg-blue-50">

                                <input
                                    type="radio"
                                    name="role"
                                    value="buyer"
                                    {{ old('role') == 'buyer' ? 'checked' : '' }}>

                                <div>

                                    <p class="font-semibold">

                                        Acheteur

                                    </p>

                                    <p class="text-sm text-gray-500">

                                        Acheter des produits auprès des vendeurs.

                                    </p>

                                </div>

                            </label>

                            <label class="flex items-center gap-4 rounded-xl border border-gray-300 p-4 cursor-pointer hover:border-blue-600 hover:bg-blue-50">

                                <input
                                    type="radio"
                                    name="role"
                                    value="seller"
                                    {{ old('role') == 'seller' ? 'checked' : '' }}>

                                <div>

                                    <p class="font-semibold">

                                        Vendeur

                                    </p>

                                    <p class="text-sm text-gray-500">

                                        Ouvrir une boutique et vendre vos produits.

                                    </p>

                                </div>

                            </label>

                            <label class="flex items-center gap-4 rounded-xl border border-gray-300 p-4 cursor-pointer hover:border-blue-600 hover:bg-blue-50">

                                <input
                                    type="radio"
                                    name="role"
                                    value="secretary"
                                    {{ old('role') == 'secretary' ? 'checked' : '' }}>

                                <div>

                                    <p class="font-semibold">

                                        Secrétaire

                                    </p>

                                    <p class="text-sm text-gray-500">

                                        Gérer les tâches administratives autorisées.

                                    </p>

                                </div>

                            </label>

                        </div>

                        @error('role')

                            <p class="mt-3 text-sm text-red-600">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>

                    <!-- Bouton -->

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 py-4 text-lg font-semibold text-white transition hover:bg-blue-700">

                        Créer mon compte

                    </button>

                </form>

                <div class="mt-8 border-t pt-6 text-center">

                    <p class="text-gray-600">

                        Vous avez déjà un compte ?

                    </p>

                    <a
                        href="{{ route('login.show') }}"
                        class="mt-3 inline-block rounded-xl border border-blue-600 px-6 py-3 font-semibold text-blue-600 transition hover:bg-blue-600 hover:text-white">

                        Se connecter

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection