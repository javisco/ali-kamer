
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="mx-auto shadow-2xl w-md mt-30 bg-white rounded-xl p-6 text-center h-70">
        <h2 class="text-xl font-bold mb-4 text-blue-600">Vérification de votre adresse e-mail</h2>

        <p class="mb-4 text-gray-600">
            Un e-mail de confirmation vient de vous être envoyé.
            Veuillez ouvrir votre boîte de réception et cliquer sur le lien pour activer votre compte.
        </p>

        <!-- Message de confirmation si l'utilisateur clique sur "Renvoyer" -->
        @if (session('message'))
            <div class="text-green-500 mb-4 font-semibold">
                {{ session('message') }}
            </div>
        @endif

        <div class="mt-6  pt-4">
            <p class="text-sm text-gray-500 mb-2">Vous n'avez pas reçu l'email ?</p>

            <!-- Formulaire de renvoi -->
            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-md transition">
                    Cliquez ici pour renvoyer l'email
                </button>
            </form>
        </div>
    </div>

