# 1. Générer le pepper et l'ajouter dans .env
php artisan tinker
>>> \Illuminate\Support\Str::random(64)
# Copier le résultat dans BLACKLIST_PEPPER=...

# 2. Exécuter les migrations
php artisan migrate

# 3. Vider les caches
php artisan config:clear
php artisan cache:clear

# 4. Tester
php artisan tinker
>>> app(\App\Services\IdentityResolver::class)->resolveIdentity(email: 'test@test.cm', ip: '127.0.0.1')