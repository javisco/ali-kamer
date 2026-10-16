<?php

/**
 * Catalogue des paramètres métier Ali-Kamer.
 *
 * Les clés déjà utilisées en production (protection_rate, auto_complete_hours, …)
 * sont conservées. Les nouvelles règles utilisent un préfixe de module.
 *
 * applies_to :
 *   - new_only : uniquement les nouvelles opérations
 *   - new_and_in_progress : lu au moment de l'exécution (jobs, boutons, quotas)
 *   - events_only : uniquement les événements futurs (TrustScore, logs)
 */
return [
    'cache_ttl' => 3600,

    'groups' => [
        'commissions'  => 'Paiements et commissions',
        'orders'       => 'Commandes et délais',
        'cancellations'=> 'Annulations tardives',
        'catalog'      => 'Catalogue et produits',
        'variantes'    => 'Variantes',
        'quotas'       => 'Quotas vendeurs',
        'disputes'     => 'Litiges',
        'reviews'      => 'Notes et avis',
        'trust'        => 'TrustScore et TrustEvents',
        'blacklist'    => 'Blacklist — poids des identifiants',
        'decision'     => 'DecisionEngine — seuils',
        'identity'     => 'Identité et vélocité',
        'sanctions'    => 'Sanctions',
        'notifications'=> 'Notifications et rappels',
        'limites'      => 'Limites financières',
    ],

    'definitions' => [

        // ── Commissions (déjà existantes) ─────────────────────────────
        'protection_rate' => [
            'type' => 'percentage', 'group' => 'commissions', 'sort' => 1, 'unit' => '%',
            'label' => 'Frais de protection acheteur',
            'description' => 'Pourcentage prélevé sur le sous-total. Non remboursable en cas d’annulation pour retard. Les commandes déjà créées conservent leur snapshot financier.',
            'default' => '2', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_only',
        ],
        'gateway_collect_rate' => [
            'type' => 'percentage', 'group' => 'commissions', 'sort' => 2, 'unit' => '%',
            'label' => 'Frais passerelle — collecte',
            'description' => 'Taux Elgiopay sur l’encaissement acheteur (Gross-Up). Vérifier le dashboard Elgiopay avant modification.',
            'default' => '2', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_only',
        ],
        'platform_commission_rate' => [
            'type' => 'percentage', 'group' => 'commissions', 'sort' => 3, 'unit' => '%',
            'label' => 'Commission plateforme',
            'description' => 'Commission prélevée sur le montant net du vendeur. Snapshot figé à la création de commande.',
            'default' => '5', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_only',
        ],
        'agency_commission_rate' => [
            'type' => 'percentage', 'group' => 'commissions', 'sort' => 4, 'unit' => '%',
            'label' => 'Commission agences',
            'description' => 'Reversé aux agences pour chaque colis traité.',
            'default' => '1', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_only',
        ],
        'gateway_payout_rate' => [
            'type' => 'percentage', 'group' => 'commissions', 'sort' => 5, 'unit' => '%',
            'label' => 'Frais passerelle — décaissement',
            'description' => 'Taux Elgiopay sur les virements sortants. 0 tant qu’Elgiopay ne facture rien sur ce trajet.',
            'default' => '0', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_only',
        ],
        'gateway_fixed_fee' => [
            'type' => 'integer', 'group' => 'commissions', 'sort' => 6, 'unit' => 'FCFA',
            'label' => 'Frais fixe passerelle',
            'description' => 'Frais fixe additionnel dans le Gross-Up (collecte et décaissement).',
            'default' => '0', 'min' => 0, 'max' => 100000, 'sensitive' => true, 'applies_to' => 'new_only',
        ],

        // ── Commandes / timers ────────────────────────────────────────
        'auto_complete_enabled' => [
            'type' => 'boolean', 'group' => 'orders', 'sort' => 1, 'unit' => null,
            'label' => 'Activer l’auto-complétion',
            'description' => 'Si désactivé, les commandes en attente de confirmation acheteur ne sont plus auto-complétées. Sans effet rétroactif sur les commandes déjà clôturées.',
            'default' => '1', 'min' => 0, 'max' => 1, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'auto_complete_hours' => [
            'type' => 'integer', 'group' => 'orders', 'sort' => 2, 'unit' => 'heures',
            'label' => 'Délai d’auto-complétion',
            'description' => 'À partir de l’arrivée en agence (timer_deadline). Statut concerné : awaiting_buyer_confirmation. Conséquence : passage en auto_completed et libération de l’escrow. Les litiges, annulations et échecs sont exclus. Lu à chaque exécution du job.',
            'default' => '72', 'min' => 1, 'max' => 720, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'transport_payment_hours' => [
            'type' => 'integer', 'group' => 'orders', 'sort' => 3, 'unit' => 'heures',
            'label' => 'Délai paiement des frais de transport',
            'description' => 'Délai de référence pour le paiement transport. Consommateur : moteur financier / documentation opérationnelle. L’annulation automatique n’est pas implémentée tant qu’aucun job dédié n’existe.',
            'default' => '24', 'min' => 1, 'max' => 168, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'seller_shipping_warning_enabled' => [
            'type' => 'boolean', 'group' => 'orders', 'sort' => 4, 'unit' => null,
            'label' => 'Alerte vendeur non-expéditeur',
            'description' => 'Si désactivé, la commande Artisan orders:warn-unshipped n’envoie plus de notifications. Les notifications déjà envoyées restent en historique.',
            'default' => '1', 'min' => 0, 'max' => 1, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'seller_shipping_warning_hours' => [
            'type' => 'integer', 'group' => 'orders', 'sort' => 5, 'unit' => 'heures',
            'label' => 'Délai avant alerte vendeur',
            'description' => 'Point de départ : paid_at. Statut : paid (pas encore en préparation). Conséquence : notification vendeur.',
            'default' => '48', 'min' => 1, 'max' => 336, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'orders.otp_expiry_hours' => [
            'type' => 'integer', 'group' => 'orders', 'sort' => 6, 'unit' => 'heures',
            'label' => 'Expiration OTP après paiement transport',
            'description' => 'Point de départ : paiement des frais de transport. Utilisé par PaymentService.',
            'default' => '120', 'min' => 1, 'max' => 720, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'orders.arrival_otp_expiry_hours' => [
            'type' => 'integer', 'group' => 'orders', 'sort' => 7, 'unit' => 'heures',
            'label' => 'Expiration OTP à l’arrivée',
            'description' => 'Point de départ : validation d’arrivée en agence destination. Valeur historique : 10 jours (240 h).',
            'default' => '240', 'min' => 1, 'max' => 720, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'pickup_reminder_first_hours' => [
            'type' => 'integer', 'group' => 'notifications', 'sort' => 1, 'unit' => 'heures',
            'label' => 'Premier rappel de retrait',
            'description' => 'Heures après arrived_at pour le premier rappel. Statut : awaiting_buyer_confirmation. Désactiver via pickup_reminders_enabled.',
            'default' => '24', 'min' => 1, 'max' => 168, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'pickup_reminder_second_hours' => [
            'type' => 'integer', 'group' => 'notifications', 'sort' => 2, 'unit' => 'heures',
            'label' => 'Second rappel de retrait',
            'description' => 'Heures après arrived_at pour le rappel urgent.',
            'default' => '48', 'min' => 1, 'max' => 336, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'pickup_reminders_enabled' => [
            'type' => 'boolean', 'group' => 'notifications', 'sort' => 3, 'unit' => null,
            'label' => 'Activer les rappels de retrait',
            'description' => 'Si désactivé, la tâche quotidienne n’envoie plus de rappels. Les commandes déjà arrivées ne sont pas annulées.',
            'default' => '1', 'min' => 0, 'max' => 1, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],

        // ── Annulations tardives ──────────────────────────────────────
        'orders.late_cancel_unprepared_enabled' => [
            'type' => 'boolean', 'group' => 'cancellations', 'sort' => 1, 'unit' => null,
            'label' => 'Proposer l’annulation si non préparée',
            'description' => 'Proposition d’annulation à l’acheteur (pas d’annulation automatique). Désactiver n’affecte pas l’annulation standard avant paiement. S’applique aux commandes en cours au moment du contrôle serveur.',
            'default' => '1', 'min' => 0, 'max' => 1, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'orders.late_cancel_unprepared_hours' => [
            'type' => 'integer', 'group' => 'cancellations', 'sort' => 2, 'unit' => 'heures',
            'label' => 'Délai avant proposition (non préparée)',
            'description' => 'Point de départ : created_at. Statut requis : paid. La commande ne doit pas être passée en préparation. Exemple métier : 3 jours = 72 h.',
            'default' => '72', 'min' => 1, 'max' => 720, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'orders.late_cancel_undeposited_enabled' => [
            'type' => 'boolean', 'group' => 'cancellations', 'sort' => 3, 'unit' => null,
            'label' => 'Proposer l’annulation si non déposée',
            'description' => 'Après passage en préparation, si aucun dépôt réel en agence (statut registered_origin ou ultérieur) n’est enregistré.',
            'default' => '1', 'min' => 0, 'max' => 1, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'orders.late_cancel_undeposited_hours' => [
            'type' => 'integer', 'group' => 'cancellations', 'sort' => 4, 'unit' => 'heures',
            'label' => 'Délai avant proposition (non déposée)',
            'description' => 'Point de départ : preparing_at. Statut : preparing. Le dépôt est l’événement registered_origin (secrétaire), pas une date saisie par le vendeur.',
            'default' => '72', 'min' => 1, 'max' => 720, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'orders.late_cancel_window_hours' => [
            'type' => 'integer', 'group' => 'cancellations', 'sort' => 5, 'unit' => 'heures',
            'label' => 'Fenêtre de validité de la proposition',
            'description' => 'Durée pendant laquelle l’annulation reste possible après le délai. 0 = tant que la condition métier reste vraie (recommandé).',
            'default' => '0', 'min' => 0, 'max' => 720, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'orders.late_cancel_notifications_enabled' => [
            'type' => 'boolean', 'group' => 'cancellations', 'sort' => 6, 'unit' => null,
            'label' => 'Notifier l’acheteur de la proposition',
            'description' => 'Envoie une notification (base de données) une seule fois par commande. Désactiver n’empêche pas le bouton si la règle d’annulation est active.',
            'default' => '1', 'min' => 0, 'max' => 1, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],

        // ── Limites financières ───────────────────────────────────────
        'min_withdrawal_amount' => [
            'type' => 'integer', 'group' => 'limites', 'sort' => 1, 'unit' => 'FCFA',
            'label' => 'Retrait minimum vendeur',
            'description' => 'Montant minimum d’un retrait wallet.',
            'default' => '1000', 'min' => 100, 'max' => 1000000, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'min_product_price' => [
            'type' => 'integer', 'group' => 'catalog', 'sort' => 1, 'unit' => 'FCFA',
            'label' => 'Prix minimum produit',
            'description' => 'Prix unitaire minimum à la création / modification.',
            'default' => '100', 'min' => 1, 'max' => 10000000, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'max_product_price' => [
            'type' => 'integer', 'group' => 'catalog', 'sort' => 2, 'unit' => 'FCFA',
            'label' => 'Prix maximum produit',
            'description' => 'Prix unitaire maximum.',
            'default' => '10000000', 'min' => 100, 'max' => 100000000, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'trust_score_prepayment_threshold' => [
            'type' => 'integer', 'group' => 'limites', 'sort' => 2, 'unit' => 'points',
            'label' => 'Seuil prépaiement (trust score)',
            'description' => 'Valeur de référence. Aucun consommateur serveur n’imposait encore ce seuil — conservée pour le moteur financier.',
            'default' => '30', 'min' => 0, 'max' => 100, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],

        // ── Catalogue ─────────────────────────────────────────────────
        'catalog.max_images_per_product' => [
            'type' => 'integer', 'group' => 'catalog', 'sort' => 3, 'unit' => 'images',
            'label' => 'Images maximum par produit',
            'description' => 'Limite d’upload à la création. Historique : 5.',
            'default' => '5', 'min' => 1, 'max' => 20, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.max_image_size_kb' => [
            'type' => 'integer', 'group' => 'catalog', 'sort' => 4, 'unit' => 'Ko',
            'label' => 'Taille maximale d’une image',
            'description' => 'Historique : 2048 Ko (2 Mo). Formats : jpg, jpeg, png, webp (non éditables ici — contrainte technique).',
            'default' => '2048', 'min' => 100, 'max' => 10240, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.max_title_length' => [
            'type' => 'integer', 'group' => 'catalog', 'sort' => 5, 'unit' => 'caractères',
            'label' => 'Longueur maximale du titre',
            'description' => 'Historique : 80.',
            'default' => '80', 'min' => 10, 'max' => 255, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],

        // ── Variantes (déjà existantes) ───────────────────────────────
        'variant_max_attributes' => [
            'type' => 'integer', 'group' => 'variantes', 'sort' => 1, 'unit' => 'attributs',
            'label' => 'Attributs maximum par produit',
            'description' => 'Les variantes déjà vendues ne sont pas supprimées si la limite baisse.',
            'default' => '3', 'min' => 1, 'max' => 8, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'variant_max_values_per_attribute' => [
            'type' => 'integer', 'group' => 'variantes', 'sort' => 2, 'unit' => 'valeurs',
            'label' => 'Valeurs maximum par attribut',
            'default' => '12', 'min' => 1, 'max' => 50, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
            'description' => 'Limite par attribut à la création / synchronisation.',
        ],
        'variant_max_total_values' => [
            'type' => 'integer', 'group' => 'variantes', 'sort' => 3, 'unit' => 'valeurs',
            'label' => 'Valeurs maximum au total',
            'default' => '30', 'min' => 1, 'max' => 200, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
            'description' => 'Somme des valeurs de tous les attributs du produit.',
        ],
        'variant_max_combinations' => [
            'type' => 'integer', 'group' => 'variantes', 'sort' => 4, 'unit' => 'combinaisons',
            'label' => 'Combinaisons maximum',
            'default' => '36', 'min' => 1, 'max' => 200, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
            'description' => 'Empêche la génération incontrôlée du produit cartésien.',
        ],
        'variant_max_active' => [
            'type' => 'integer', 'group' => 'variantes', 'sort' => 5, 'unit' => 'variantes',
            'label' => 'Variantes actives maximum',
            'default' => '36', 'min' => 1, 'max' => 200, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
            'description' => 'Variantes vendables simultanément.',
        ],

        // ── Quotas ────────────────────────────────────────────────────
        'catalog.quota_enforcement_enabled' => [
            'type' => 'boolean', 'group' => 'quotas', 'sort' => 1, 'unit' => null,
            'label' => 'Activer les quotas produits',
            'description' => 'Contrôle serveur à la création et à la publication. Une baisse de quota ne supprime pas les produits existants.',
            'default' => '1', 'min' => 0, 'max' => 1, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.max_products_registered' => [
            'type' => 'integer', 'group' => 'quotas', 'sort' => 2, 'unit' => 'produits',
            'label' => 'Quota de base — produits enregistrés',
            'description' => 'Comptabilise tous les produits non supprimés (visible, hidden, sold_out, banned). Les soft-deleted sont exclus.',
            'default' => '50', 'min' => 1, 'max' => 500, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.max_products_published' => [
            'type' => 'integer', 'group' => 'quotas', 'sort' => 3, 'unit' => 'produits',
            'label' => 'Quota de base — produits publiés',
            'description' => 'Comptabilise uniquement status = visible.',
            'default' => '30', 'min' => 1, 'max' => 500, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.max_products_hidden' => [
            'type' => 'integer', 'group' => 'quotas', 'sort' => 4, 'unit' => 'produits',
            'label' => 'Quota de base — brouillons / masqués',
            'description' => 'Comptabilise status = hidden (création par défaut).',
            'default' => '50', 'min' => 1, 'max' => 500, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.adaptive_quota_enabled' => [
            'type' => 'boolean', 'group' => 'quotas', 'sort' => 5, 'unit' => null,
            'label' => 'Ajustement automatique du quota',
            'description' => 'Si activé, un palier supplémentaire s’applique selon le TrustScore existant (pas un second score). Désactivé par défaut : le moteur KYC/Trust n’est pas encore consolidé comme formule d’attribution.',
            'default' => '0', 'min' => 0, 'max' => 1, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.quota_trusted_threshold' => [
            'type' => 'integer', 'group' => 'quotas', 'sort' => 6, 'unit' => 'points',
            'label' => 'Seuil TrustScore du palier « fiable »',
            'description' => 'Utilisé seulement si l’ajustement automatique est activé. Ne remplace pas une revue KYC.',
            'default' => '80', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.quota_trusted_registered' => [
            'type' => 'integer', 'group' => 'quotas', 'sort' => 7, 'unit' => 'produits',
            'label' => 'Quota enregistrés — palier fiable',
            'description' => 'Remplace le quota de base enregistrés si TrustScore ≥ seuil et ajustement activé.',
            'default' => '100', 'min' => 1, 'max' => 500, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.quota_absolute_max' => [
            'type' => 'integer', 'group' => 'quotas', 'sort' => 8, 'unit' => 'produits',
            'label' => 'Plafond absolu (enregistrés)',
            'description' => 'Aucun palier ni extension payée ne peut dépasser cette valeur (protection ressources).',
            'default' => '200', 'min' => 1, 'max' => 1000, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.paid_extension_enabled' => [
            'type' => 'boolean', 'group' => 'quotas', 'sort' => 9, 'unit' => null,
            'label' => 'Autoriser les extensions payantes',
            'description' => 'Architecture prête. Aucun tarif n’est vendu tant que le prix est 0. Une extension n’est comptée que si payment_status = paid.',
            'default' => '0', 'min' => 0, 'max' => 1, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.paid_extension_slots' => [
            'type' => 'integer', 'group' => 'quotas', 'sort' => 10, 'unit' => 'produits',
            'label' => 'Slots d’une extension (quand le modèle sera défini)',
            'description' => 'Nombre de produits supplémentaires par extension. Pas de vente automatique.',
            'default' => '20', 'min' => 1, 'max' => 200, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'catalog.paid_extension_price' => [
            'type' => 'integer', 'group' => 'quotas', 'sort' => 11, 'unit' => 'FCFA',
            'label' => 'Prix d’une extension (0 = non commercialisé)',
            'description' => 'Laisser à 0 jusqu’à validation du modèle économique. Aucun paiement n’est déclenché par ce paramètre seul.',
            'default' => '0', 'min' => 0, 'max' => 5000000, 'sensitive' => true, 'applies_to' => 'new_only',
        ],

        // ── Litiges ───────────────────────────────────────────────────
        'disputes.seller_reply_hours' => [
            'type' => 'integer', 'group' => 'disputes', 'sort' => 1, 'unit' => 'heures',
            'label' => 'Délai de réponse vendeur',
            'description' => 'Point de départ : ouverture du litige. Statut initial : open. Aucune défaite automatique à l’expiration (décision métier non tranchée).',
            'default' => '48', 'min' => 1, 'max' => 336, 'sensitive' => false, 'applies_to' => 'new_only',
        ],

        // ── Avis ──────────────────────────────────────────────────────
        'reviews.enabled' => [
            'type' => 'boolean', 'group' => 'reviews', 'sort' => 1, 'unit' => null,
            'label' => 'Activer les avis',
            'description' => 'Si désactivé, aucun nouvel avis ne peut être déposé. Les avis existants restent visibles.',
            'default' => '1', 'min' => 0, 'max' => 1, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],
        'reviews.max_days_after_completion' => [
            'type' => 'integer', 'group' => 'reviews', 'sort' => 2, 'unit' => 'jours',
            'label' => 'Délai maximal pour laisser un avis',
            'description' => 'À partir de completed_at. 0 = pas de limite (comportement historique).',
            'default' => '0', 'min' => 0, 'max' => 365, 'sensitive' => false, 'applies_to' => 'new_and_in_progress',
        ],

        // ── Trust events (points = valeur brute historique) ───────────
        'trust.points.transaction_success' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 1, 'unit' => 'points',
            'label' => 'Points — transaction réussie',
            'description' => 'Rôle : selon l’appel. Direction : + . Événements futurs uniquement. Idempotent si référence déjà enregistrée.',
            'default' => '1', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
        ],
        'trust.points.order_completed' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 2, 'unit' => 'points',
            'label' => 'Points — commande terminée',
            'description' => 'Historique : +2. Non rétroactif.',
            'default' => '2', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
        ],
        'trust.points.order_completed_fast' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 3, 'unit' => 'points',
            'label' => 'Points — commande terminée rapidement',
            'default' => '1', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
            'description' => 'Historique : +1.',
        ],
        'trust.points.dispute_won' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 4, 'unit' => 'points',
            'label' => 'Points — litige gagné',
            'default' => '3', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
            'description' => 'Historique : +3. Rôle : acheteur ou vendeur selon la résolution.',
        ],
        'trust.points.kyc_approved' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 5, 'unit' => 'points',
            'label' => 'Points — KYC approuvé',
            'default' => '10', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
            'description' => 'Historique : +10.',
        ],
        'trust.points.order_cancelled' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 6, 'unit' => 'points',
            'label' => 'Points retirés — annulation',
            'default' => '2', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
            'description' => 'Historique : -2. Direction toujours négative pour ce type.',
        ],
        'trust.points.late_pickup' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 7, 'unit' => 'points',
            'label' => 'Points retirés — retrait tardif',
            'default' => '2', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
            'description' => 'Historique : -2.',
        ],
        'trust.points.dispute_lost' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 8, 'unit' => 'points',
            'label' => 'Points retirés — litige perdu',
            'default' => '8', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
            'description' => 'Historique : -8.',
        ],
        'trust.points.order_cancelled_repeat' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 9, 'unit' => 'points',
            'label' => 'Points retirés — annulations répétées',
            'default' => '8', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
            'description' => 'Historique : -8.',
        ],
        'trust.risk_clean_min' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 20, 'unit' => 'score',
            'label' => 'Seuil profil clean',
            'description' => 'TrustScore ≥ cette valeur → risk_profile = clean. Recalculé sur les prochains événements, pas en masse.',
            'default' => '70', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
        ],
        'trust.risk_watch_min' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 21, 'unit' => 'score',
            'label' => 'Seuil profil watch',
            'default' => '50', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
            'description' => 'TrustScore ≥ cette valeur (et < clean) → watch.',
        ],
        'trust.risk_restricted_min' => [
            'type' => 'integer', 'group' => 'trust', 'sort' => 22, 'unit' => 'score',
            'label' => 'Seuil profil restricted',
            'default' => '30', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'events_only',
            'description' => 'En dessous → blocked + blacklist auto_score_critical (comportement existant).',
        ],

        // ── Blacklist weights ─────────────────────────────────────────
        'blacklist.weight.email_hash' => [
            'type' => 'integer', 'group' => 'blacklist', 'sort' => 1, 'unit' => 'poids',
            'label' => 'Poids — e-mail',
            'description' => 'Signal, pas une preuve. Historique : 40. Consommé à l’enregistrement d’un identifiant et par le DecisionEngine.',
            'default' => '40', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'blacklist.weight.phone_hash' => [
            'type' => 'integer', 'group' => 'blacklist', 'sort' => 2, 'unit' => 'poids',
            'label' => 'Poids — téléphone',
            'description' => 'Historique : 20. Peut être partagé au Cameroun.',
            'default' => '20', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'blacklist.weight.phone_momo_hash' => [
            'type' => 'integer', 'group' => 'blacklist', 'sort' => 3, 'unit' => 'poids',
            'label' => 'Poids — Mobile Money',
            'description' => 'Historique : 85. Identifiant critique (peut conduire à BLOCK avec CNI).',
            'default' => '85', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'blacklist.weight.ip_hash' => [
            'type' => 'integer', 'group' => 'blacklist', 'sort' => 4, 'unit' => 'poids',
            'label' => 'Poids — IP',
            'description' => 'Historique : 15. Signal faible, souvent partagé. Ne déclenche pas un ban à lui seul.',
            'default' => '15', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'blacklist.weight.device_id_hash' => [
            'type' => 'integer', 'group' => 'blacklist', 'sort' => 5, 'unit' => 'poids',
            'label' => 'Poids — appareil',
            'description' => 'Utilisé si un device_id est fourni à IdentityResolver. Valeur initiale 25 (non stocké auparavant dans TrustService).',
            'default' => '25', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'blacklist.weight.cni_hash' => [
            'type' => 'integer', 'group' => 'blacklist', 'sort' => 6, 'unit' => 'poids',
            'label' => 'Poids — CNI',
            'description' => 'Historique : 100. Identifiant critique.',
            'default' => '100', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'blacklist.weight.face_vector_hash' => [
            'type' => 'integer', 'group' => 'blacklist', 'sort' => 7, 'unit' => 'poids',
            'label' => 'Poids — visage',
            'description' => 'Un visage seul ne bloque jamais automatiquement (règle DecisionEngine conservée).',
            'default' => '50', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],

        // ── DecisionEngine ────────────────────────────────────────────
        'decision.risk_block' => [
            'type' => 'integer', 'group' => 'decision', 'sort' => 1, 'unit' => 'score',
            'label' => 'Seuil BLOCK (lien compte banni)',
            'description' => 'Si lié à un compte banni ET riskScore ≥ ce seuil → BLOCK. Historique : 80.',
            'default' => '80', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'decision.risk_review' => [
            'type' => 'integer', 'group' => 'decision', 'sort' => 2, 'unit' => 'score',
            'label' => 'Seuil REVIEW',
            'description' => 'riskScore ≥ ce seuil (ou same_face / lien banni) → REVIEW. Historique : 50. Jamais de ban automatique sur le visage seul.',
            'default' => '50', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'decision.risk_challenge' => [
            'type' => 'integer', 'group' => 'decision', 'sort' => 3, 'unit' => 'score',
            'label' => 'Seuil CHALLENGE',
            'description' => 'Historique : 25.',
            'default' => '25', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'decision.name_match_min' => [
            'type' => 'integer', 'group' => 'decision', 'sort' => 4, 'unit' => 'score',
            'label' => 'Score minimal de correspondance de nom KYC',
            'description' => 'En dessous, +25 au riskScore. Historique : 70.',
            'default' => '70', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],

        // ── Identité ──────────────────────────────────────────────────
        'identity.velocity_window_hours' => [
            'type' => 'integer', 'group' => 'identity', 'sort' => 1, 'unit' => 'heures',
            'label' => 'Fenêtre de vélocité inscriptions',
            'description' => 'Historique : 1 heure.',
            'default' => '1', 'min' => 1, 'max' => 24, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'identity.velocity_register_max' => [
            'type' => 'integer', 'group' => 'identity', 'sort' => 2, 'unit' => 'inscriptions',
            'label' => 'Inscriptions max par IP sur la fenêtre',
            'description' => 'Historique : 5. Au-delà, IdentityResolver propose block (décision ensuite par DecisionEngine).',
            'default' => '5', 'min' => 1, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'identity.link_confidence_min' => [
            'type' => 'integer', 'group' => 'identity', 'sort' => 3, 'unit' => '%',
            'label' => 'Confiance minimale pour lier des comptes',
            'description' => 'Historique : 70.',
            'default' => '70', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],
        'identity.banned_link_confidence' => [
            'type' => 'integer', 'group' => 'identity', 'sort' => 4, 'unit' => '%',
            'label' => 'Confiance lien vers compte banni',
            'description' => 'Historique : 85 dans IdentityResolver / DecisionEngine.',
            'default' => '85', 'min' => 0, 'max' => 100, 'sensitive' => true, 'applies_to' => 'new_and_in_progress',
        ],

        // ── Sanctions ─────────────────────────────────────────────────
        'sanctions.default_suspension_minutes' => [
            'type' => 'integer', 'group' => 'sanctions', 'sort' => 1, 'unit' => 'minutes',
            'label' => 'Durée de suspension par défaut (si durée inconnue)',
            'description' => 'Utilisée uniquement comme repli. L’admin choisit toujours une durée à la saisie. Une suspension n’est jamais convertie en ban.',
            'default' => '1440', 'min' => 1, 'max' => 525600, 'sensitive' => true, 'applies_to' => 'new_only',
        ],
    ],
];
