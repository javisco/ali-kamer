<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';

    // 1. Définir 'email' comme clé primaire à la place de 'id'
    protected $primaryKey = 'email';

    // 2. Indiquer que la clé n'est pas un entier auto-incrémenté
    public $incrementing = false;

    // 3. Spécifier le type de la clé
    protected $keyType = 'string';

    // 4. Désactiver les timestamps automatiques (updated_at n'existe pas dans cette table)
    public $timestamps = false;

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}