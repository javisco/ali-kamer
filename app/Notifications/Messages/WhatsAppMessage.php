<?php

namespace App\Notifications\Messages;

class WhatsAppMessage
{
    protected ?string $templateName = null;
    protected string $languageCode = 'fr';
    protected array $templateParams = [];
    protected ?string $text = null;

    public static function create(): static
    {
        return new static();
    }

    // ── Message via TEMPLATE pré-approuvé (méthode principale) ────────
    // Obligatoire pour tout message initié par la plateforme (hors fenêtre
    // de 24h suivant un message du client). Le template doit être créé et
    // approuvé au préalable dans Meta Business Manager.
    //
    // $params : valeurs positionnelles pour les variables {{1}}, {{2}}...
    // du template, dans l'ordre.
    public function template(string $name, array $params = [], string $languageCode = 'fr'): static
    {
        $this->templateName   = $name;
        $this->templateParams = $params;
        $this->languageCode   = $languageCode;

        return $this;
    }

    // ── Message texte libre ────────────────────────────────────────────
    // Ne fonctionne QUE si le destinataire a écrit à ce numéro WhatsApp
    // dans les 24h précédentes (fenêtre de session Meta). À éviter pour
    // les notifications proactives — utiliser template() à la place.
    public function text(string $content): static
    {
        $this->text = $content;

        return $this;
    }

    public function isTemplate(): bool
    {
        return $this->templateName !== null;
    }

    public function toArray(): array
    {
        if ($this->isTemplate()) {
            return [
                'type'     => 'template',
                'template' => [
                    'name'     => $this->templateName,
                    'language' => ['code' => $this->languageCode],
                    'components' => empty($this->templateParams) ? [] : [[
                        'type'       => 'body',
                        'parameters' => array_map(
                            fn ($p) => ['type' => 'text', 'text' => (string) $p],
                            $this->templateParams
                        ),
                    ]],
                ],
            ];
        }

        return [
            'type' => 'text',
            'text' => ['body' => $this->text, 'preview_url' => false],
        ];
    }
}
