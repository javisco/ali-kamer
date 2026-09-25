<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Client très léger de l'API Hosted Sessions Didit.
 *
 * Aucun package Composer n'est nécessaire : Laravel HTTP Client suffit.
 * Cette classe ne contient aucune logique métier KYC ; elle ne fait que
 * communiquer avec Didit. La logique métier reste dans KycService.
 */
class DiditService
{
    private function http(): PendingRequest
    {
        $apiKey = config('didit.api_key');

        if (! $apiKey) {
            throw new RuntimeException('DIDIT_API_KEY est manquante.');
        }

        return Http::baseUrl(rtrim(config('didit.base_url'), '/'))
            ->acceptJson()
            ->withHeaders([
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(config('didit.timeout', 60));
    }

    /**
     * Crée (ou récupère, grâce à vendor_data) une session KYC Didit.
     */
    public function createKycSession(User $user, ?string $callbackUrl = null, string $source = 'ali-kamer-web'): array
    {
        $workflowId = config('didit.workflow_id');

        if (! $workflowId) {
            throw new RuntimeException('DIDIT_WORKFLOW_ID est manquante.');
        }

        $response = $this->http()->post('/v3/session/', [
            'workflow_id' => $workflowId,

            // Identifiant stable : Didit peut ainsi réutiliser une session
            // non terminée et regrouper les vérifications du même vendeur.
            'vendor_data' => 'user-' . $user->id,

            // Callback navigateur / mobile. Il ne remplace PAS le webhook serveur.
            'callback' => $callbackUrl ?: route('didit.kyc.callback'),
            'callback_method' => 'both',

            'metadata' => [
                'user_id' => $user->id,
                'role' => 'seller',
                'source' => $source,
            ],

            'language' => 'fr',

            'contact_details' => [
                'email' => $user->email,
                'send_notification_emails' => false,
                'email_lang' => 'fr',
                'phone' => $user->phone_momo ?: $user->phone,
            ],

            // On indique à Didit que le document attendu est camerounais.
            'expected_details' => [
                'id_country' => 'CMR',
                'expected_document_types' => ['ID'],
            ],
        ]);

        if (! $response->successful()) {
            throw new RuntimeException(
                'Didit Create Session a échoué (' . $response->status() . ').'
            );
        }

        $data = $response->json();

        if (! is_array($data) || empty($data['session_id']) || empty($data['url'])) {
            throw new RuntimeException('Réponse Didit Create Session invalide.');
        }

        return $data;
    }

    /**
     * Récupère la décision complète côté serveur.
     *
     * Cette méthode reste volontairement disponible même si le webhook
     * contient déjà une décision : elle est notre filet de sécurité et la
     * source de vérité lorsque le webhook Simple est utilisé.
     */
    public function getSessionDecision(string $sessionId): array
    {
        $response = $this->http()->get('/v3/session/' . rawurlencode($sessionId) . '/decision/');

        if (! $response->successful()) {
            throw new RuntimeException(
                'Didit Retrieve Decision a échoué (' . $response->status() . ').'
            );
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException('Réponse Didit Decision invalide.');
        }

        return $data;
    }
}
