@extends('base')
@section('title', 'Signaler un problème')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-2xl mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Signaler un problème</h1>
            <p class="text-sm text-gray-500 mb-6">Commande {{ $order->reference }}</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('buyer.disputes.store', $order) }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                {{-- Motif --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Motif du litige <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        @foreach ($types as $value => $label)
                            <label
                                class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer
                                  hover:border-indigo-300 transition
                                  {{ old('type') === $value ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                <input type="radio" name="type" value="{{ $value }}"
                                    {{ old('type') === $value ? 'checked' : '' }} required>
                                <span class="text-sm font-medium">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('type')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Description détaillée <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="5" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                             focus:ring-2 focus:ring-indigo-500"
                        placeholder="Décrivez précisément le problème rencontré (minimum 20 caractères)...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preuves --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Photos ou documents <span class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-3">
                        Ajoutez des photos du produit reçu ou tout document utile. Max 5 MB par fichier.
                    </p>
                    <input type="file" name="files[]" accept="image/*,.pdf" multiple
                        class="w-full border border-gray-300 rounded-xl p-2 text-sm">
                </div>

                {{-- Avertissement --}}
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 text-sm text-orange-700">
                    ⚠ Les litiges abusifs peuvent affecter votre score de fiabilité.
                    Assurez-vous que le problème est réel avant de soumettre.
                </div>

                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold
                       py-4 rounded-2xl transition">
                    Soumettre le litige
                </button>

                <a href="{{ route('buyer.orders.show', $order) }}"
                    class="block text-center text-sm text-gray-500 hover:underline">
                    Annuler
                </a>
            </form>
        </div>
    </div>
@endsection
