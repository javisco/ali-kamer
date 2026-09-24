<?php

return [
    // Base officielle de l'API Didit.
    'base_url' => env('DIDIT_BASE_URL', 'https://verification.didit.me'),

    // Clé API : ne doit jamais être exposée au navigateur.
    'api_key' => env('DIDIT_API_KEY'),

    // UUID du workflow KYC publié dans Didit.
    'workflow_id' => env('DIDIT_WORKFLOW_ID'),

    // Secret partagé de la destination Webhook Didit.
    // Il vient de Business Console > API & Webhooks.
    'webhook_secret' => env('DIDIT_WEBHOOK_SECRET'),

    'timeout' => (int) env('DIDIT_TIMEOUT', 60),
];
