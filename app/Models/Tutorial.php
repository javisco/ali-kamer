<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    // Catégories disponibles avec leurs libellés
    const CATEGORIES = [
        'comment-acheter'  => 'Comment acheter',
        'comment-vendre'   => 'Comment vendre',
        'paiement'         => 'Paiement Mobile Money',
        'livraison'        => 'Livraison & Agences',
        'litige'           => 'Gérer un litige',
        'compte'           => 'Gérer son compte',
    ];

    protected $fillable = [
        'title', 'type', 'role_target', 'category',
        'video_url', 'thumbnail_url', 'content',
        'duration_minutes', 'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    public function scopeForRole($q, string $role)
    {
        return $q->where(function ($query) use ($role) {
            $query->where('role_target', $role)
                  ->orWhere('role_target', 'all');
        });
    }

    public function scopeByCategory($q, string $category)
    {
        return $q->where('category', $category);
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    // Extrait l'ID YouTube depuis l'URL pour l'embed
    public function youtubeId(): ?string
    {
        if (! $this->video_url) return null;

        preg_match(
            '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            $this->video_url,
            $matches
        );

        return $matches[1] ?? null;
    }

    // URL embed YouTube
    public function embedUrl(): ?string
    {
        $id = $this->youtubeId();
        return $id ? "https://www.youtube.com/embed/{$id}" : $this->video_url;
    }
}