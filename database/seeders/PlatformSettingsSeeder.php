<?php

namespace Database\Seeders;

use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlatformSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // ── Commissions et frais ──────────────────────────────────
            [
                'key'         => 'protection_rate',
                'value'       => '2',
                'type'        => 'percentage',
                'label'       => 'Frais de protection acheteur',
                'description' => 'Pourcentage prélevé sur le montant de la commande. Payé par l\'acheteur.',
                'group'       => 'commissions',
                'sort_order'  => 1,
            ],
            [
                'key'         => 'gateway_collect_rate',
                'value'       => '2',
                'type'        => 'percentage',
                'label'       => 'Frais Campay collecte',
                'description' => 'Frais Mobile Money à l\'encaissement. Payé par l\'acheteur.',
                'group'       => 'commissions',
                'sort_order'  => 2,
            ],
            [
                'key'         => 'platform_commission_rate',
                'value'       => '5',
                'type'        => 'percentage',
                'label'       => 'Commission plateforme',
                'description' => 'Commission prélevée sur le montant net du vendeur.',
                'group'       => 'commissions',
                'sort_order'  => 3,
            ],
            [
                'key'         => 'agency_commission_rate',
                'value'       => '1',
                'type'        => 'percentage',
                'label'       => 'Commission agences partenaires',
                'description' => 'Reversé aux agences de voyage pour chaque colis traité.',
                'group'       => 'commissions',
                'sort_order'  => 4,
            ],
            [
                'key'         => 'gateway_payout_rate',
                'value'       => '1',
                'type'        => 'percentage',
                'label'       => 'Frais Campay retrait vendeur',
                'description' => 'Frais Mobile Money au décaissement vers le vendeur.',
                'group'       => 'commissions',
                'sort_order'  => 5,
            ],

            // ── Timers ────────────────────────────────────────────────
            [
                'key'         => 'auto_complete_hours',
                'value'       => '72',
                'type'        => 'integer',
                'label'       => 'Délai auto-complétion (heures)',
                'description' => 'Si l\'acheteur ne retire pas le colis dans ce délai, la commande est auto-complétée.',
                'group'       => 'timers',
                'sort_order'  => 1,
            ],
            [
                'key'         => 'transport_payment_hours',
                'value'       => '24',
                'type'        => 'integer',
                'label'       => 'Délai paiement frais transport (heures)',
                'description' => 'Si l\'acheteur ne paie pas les frais transport dans ce délai, la commande est annulée.',
                'group'       => 'timers',
                'sort_order'  => 2,
            ],
            [
                'key'         => 'seller_shipping_warning_hours',
                'value'       => '48',
                'type'        => 'integer',
                'label'       => 'Alerte vendeur non-expéditeur (heures)',
                'description' => 'Délai après paiement avant d\'avertir le vendeur qu\'il n\'a pas expédié.',
                'group'       => 'timers',
                'sort_order'  => 3,
            ],

            // ── Limites ───────────────────────────────────────────────
            [
                'key'         => 'min_withdrawal_amount',
                'value'       => '1000',
                'type'        => 'integer',
                'label'       => 'Retrait minimum (FCFA)',
                'description' => 'Montant minimum pour qu\'un vendeur puisse effectuer un retrait.',
                'group'       => 'limites',
                'sort_order'  => 1,
            ],
            [
                'key'         => 'min_product_price',
                'value'       => '100',
                'type'        => 'integer',
                'label'       => 'Prix minimum produit (FCFA)',
                'description' => 'Prix minimum qu\'un vendeur peut fixer pour un produit.',
                'group'       => 'limites',
                'sort_order'  => 2,
            ],
            [
                'key'         => 'max_product_price',
                'value'       => '10000000',
                'type'        => 'integer',
                'label'       => 'Prix maximum produit (FCFA)',
                'description' => 'Prix maximum qu\'un vendeur peut fixer pour un produit.',
                'group'       => 'limites',
                'sort_order'  => 3,
            ],
            [
                'key'         => 'trust_score_prepayment_threshold',
                'value'       => '30',
                'type'        => 'integer',
                'label'       => 'Seuil score confiance prépaiement',
                'description' => 'Si le trust score d\'un acheteur est inférieur à ce seuil, le prépaiement est obligatoire.',
                'group'       => 'limites',
                'sort_order'  => 4,
            ],
        ];

        foreach ($settings as $setting) {
            PlatformSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Paramètres plateforme initialisés.');
    }
}
