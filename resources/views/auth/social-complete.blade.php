@extends('base')

@section('title', 'Finaliser votre inscription - Ali-Kamer')

@section('content')

<div class="min-h-screen bg-[#FAF9F6] py-10">

    <div class="max-w-lg mx-auto px-4">

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">

            <div class="text-center mb-8">

                <div class="w-14 h-14 mx-auto rounded-2xl bg-[#006837]
                            flex items-center justify-center text-white text-2xl mb-4">
                    🇨🇲
                </div>

                <h1 class="text-2xl font-black text-gray-900">
                    Finalisez votre inscription
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Encore une information pour terminer votre inscription
                    sur Ali-Kamer.
                </p>

            </div>

            @if(!empty($socialPending['name']))
                <div class="mb-6 rounded-2xl bg-green-50 border border-green-100 p-4">

                    <p class="text-sm font-bold text-gray-900">
                        {{ $socialPending['name'] }}
                    </p>

                    @if(!empty($socialPending['provider_email']))
                        <p class="text-xs text-gray-500 mt-1">
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Numéro Mobile Money
                    </label>

                    <input
                        type="text"
                        name="phone_momo"
                        value="{{ old('phone_momo') }}"
                        placeholder="6XXXXXXXX"
                        required
                        class="w-full rounded-xl border border-gray-200
                               px-4 py-3 text-sm outline-none
                               focus:border-[#006837]
                               focus:ring-2 focus:ring-[#006837]/10"
                    >

                    @error('phone_momo')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
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

                            <div class="rounded-xl border border-gray-200
                                        px-4 py-3 text-center font-bold
                                        peer-checked:border-[#FFC20E]
                                        peer-checked:bg-yellow-50">
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

                            <div class="rounded-xl border border-gray-200
                                        px-4 py-3 text-center font-bold
                                        peer-checked:border-[#FF7900]
                                        peer-checked:bg-orange-50">
                                Orange
                            </div>
                        </label>

                    </div>

                    @error('momo_operator')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-[#006837]
                           px-5 py-3.5 text-sm font-extrabold text-white
                           hover:bg-[#004d28] transition"
                >
                    Terminer mon inscription
                </button>

            </form>

        </div>

    </div>

</div>

@endsection