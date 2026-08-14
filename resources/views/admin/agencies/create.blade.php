@extends('base')
@section('title', 'Nouvelle agence')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-lg mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Nouvelle agence</h1>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.agencies.store') }}"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nom de l'agence <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Ex: General Express"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Téléphone contact
                    </label>
                    <input type="tel" name="contact_phone" value="{{ old('contact_phone') }}"
                        placeholder="Ex: 699000000"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email contact
                    </label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}"
                        placeholder="Ex: contact@general.cm"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500">
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                       py-3 rounded-xl transition text-sm">
                    Créer l'agence
                </button>

                <a href="{{ route('admin.agencies.index') }}"
                    class="block text-center text-sm text-gray-500 hover:underline">
                    Annuler
                </a>
            </form>
        </div>
    </div>
@endsection
