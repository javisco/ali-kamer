<?php

namespace App\Console\Commands;

use App\Services\BusinessSettingsService;
use Illuminate\Console\Command;

class SyncBusinessSettings extends Command
{
    protected $signature = 'settings:sync';

    protected $description = 'Synchronise le catalogue des paramètres métier vers platform_settings (sans écraser les valeurs existantes).';

    public function handle(BusinessSettingsService $settings): int
    {
        $count = $settings->syncFromCatalog();
        $this->info("{$count} paramètres synchronisés.");

        return self::SUCCESS;
    }
}
