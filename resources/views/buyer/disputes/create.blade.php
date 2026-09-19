@extends('layouts.buyer')
@section('title', 'Signaler un problème')
@section('content')
    <div class="bg-slate-50 min-h-screen py-8">
        <div class="max-w-2xl mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-slate-900 mb-2">Signaler un problème</h1>
            <p class="text-sm text-slate-500 mb-6">Commande {{ $order->reference }}</p>

            @if ($errors->any())
                <div class="bg-danger-50 border border-danger-200 text-danger-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('buyer.disputes.store', $order) }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                {{-- Motif --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <label class="block text-sm font-medium text-slate-700 mb-3">
                        Motif du litige <span class="text-danger">*</span>
                    </label>
                    <div class="space-y-2">
                        @foreach ($types as $value => $label)
                            <label
                                class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer
                                  hover:border-primary-300 transition
                                  {{ old('type') === $value ? 'border-primary-500 bg-primary-50' : 'border-slate-200' }}">
                                <input type="radio" name="type" value="{{ $value }}"
                                    {{ old('type') === $value ? 'checked' : '' }} required>
                                <span class="text-sm font-medium">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('type')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Description détaillée <span class="text-danger">*</span>
                    </label>
                    <textarea name="description" rows="5" required
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm
                             focus:ring-2 focus:ring-primary-500"
                        placeholder="Décrivez précisément le problème rencontré (minimum 20 caractères)...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preuves --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Photos ou documents <span class="text-slate-400 font-normal">(optionnel)</span>
                    </label>
                    <p class="text-xs text-slate-500 mb-3">
                        Ajoutez des photos du produit reçu ou tout document utile. Max 5 MB par fichier.
                    </p>
                    <input type="file" name="files[]" accept="image/*,.pdf" multiple
                        class="w-full border border-slate-300 rounded-xl p-2 text-sm">
                </div>

                {{-- Avertissement --}}
                <div class="bg-accent-50 border border-accent-200 rounded-xl p-4 text-sm text-accent-700">
                    ⚠ Les litiges abusifs peuvent affecter votre score de fiabilité.
                    Assurez-vous que le problème est réel avant de soumettre.
                </div>

                <button type="submit"
                    class="w-full bg-danger hover:bg-danger text-white font-bold
                       py-4 rounded-2xl transition">
                    Soumettre le litige
                </button>

                <a href="{{ route('buyer.orders.show', $order) }}"
                    class="block text-center text-sm text-slate-500 hover:underline">
                    Annuler
                </a>
            </form>
        </div>
    </div>
@endsection
