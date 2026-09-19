@extends('base')

@section('title', 'Finaliser votre inscription - Ali-Kamer')

@section('content')

<div class="min-h-screen bg-slate-50 py-10">

    <div class="max-w-lg mx-auto px-4">

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-8">

            <div class="text-center mb-8">

                <div class="w-14 h-14 mx-auto rounded-2xl bg-primary-600
                            flex items-center justify-center text-white text-2xl mb-4">
                    🇨🇲
                </div>

                <h1 class="text-2xl font-black text-slate-900">
                    Finalisez votre inscription
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Encore une information pour terminer votre inscription
                    sur Ali-Kamer.
                </p>

            </div>

            @if(!empty($socialPending['name']))
                <div class="mb-6 rounded-2xl bg-success-50 border border-success-100 p-4">

                    <p class="text-sm font-bold text-slate-900">
                        {{ $socialPending['name'] }}
                    </p>

                    @if(!empty($socialPending['provider_email']))
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $socialPending['provider_email'] }}
                        </p>
                    @endif

                </div>
            @endif

            <form
                method="POST"
                action="{{ route('social.complete.buyer') }}"
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
                        class="w-full rounded-xl border border-slate-200
                               px-4 py-3 text-sm outline-none
                               focus:border-primary-600
                               focus:ring-2 focus:ring-primary-500/10"
                    >

                    @error('phone_momo')
                        <p class="mt-1 text-xs text-danger">
                            {{ $message }}
                        </p>
                    @enderror
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

                            <div class="rounded-xl border border-slate-200
                                        px-4 py-3 text-center font-bold
                                        peer-checked:border-warning
                                        peer-checked:bg-warning-50">
                                MTN
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

                            <div class="rounded-xl border border-slate-200
                                        px-4 py-3 text-center font-bold
                                        peer-checked:border-accent-500
                                        peer-checked:bg-accent-50">
                                Orange
                            </div>
                        </label>

                    </div>

                    @error('momo_operator')
                        <p class="mt-1 text-xs text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-primary-600
                           px-5 py-3.5 text-sm font-extrabold text-white
                           hover:bg-primary-800 transition"
                >
                    Terminer mon inscription
                </button>

            </form>

        </div>

    </div>

</div>

@endsection