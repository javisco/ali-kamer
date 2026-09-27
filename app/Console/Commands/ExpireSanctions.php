<?php

namespace App\Console\Commands;

use App\Services\SanctionEngine;
use Illuminate\Console\Command;

class ExpireSanctions extends Command
{
    protected $signature = 'ali-kamer:sanctions-expire';
    protected $description = 'Expire les sanctions temporaires arrivées à échéance';

    public function handle(SanctionEngine $engine): int
    {
        $this->info('Sanctions expirées : '.$engine->expireDueSanctions());
        return self::SUCCESS;
    }
}
