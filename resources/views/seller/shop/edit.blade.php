@extends('base')
@section('title','edit')
@section('content')
    <div class="max-w-2xl mx-auto py-6 px-4">
        <!-- Bloc En-tête ALI-KAMER avec titre agrandi (text-2xl) -->
        <div class="bg-blue-600 rounded-t-3xl px-6 py-6 text-center shadow-lg">
            <h1 class="text-2xl font-black text-white tracking-wider uppercase">ALI-KAMER</h1>
            <!-- Augmentation de text-xxs à text-sm -->
            <p class="text-blue-100 text-sm font-semibold mt-1">Modifier ma boutique</p>
        </div>

        <!-- Corps du formulaire -->
        <div class="bg-white rounded-b-3xl p-6 sm:p-8 shadow-xl border-x border-b border-gray-100/50">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4 shadow-sm text-base font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 shadow-sm">
                    <ul class="list-disc list-inside space-y-0.5 text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('seller.shop.update') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- LIGNE 1 : Nom de la boutique + Ville -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <!-- Augmentation des labels de text-xs à text-sm -->
                        <label class="block text-sm font-bold uppercase tracking-wide text-gray-700 mb-1.5">Nom de la boutique</label>
                        <!-- Augmentation du texte de saisie de text-sm à text-base -->
                        <input type="text" name="name" value="{{ old('name', $shop->name) }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-base font-medium transition duration-200 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-wide text-gray-700 mb-1.5">Ville</label>
                        <input type="text" name="city" value="{{ old('city', $shop->city) }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-base font-medium transition duration-200 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 shadow-sm">
                    </div>
                </div>

                <!-- LIGNE 2 : Téléphone + Adresse -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold uppercase tracking-wide text-gray-700 mb-1.5">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-base font-medium transition duration-200 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-wide text-gray-700 mb-1.5">Adresse</label>
                        <input type="text" name="address" value="{{ old('address', $shop->address) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-base font-medium transition duration-200 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 shadow-sm">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold uppercase tracking-wide text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-base font-medium transition duration-200 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 shadow-sm">{{ old('description', $shop->description) }}</textarea>
                </div>

                <!-- Logo -->
                <div>
                    <label class="block text-sm font-bold uppercase tracking-wide text-gray-700 mb-1.5">Logo</label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full border border-gray-200 rounded-xl p-2.5 text-base font-medium transition duration-200 file:mr-4 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer shadow-sm">
                </div>

                <!-- Boutons d'action : Texte agrandi à text-base -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <a href="{{ url()->previous() }}" 
                        class="sm:col-span-1 w-full border border-gray-200 text-gray-600 font-bold py-3.5 px-4 rounded-xl hover:bg-gray-50 hover:border-gray-300 active:scale-[0.98] transition-all duration-150 text-base text-center shadow-sm block">
                        Retour
                    </a>
                    
                    <button type="submit" 
                        class="sm:col-span-2 w-full bg-blue-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-600/30 hover:-translate-y-0.5 active:scale-[0.99] active:translate-y-0 transition-all duration-150 text-base tracking-wide">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

