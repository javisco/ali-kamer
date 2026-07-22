@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">

    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden">

        <!-- En-tête -->
        <div class="bg-blue-600 px-8 py-8 text-center">

            <div class="text-6xl mb-3">
                📧
            </div>

            <h1 class="text-3xl font-bold text-white">
                Vérifiez votre adresse e-mail
            </h1>

            <p class="mt-2 text-blue-100">
                Une dernière étape avant d'accéder à votre compte.
            </p>

        </div>

        <!-- Corps -->
        <div class="p-8">

            <p class="text-gray-700 leading-7">

                Nous avons envoyé un e-mail de confirmation à votre adresse.

                <br><br>

                Cliquez sur le lien contenu dans cet e-mail afin
                d'activer votre compte et de commencer à utiliser
                <strong>ALI-KAMER</strong>.

            </p>

            <!-- Succès -->

            @if(session('message'))

                <div
                    class="mt-6 rounded-lg border border-green-300 bg-green-50 px-5 py-4 text-green-700">

                    {{ session('message') }}

                </div>

            @endif

            <!-- Informations -->

            <div
                class="mt-8 rounded-xl border border-yellow-200 bg-yellow-50 p-5">

                <h2 class="font-semibold text-yellow-700 mb-2">

                    Vous ne trouvez pas l'e-mail ?

                </h2>

                <ul class="list-disc pl-6 space-y-2 text-gray-700">

                    <li>Patientez quelques minutes.</li>

                    <li>Vérifiez votre dossier <strong>Spam</strong> ou <strong>Courrier indésirable</strong>.</li>

                    <li>Assurez-vous que votre adresse e-mail est correcte.</li>

                </ul>

            </div>

            <!-- Bouton -->

            <form
                action="{{ route('verification.send') }}"
                method="POST"
                class="mt-8">

                @csrf

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 py-4 text-lg font-semibold text-white transition hover:bg-blue-700">

                    Renvoyer l'e-mail de vérification

                </button>

            </form>

            <!-- Retour connexion -->

            <div class="mt-8 border-t pt-6 text-center">

                <a
                    href="{{ route('login.show') }}"
                    class="text-blue-600 font-semibold hover:underline">

                    Retour à la connexion

                </a>

            </div>

        </div>

    </div>

</div>