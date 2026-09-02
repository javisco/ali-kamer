<?php

namespace App\Notifications\Concerns;

/**
 * Standardise la forme des données stockées en BDD pour toutes les
 * notifications, afin que le composant "cloche" côté front puisse les
 * afficher toutes de la même façon sans code spécifique par type.
 *
 * Chaque classe de notification définit databasePayload($notifiable)
 * plutôt que toDatabase() directement — ce trait s'occupe du formatage.
 */
trait HasDatabasePayload
{
    // À implémenter par chaque notification :
    // return [
    //     'title' => '...',
    //     'body'  => '...',
    //     'icon'  => 'heroicon-name ou emoji',
    //     'url'   => route(...) ou null,
    // ];
    abstract protected function databasePayload(object $notifiable): array;

    public function toDatabase(object $notifiable): array
    {
        return $this->databasePayload($notifiable);
    }

    public function toArray(object $notifiable): array
    {
        return $this->databasePayload($notifiable);
    }
}
