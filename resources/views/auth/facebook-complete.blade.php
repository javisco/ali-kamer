@extends('layouts.guest')

@section('content')

<div class="min-h-screen bg-[#FAF9F6] flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-lg">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Ali-Kamer"
                    class="h-16 w-auto"
                >
            </div>

            <h1 class="text-2xl font-bold text-[#004d28]">
                Finalisez votre inscription
            </h1>

            <p class="text-gray-500 mt-2">
                Votre compte Facebook a été reconnu.
                Il reste quelques informations à renseigner.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 md:p-8">

            {{-- Facebook identity --}}
            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 mb-6">

                @if(!empty(session('facebook_pending.avatar')))
                    <img
                        src="{{ session('facebook_pending.avatar') }}"
                        alt="Photo Facebook"
                        class="w-14 h-14 rounded-full object-cover"
                    >
                @else
                    <div class="w-14 h-14 rounded-full bg-[#006837] text-white flex items-center justify-center font-bold text-xl">
                        {{ strtoupper(substr(session('facebook_pending.name', 'A'), 0, 1)) }}
                    </div>
                @endif

                <div>
                    <p class="font-semibold text-gray-900">
                        {{ session('facebook_pending.name') }}
                    </p>

                    @if(session('facebook_pending.provider_email'))
                        <p class="text-sm text-gray-500">
                            {{ session('facebook_pending.provider_email') }}
                        </p>
                    @endif

                    <span class="inline-flex mt-1 text-xs font-medium text-[#006837]">
                        Connecté avec Facebook
                    </span>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('facebook.complete.buyer.post') }}"
                class="space-y-5"
            >
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Numéro Mobile Money
                    </label>

                    <input
                        type="text"
                        name="phone_momo"
                        value="{{ old('phone_momo') }}"
                        placeholder="6XXXXXXXX"
                        required
                        class="w-full rounded-xl border-gray-300 focus:border-[#006837] focus:ring-[#006837] px-4 py-3"
                    >

                    <p class="mt-1 text-xs text-gray-500">
                        Ce numéro sera utilisé pour vos paiements.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Opérateur Mobile Money
                    </label>

                    <div class="grid grid-cols-2 gap-3">

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="momo_operator"
                                value="mtn"
                                class="peer sr-only"
                                {{ old('momo_operator') === 'mtn' ? 'checked' : '' }}
                            >

                            <div class="border rounded-xl p-4 text-center peer-checked:border-[#FFC20E] peer-checked:bg-yellow-50 transition">
                                <span class="font-semibold text-gray-800">
                                    MTN Mobile Money
                                </span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="momo_operator"
                                value="orange"
                                class="peer sr-only"
                                {{ old('momo_operator') === 'orange' ? 'checked' : '' }}
                            >

                            <div class="border rounded-xl p-4 text-center peer-checked:border-orange-500 peer-checked:bg-orange-50 transition">
                                <span class="font-semibold text-gray-800">
                                    Orange Money
                                </span>
                            </div>
                        </label>

                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-[#006837] hover:bg-[#004d28] text-white font-bold py-3.5 rounded-xl transition"
                >
                    Terminer mon inscription
                </button>

            </form>

        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            Ali-Kamer — Acheter et vendre sans stress.
        </p>

    </div>

</div>

@endsection