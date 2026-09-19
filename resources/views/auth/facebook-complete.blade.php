@extends('layouts.guest')

@section('content')

<div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-10">

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

            <h1 class="text-2xl font-bold text-primary-800">
                Finalisez votre inscription
            </h1>

            <p class="text-slate-500 mt-2">
                Votre compte Facebook a été reconnu.
                Il reste quelques informations à renseigner.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6 md:p-8">

            {{-- Facebook identity --}}
            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 mb-6">

                @if(!empty(session('facebook_pending.avatar')))
                    <img
                        src="{{ session('facebook_pending.avatar') }}"
                        alt="Photo Facebook"
                        class="w-14 h-14 rounded-full object-cover"
                    >
                @else
                    <div class="w-14 h-14 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold text-xl">
                        {{ strtoupper(substr(session('facebook_pending.name', 'A'), 0, 1)) }}
                    </div>
                @endif

                <div>
                    <p class="font-semibold text-slate-900">
                        {{ session('facebook_pending.name') }}
                    </p>

                    @if(session('facebook_pending.provider_email'))
                        <p class="text-sm text-slate-500">
                            {{ session('facebook_pending.provider_email') }}
                        </p>
                    @endif

                    <span class="inline-flex mt-1 text-xs font-medium text-primary-600">
                        Connecté avec Facebook
                    </span>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-5 rounded-xl bg-danger-50 border border-danger-200 p-4">
                    <ul class="text-sm text-danger-700 space-y-1">
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
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Numéro Mobile Money
                    </label>

                    <input
                        type="text"
                        name="phone_momo"
                        value="{{ old('phone_momo') }}"
                        placeholder="6XXXXXXXX"
                        required
                        class="w-full rounded-xl border-slate-300 focus:border-primary-600 focus:ring-primary-500 px-4 py-3"
                    >

                    <p class="mt-1 text-xs text-slate-500">
                        Ce numéro sera utilisé pour vos paiements.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
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

                            <div class="border rounded-xl p-4 text-center peer-checked:border-warning peer-checked:bg-warning-50 transition">
                                <span class="font-semibold text-slate-800">
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

                            <div class="border rounded-xl p-4 text-center peer-checked:border-accent-500 peer-checked:bg-accent-50 transition">
                                <span class="font-semibold text-slate-800">
                                    Orange Money
                                </span>
                            </div>
                        </label>

                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-800 text-white font-bold py-3.5 rounded-xl transition"
                >
                    Terminer mon inscription
                </button>

            </form>

        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            Ali-Kamer — Acheter et vendre sans stress.
        </p>

    </div>

</div>

@endsection