# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Projet

**MediCare Pro** — Système de gestion hospitalière. Migration depuis HTML/JS vanilla vers Laravel 9.

- **URL locale** : `http://localhost/hopital/public/`
- **Base de données** : `hopital_medicare` (MySQL)
- **Stack** : Laravel 9, Laravel Breeze (auth), PHP 8.x, CSS custom (`public/css/style.css`), Vite, Alpine.js

## Commandes

```bash
# Développement
php artisan serve                        # serveur de dev
npm run dev                              # assets Vite (watch)
npm run build                            # build assets production

# Base de données
php artisan migrate                      # appliquer les migrations
php artisan db:seed                      # seeder les données de démo
php artisan migrate:fresh --seed         # reset complet + seed

# Tests
php artisan test                         # tous les tests
php artisan test --filter NomDuTest      # test ciblé
```

## Architecture

### Modules et routes

Chaque module correspond à un préfixe URL, un namespace de controllers, et un rôle utilisateur :

| Module | Préfixe | Rôle requis |
|--------|---------|-------------|
| Admin | `/admin` | `admin` |
| Réception | `/reception` | `reception`, `admin` |
| Médecin | `/medecin` | `medecin`, `admin` |
| Caisse | `/caisse` | `caisse`, `admin` |
| Pharmacie | `/pharmacie` | `pharmacie`, `admin` |

Le contrôle d'accès passe par `CheckRole` middleware (`app/Http/Middleware/CheckRole.php`), enregistré sous l'alias `role:` dans le Kernel. Les routes sont définies dans `routes/web.php` avec des groupes `middleware(['auth', 'role:xxx'])`.

### Layout et vues

Le layout principal est `resources/views/layouts/medicare.blade.php`. Il expose `@yield('sidebar-nav')` — chaque module injecte son propre partial sidebar (`resources/views/{module}/_sidebar.blade.php`).

Le **dashboard** (`resources/views/dashboard.blade.php`) est la seule vue sans sidebar : c'est un sélecteur de modules avec accès grisés selon le rôle.

### Flux métier principal

```
Réception → crée Consultation (statut: en_attente)
         → Médecin démarre consultation (statut: en_cours)
         → Médecin soumet FicheTraitement + actes médicaux
           └─ FicheTraitementController::store() crée automatiquement la Facture si total_facturable > 0
         → Médecin émet Ordonnance
         → Pharmacie prépare/remet l'ordonnance (décrément stock)
         → Caisse encaisse la Facture (statut: payee, crée Paiement + Transaction)
```

### Modèles clés

- **`Consultation`** : pivot central. Relie `Patient`, `Medecin`, `FicheTraitement`, `Ordonnance`, `Facture`, `FileAttente`.
- **`FicheTraitement`** : liée à une `Consultation`. Contient les actes via pivot `fiche_traitement_actes` (colonnes pivot : `nom`, `prix`, `quantite`, `facturable`). Méthode `actes()` = alias de `actesMedicaux()`.
- **`Facture`** : générée automatiquement par `FicheTraitementController`. L'accessor `montant_total` préfère `lignes` si chargées, sinon retourne `montant` — toujours eager-loader `lignes` pour éviter une valeur incorrecte.
- **`OrdonnanceMedicament`** : stocke le `nom` du médicament en string (pas de FK `medicament_id`).
- **`Medecin`** : lié à `User` via `user_id`. `getMedecin()` dans les controllers utilise `auth()->user()->medecin` avec fallback sur `Medecin::first()` pour le mode démo.

### Seeders (ordre obligatoire)

Le `DatabaseSeeder` appelle les seeders dans un ordre précis (dépendances entre tables). Le `UserSeeder` crée les comptes de démo en dernier.

Comptes de démo : `admin@medicare.ci`, `reception@medicare.ci`, `medecin@medicare.ci`, `caisse@medicare.ci`, `pharmacie@medicare.ci` — tous avec le mot de passe `password`.

## Après chaque modification

Documenter dans `historique.md` : phase numérotée, fichiers modifiés, nouvelles routes, correctifs.
