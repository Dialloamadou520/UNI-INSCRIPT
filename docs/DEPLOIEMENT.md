# Déploiement de la plateforme

L'application est un projet Laravel : elle a besoin d'un hébergement capable
d'exécuter **PHP 8.3** et de se connecter à une base **MySQL**. Vercel n'exécute
pas PHP nativement ; c'est pour cette raison qu'un déploiement fait « tel quel »
renvoie une erreur (404, `NOT_FOUND` ou page blanche) : la plateforme cherche un
site statique ou une fonction JavaScript et ne trouve pas de point d'entrée.

Deux voies sont possibles.

## Option A (recommandée) — un hébergeur PHP

Railway, Render, Fly.io, Heroku, Clever Cloud, ou un hébergement mutualisé
classique (Hostinger, PlanetHoster, LWS…). Le fonctionnement y est identique à
celui du poste de développement.

Étapes :

1. Créer une base MySQL chez l'hébergeur (ou chez Aiven, Railway, TiDB Cloud…).
2. Déployer le dépôt, avec la racine du site pointant sur le dossier `public/`.
3. Renseigner les variables d'environnement :

   ```env
   APP_NAME="Plateforme d'Inscription Administrative Universitaire"
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=            # généré par : php artisan key:generate --show
   APP_URL=https://votre-domaine

   DB_CONNECTION=mysql
   DB_HOST=...
   DB_PORT=3306
   DB_DATABASE=...
   DB_USERNAME=...
   DB_PASSWORD=...

   SESSION_DRIVER=database
   ```

4. Lancer une fois, après le premier déploiement :

   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   php artisan db:seed --force   # crée l'administrateur et les référentiels
   ```

Le compte administrateur créé par les seeders est `admin@universite.sn` /
`password` : **changez ce mot de passe immédiatement** après la mise en ligne.

## Option B — Vercel avec le runtime PHP communautaire

Le dépôt contient `vercel.json` et `api/index.php`, qui configurent le runtime
[`vercel-php`](https://github.com/vercel-community/php) (PHP 8.3) et redirigent
toutes les requêtes vers Laravel.

À savoir avant de choisir cette voie :

- Vercel est **serverless** : aucun disque persistant, seul `/tmp` est
  inscriptible et son contenu disparaît entre deux requêtes. Les sessions sont
  donc stockées en base (`SESSION_DRIVER=database`), et les fichiers déposés par
  les étudiants ne pourraient pas être conservés localement (il faudrait un
  stockage externe type S3).
- Il faut une **base MySQL hébergée ailleurs** (Aiven, Railway, TiDB Cloud,
  PlanetScale…), accessible depuis Internet.
- Les commandes Artisan (`migrate`, `db:seed`) ne peuvent pas être lancées sur
  Vercel : exécutez-les depuis votre machine en pointant sur la base distante.
- `vercel-php` est un runtime communautaire, non officiel.

Variables à définir dans le projet Vercel (onglet *Settings → Environment
Variables*) : `APP_KEY`, `APP_URL`, `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`,
`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`. Les autres réglages nécessaires
(chemins de cache dans `/tmp`, logs vers `stderr`) sont déjà dans `vercel.json`.
