@extends('base')

@section('title', 'Créer un compte')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-2xl">

        @if(session('fail'))
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-base text-red-700 shadow-sm">
                {{ session('fail') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

            <!-- En-tête -->
            <div class="bg-blue-600 px-6 py-6 text-center">
                <h1 class="text-3xl font-bold tracking-tight text-white">ALI-KAMER</h1>
                <p class="text-sm text-blue-100 mt-1">Créez votre compte gratuitement</p>
            </div>

            <!-- Formulaire -->
            <div class="p-6 sm:p-8">
                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Ligne 1 : Nom & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="name" class="block mb-2 font-medium text-gray-700">Nom complet</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Ex : Jean Dupont"
                                class="w-full rounded-xl border px-4 py-3 text-base @error('name') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block mb-2 font-medium text-gray-700">Adresse e-mail</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="exemple@email.com"
                                class="w-full rounded-xl border px-4 py-3 text-base @error('email') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="phone" class="block mb-2 font-medium text-gray-700">Numéro de téléphone</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+237 6 XX XX XX XX"
                            class="w-full rounded-xl border px-4 py-3 text-base @error('phone') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Ligne 2 : Mot de passe & Confirmation -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="password" class="block mb-2 font-medium text-gray-700">Mot de passe</label>
                            <input id="password" type="password" name="password" placeholder="Min. 8 caractères"
                                class="w-full rounded-xl border px-4 py-3 text-base @error('password') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block mb-2 font-medium text-gray-700">Confirmation</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirmer le mot de passe"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-base focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Choix du rôle (Grille aérée 2-1) -->
                    <div>
                        <label class="block mb-3 font-medium text-gray-700">Vous souhaitez rejoindre ALI-KAMER en tant que :</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            
                            <!-- Acheteur -->
                            <label class="flex items-start gap-3 p-3.5 border rounded-xl cursor-pointer transition hover:border-blue-500 hover:bg-blue-50/40 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-600">
                                <input type="radio" name="role" value="buyer" class="mt-1 text-blue-600 focus:ring-blue-500" {{ old('role', 'buyer') == 'buyer' ? 'checked' : '' }}>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base">Acheteur</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Acheter des produits</p>
                                </div>
                            </label>

                            <!-- Vendeur -->
                            <label class="flex items-start gap-3 p-3.5 border rounded-xl cursor-pointer transition hover:border-blue-500 hover:bg-blue-50/40 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-600">
                                <input type="radio" name="role" value="seller" class="mt-1 text-blue-600 focus:ring-blue-500" {{ old('role') == 'seller' ? 'checked' : '' }}>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base">Vendeur</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Ouvrir une boutique</p>
                                </div>
                            </label>

                            <!-- Secrétaire -->
                            <label class="flex items-start gap-3 p-3.5 border rounded-xl cursor-pointer transition hover:border-blue-500 hover:bg-blue-50/40 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-600">
                                <input type="radio" name="role" value="secretary" class="mt-1 text-blue-600 focus:ring-blue-500" {{ old('role') == 'secretary' ? 'checked' : '' }}>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base">Secrétaire</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Gestion d'agence</p>
                                </div>
                            </label>

                        </div>
                        @error('role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Bouton Submit -->
                    <button type="submit" class="w-full rounded-xl bg-blue-600 py-3.5 text-base font-semibold text-white transition hover:bg-blue-700 shadow-md mt-2">
                        Créer mon compte
                    </button>
                </form>

                <!-- Link Se connecter -->
                <div class="mt-6 border-t pt-5 text-center text-base text-gray-600 flex items-center justify-center gap-2">
                    <span>Vous avez déjà un compte ?</span>
                    <a href="{{ route('login.show') }}" class="font-semibold text-blue-600 hover:underline">
                        Se connecter
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection