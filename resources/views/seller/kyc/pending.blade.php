@extends('base')

@section('title', 'Vérification en cours')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center px-4">

    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden">

        <!-- En-tête -->

        <div class="bg-yellow-500 px-8 py-8 text-center">

            <div class="text-6xl mb-3">
                ⏳
            </div>

            <h1 class="text-3xl font-bold text-white">
                Vérification en cours
            </h1>

            <p class="mt-2 text-yellow-100">
                Votre dossier est actuellement en cours d'examen.
            </p>

        </div>

        <!-- Contenu -->

        <div class="p-8">

            <p class="text-gray-700 leading-7">

                Merci d'avoir soumis votre dossier de vérification.

                <br><br>

                Notre équipe procède actuellement à l'analyse des documents
                que vous avez transmis afin de garantir la sécurité de
                l'ensemble des utilisateurs de la plateforme.

            </p>

            <!-- Informations -->

            <div class="mt-8 rounded-xl border border-yellow-200 bg-yellow-50 p-6">

                <h2 class="font-semibold text-yellow-700 mb-4">

                    Informations sur votre demande

                </h2>

                <div class="space-y-3 text-gray-700">

                    <div class="flex justify-between">

                        <span>Date de soumission</span>

                        <span class="font-semibold">

                            {{ $kyc->created_at->format('d/m/Y à H:i') }}

                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span>Statut</span>

                        <span class="rounded-full bg-yellow-200 px-3 py-1 text-sm font-semibold text-yellow-800">

                            En cours de traitement

                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span>Délai estimé</span>

                        <span class="font-semibold">

                            24 à 48 heures ouvrables

                        </span>

                    </div>

                </div>

            </div>

            <!-- Notification -->

            <div class="mt-8 rounded-xl border border-blue-200 bg-blue-50 p-6">

                <h2 class="font-semibold text-blue-700 mb-2">

                    Notification

                </h2>

                <p class="text-gray-700 leading-7">

                    Dès qu'une décision sera prise, vous recevrez une notification
                    sur votre compte. Si un numéro de téléphone est associé à votre
                    compte, nous pourrons également vous informer par WhatsApp ou SMS
                    selon les services disponibles.

                </p>

            </div>

            <!-- Conseils -->

            <div class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-6">

                <h2 class="font-semibold text-gray-700 mb-3">

                    Pendant ce temps...

                </h2>

                <ul class="list-disc pl-6 space-y-2 text-gray-600">

                    <li>Évitez de soumettre un nouveau dossier.</li>

                    <li>Surveillez votre boîte e-mail.</li>

                    <li>Vérifiez également votre dossier « Spam ».</li>

                    <li>Préparez les informations de votre boutique si votre dossier est accepté.</li>

                </ul>

            </div>

        </div>

    </div>

</div>

@endsection