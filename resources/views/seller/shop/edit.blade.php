@extends('base')
@section('title','edit')
@section('content')
    <div class="max-w-xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Ma boutique</h1>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('seller.shop.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Nom de la boutique</label>
                <input type="text" name="name" value="{{ old('name', $shop->name) }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Ville</label>
                <input type="text" name="city" value="{{ old('city', $shop->city) }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Adresse</label>
                <input type="text" name="address" value="{{ old('address', $shop->address) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">{{ old('description', $shop->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Logo</label>
                <input type="file" name="logo" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg p-2 text-sm">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700">
                Enregistrer
            </button>
        </form>
    </div>
@endsection
