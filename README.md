# 🧵 Tissu Artisanal — Plateforme Coopérative Marocaine

**Tissu Artisanal** est une plateforme web full-stack dédiée à la valorisation de l'artisanat textile traditionnel marocain. Elle connecte artisans, clients et administrateurs autour d'un catalogue e-commerce, d'un système de commandes, de formations artisanales et d'un back-office complet.

> 🇲🇦 Fait avec fierté au Maroc — préservation du patrimoine culturel à travers le numérique.

---

## Table des matières

1. [Idée du projet](#idée-du-project)
2. [Rôles utilisateurs](#rôles-utilisateurs)
3. [Fonctionnalités principales](#fonctionnalités-principales)
4. [Stack technique](#stack-technique)
5. [Prérequis](#prérequis)
6. [Installation pas à pas](#installation-pas-à-pas)
7. [Configuration `.env`](#configuration-env)
8. [Base de données](#base-de-données)
9. [Lancer l'application](#lancer-lapplication)
10. [URLs et comptes de démonstration](#urls-et-comptes-de-démonstration)
11. [Structure du projet](#structure-du-projet)
12. [Règles métier importantes](#règles-métier-importantes)
13. [Panel admin Filament](#panel-admin-filament)
14. [Espace artisan](#espace-artisan)
15. [Commandes utiles](#commandes-utiles)
16. [Dépannage](#dépannage)

---

## Idée du projet

Le Maroc possède un patrimoine textile exceptionnel : **tapis berbères**, **broderies fassi**, **teintures naturelles**, **djellabas**, **kilims**, **céramiques**… Pourtant, de nombreux artisans peinent à accéder aux marchés numériques et à transmettre leurs savoir-faire.

**Tissu Artisanal** répond à ce défi en proposant une **coopérative digitale** qui :

- **Met en valeur** les créations artisanales via un catalogue en ligne moderne
- **Facilite la vente** avec un panier, un checkout et un suivi des commandes
- **Forme la nouvelle génération** grâce à des ateliers et formations artisanales
- **Centralise la gestion** via un panel admin pour les équipes de la coopérative
- **Donne de l'autonomie aux artisans** avec leur propre espace de gestion (produits, commandes, formations)

L'objectif est double : **moderniser le commerce artisanal** tout en **préservant et transmettant** les techniques traditionnelles marocaines.

---

## Rôles utilisateurs

| Rôle | Description | Accès |
|------|-------------|-------|
| **Client** | Parcourt le catalogue, achète des produits, s'inscrit aux formations | Site public + profil + commandes |
| **Artisan** | Gère ses produits, consulte ses ventes et formations | Espace `/artisan/*` |
| **Admin** | Gère toute la plateforme (utilisateurs, produits, commandes, livraisons…) | Panel Filament `/admin` |

---

## Fonctionnalités principales

### Site public
- Page d'accueil avec hero, catégories, produits vedettes et formations
- Catalogue avec filtres (catégorie, prix, stock, artisans vérifiés, tri)
- Fiche produit détaillée avec galerie, avis, fiche artisan
- Liste et profils des artisans vérifiés
- Formations artisanales avec inscription en ligne
- Panier session-based (sans connexion requise pour ajouter)
- Checkout avec adresse de livraison et choix du paiement
- Historique des commandes pour les clients connectés

### Espace artisan
- Tableau de bord avec KPIs (produits, commandes, revenus, formations)
- CRUD produits (nom, catégorie, prix, stock, image…)
- Liste des formations animées

### Panel admin (Filament)
- Dashboard avec statistiques, graphique de revenus, commandes récentes
- Gestion : utilisateurs, artisans, produits, commandes, livraisons
- Formations, fournisseurs, catégories, tickets support

---

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Environnement local | [Laravel Herd](https://herd.laravel.com) |
| Backend | Laravel 13 |
| Frontend | Laravel Blade + Bootstrap 5 |
| Admin | Filament v3 |
| Base de données | PostgreSQL (Neon serverless) |
| Auth web | Laravel Sanctum (sessions) |
| ORM | Eloquent |
| Images | Laravel Storage (disque `public`) |
| Files d'attente | Laravel Queues (driver `database`) |
| Assets | Vite + npm |
| Packages JS | Bootstrap 5, Popper.js |

---

## Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- **[Laravel Herd](https://herd.laravel.com)** (PHP 8.3+, Composer, Node.js inclus)
- **Git** (optionnel, pour cloner le dépôt)
- Un compte **[Neon](https://neon.tech)** (PostgreSQL serverless) ou une instance PostgreSQL locale
- **Node.js 18+** et **npm** (inclus avec Herd)

Vérifications rapides :

```bash
php --version      # >= 8.3
composer --version
node --version
npm --version
laravel --version
```

---

## Installation pas à pas

### Étape 1 — Créer le projet (si pas déjà fait)

```bash
cd ~/Herd
laravel new tissu-artisanal
cd tissu-artisanal
```

> Si le projet existe déjà dans `~/Herd/tissu-artisanal`, passez directement à l'étape 2.

---

### Étape 2 — Installer les dépendances PHP

```bash
composer require filament/filament:"^3.0" -W
composer require laravel/sanctum
```

---

### Étape 3 — Installer et configurer Filament

```bash
php artisan filament:install --panels
```

Cela crée le panel admin à `/admin` et le fichier `app/Providers/Filament/AdminPanelProvider.php`.

---

### Étape 4 — Installer les dépendances frontend

```bash
npm install bootstrap @popperjs/core
```

Bootstrap est importé dans :

- `resources/css/app.css` → `@import 'bootstrap/dist/css/bootstrap.min.css';`
- `resources/js/app.js` → `import 'bootstrap/dist/js/bootstrap.bundle.min.js';`

---

### Étape 5 — Configurer l'environnement

Copiez le fichier d'environnement et générez la clé d'application :

```bash
cp .env.example .env
php artisan key:generate
```

Puis éditez `.env` (voir section [Configuration `.env`](#configuration-env) ci-dessous).

---

### Étape 6 — Exécuter les migrations

```bash
php artisan migrate
```

Pour repartir de zéro avec les données de démo :

```bash
php artisan migrate:fresh --seed
```

---

### Étape 7 — Peupler la base de données

```bash
php artisan db:seed
```

Les seeders créent automatiquement :
- 1 administrateur
- 6 catégories
- 5 artisans vérifiés
- 6 produits (4 vedettes)
- 3 formations
- 3 clients de démo
- 4 commandes de démonstration

---

### Étape 8 — Lier le stockage public

```bash
php artisan storage:link
```

Permet l'accès aux images uploadées via `/storage/...`.

---

### Étape 9 — Compiler les assets

**Production :**
```bash
npm run build
```

**Développement (avec rechargement à chaud) :**
```bash
npm run dev
```

---

### Étape 10 — Vérifier l'installation

Avec Laravel Herd, le site est automatiquement disponible à :

```
http://tissu-artisanal.test
```

Sans Herd :
```bash
php artisan serve
# → http://127.0.0.1:8000
```

---

## Configuration `.env`

Exemple de configuration pour PostgreSQL (Neon) :

```env
APP_NAME="Tissu Artisanal"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://tissu-artisanal.test

DB_CONNECTION=pgsql
DB_HOST=votre-hote.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
DB_SSLMODE=require

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local
```

> ⚠️ **Sécurité** : ne commitez jamais le fichier `.env` avec de vrais identifiants. Utilisez des variables d'environnement sécurisées en production.

---

## Base de données

### Schéma (15 tables)

| Table | Description |
|-------|-------------|
| `users` | Comptes (client, artisan, admin) |
| `categories` | Catégories produits (Tapis, Broderies…) |
| `artisans` | Profils artisans liés aux users |
| `fournisseurs` | Fournisseurs de matières premières |
| `products` | Produits du catalogue |
| `product_images` | Images additionnelles produits |
| `reviews` | Avis clients sur les produits |
| `commandes` | Commandes clients |
| `commande_items` | Lignes de commande |
| `livraisons` | Suivi livraison par commande |
| `formations` | Programmes de formation |
| `formation_enrollments` | Inscriptions aux formations |
| `support_tickets` | Tickets support client |

### Seeders exécutés dans l'ordre

1. `AdminSeeder`
2. `CategorySeeder`
3. `ArtisanSeeder`
4. `ProductSeeder`
5. `FormationSeeder`
6. `ClientSeeder`
7. `CommandeSeeder`

---

## Lancer l'application

### Avec Laravel Herd (recommandé)

Herd sert automatiquement le projet depuis `~/Herd/tissu-artisanal` :

| Service | URL |
|---------|-----|
| Site public | http://tissu-artisanal.test |
| Panel admin | http://tissu-artisanal.test/admin |

Lancer Vite en parallèle pour le développement frontend :

```bash
cd ~/Herd/tissu-artisanal
npm run dev
```

### Sans Herd

```bash
php artisan serve
npm run dev
```

---

## URLs et comptes de démonstration

### URLs principales

| Page | URL |
|------|-----|
| Accueil | http://tissu-artisanal.test/ |
| Catalogue | http://tissu-artisanal.test/catalogue |
| Artisans | http://tissu-artisanal.test/artisans |
| Formations | http://tissu-artisanal.test/formations |
| Panier | http://tissu-artisanal.test/panier |
| Connexion | http://tissu-artisanal.test/login |
| Inscription | http://tissu-artisanal.test/register |
| **Panel admin** | **http://tissu-artisanal.test/admin** |
| Login admin | http://tissu-artisanal.test/admin/login |
| Dashboard artisan | http://tissu-artisanal.test/artisan/dashboard |

### Comptes de démonstration

Mot de passe pour **tous** les comptes : `password`

| Rôle | Email | Accès |
|------|-------|-------|
| **Admin** | `admin@tissu.ma` | Panel `/admin` |
| **Artisan** | `fatima.ait-hassan@tissu.ma` | Dashboard `/artisan/dashboard` |
| **Artisan** | `khadija.bensouda@tissu.ma` | Dashboard artisan |
| **Client** | `youssef.benali@tissu.ma` | Catalogue + commandes |

Autres artisans seedés : `nezha.ouazzani@`, `amina.elhachimi@`, `mohamed.ziani@` (domaine `@tissu.ma`).

---

## Structure du projet

```
tissu-artisanal/
├── app/
│   ├── Filament/
│   │   ├── Resources/          # CRUD admin (Users, Products, Commandes…)
│   │   ├── Widgets/            # KPIs, graphiques, tableaux dashboard
│   │   └── Pages/              # Dashboard Filament
│   ├── Http/
│   │   ├── Controllers/        # Contrôleurs web public + Auth + Artisan
│   │   ├── Middleware/         # EnsureIsArtisan
│   │   └── Requests/           # Validation formulaires
│   ├── Models/                 # Modèles Eloquent + relations
│   ├── Helpers/                # mad_format() pour les prix MAD
│   └── Providers/
│       ├── AppServiceProvider.php   # Pagination Bootstrap, badge panier
│       └── Filament/AdminPanelProvider.php
├── database/
│   ├── migrations/             # Schéma PostgreSQL
│   └── seeders/                # Données de démonstration
├── resources/
│   ├── css/app.css             # Design system (couleurs marocaines)
│   ├── js/app.js               # Bootstrap + interactions UI
│   └── views/
│       ├── layouts/            # app.blade.php, artisan.blade.php
│       ├── components/         # product-card, formation-card, star-rating…
│       ├── home/               # Page d'accueil
│       ├── catalogue/          # Liste + fiche produit
│       ├── artisans/           # Liste + profil artisan
│       ├── formations/         # Formations
│       ├── cart/               # Panier
│       ├── commandes/          # Checkout + historique
│       ├── auth/               # Login + register
│       └── artisan/            # Dashboard artisan
├── routes/web.php              # Toutes les routes web
├── public/build/               # Assets compilés (Vite)
└── storage/app/public/         # Images uploadées
```

---

## Règles métier importantes

### Monnaie et TVA
- Tous les prix sont affichés en **MAD** (dirham marocain)
- Format : `1 200 MAD` (espace comme séparateur de milliers)
- **TVA = 20%** : `total_ttc = total_ht × 1.20`

### Catalogue public
- Seuls les produits **actifs** d'artisans **vérifiés** apparaissent
- Les nouveaux artisans inscrits ont `is_verified = false` jusqu'à validation admin

### Panier et commandes
- Panier stocké en **session** (pas besoin d'être connecté pour ajouter)
- Connexion **requise** pour passer commande
- Le stock est **décrémenté** à la validation de la commande
- Une entrée `livraison` est créée automatiquement (statut `en_attente`)

### Formations
- Inscription réservée aux utilisateurs connectés
- Vérification des places disponibles (`current_participants < max_participants`)
- Contrainte unique : un user ne peut s'inscrire qu'une fois par formation

### Sécurité
- Tous les formulaires POST/PUT utilisent `@csrf`
- Les artisans ne peuvent modifier que **leurs propres** produits
- L'accès admin est restreint au rôle `admin` via `User::canAccessPanel()`

---

## Panel admin Filament

**URL :** http://tissu-artisanal.test/admin

### Navigation

| Groupe | Ressources |
|--------|------------|
| **Principal** | Dashboard (KPIs, graphique revenus, commandes récentes, artisans en attente) |
| **Gestion** | Utilisateurs, Artisans, Produits, Commandes, Livraisons |
| **Formations** | Formations, Fournisseurs |
| **Config** | Catégories, Support |

### Widgets dashboard
- **Clients** — nombre d'utilisateurs `role=client`
- **Artisans vérifiés** — artisans avec `is_verified=true`
- **Commandes livrées** — commandes `status=livree`
- **C.A. total** — somme des `total_ttc` (format `XXX K MAD`)
- **Graphique** — revenus mensuels sur 6 mois
- **Commandes récentes** — 10 dernières commandes
- **Artisans en attente** — bouton "Valider" rapide

### Couleur de marque
Indigo `#2C3E7A` (couleur principale du panel).

---

## Espace artisan

Accessible après connexion avec un compte `role=artisan` :

| Page | URL |
|------|-----|
| Dashboard | `/artisan/dashboard` |
| Mes produits | `/artisan/products` |
| Ajouter un produit | `/artisan/products/create` |
| Mes formations | `/artisan/formations` |

Middleware : `auth` + `EnsureIsArtisan`.

---

## Commandes utiles

```bash
# Réinitialiser la BDD avec les données de démo
php artisan migrate:fresh --seed

# Vider les caches
php artisan optimize:clear

# Lister les routes
php artisan route:list

# Vérifier le statut des migrations
php artisan migrate:status

# Compiler les assets production
npm run build

# Lancer Vite en dev
npm run dev

# Créer le lien storage (images)
php artisan storage:link

# Formater le code PHP (Pint)
./vendor/bin/pint
```

---

## Dépannage

### Le site ne charge pas
- Vérifiez que Herd est actif et que le dossier est dans `~/Herd/`
- Testez : `ping tissu-artisanal.test`

### Erreur de connexion à la base de données
- Vérifiez les variables `DB_*` dans `.env`
- Pour Neon : `DB_SSLMODE=require` est obligatoire
- Testez : `php artisan migrate:status`

### Les styles/CSS ne s'appliquent pas
- Lancez `npm run dev` ou `npm run build`
- Vérifiez que `@vite(['resources/css/app.css', 'resources/js/app.js'])` est dans le layout

### Les images uploadées ne s'affichent pas
```bash
php artisan storage:link
```

### Impossible d'accéder au panel admin
- Connectez-vous avec `admin@tissu.ma` / `password`
- Seuls les users avec `role = admin` peuvent accéder à `/admin`

### Page lente au premier chargement
- Normal avec une base Neon distante (latence réseau PostgreSQL serverless)
- En local, utilisez PostgreSQL local pour de meilleures performances

---

## Design system

Palette de couleurs inspirée de l'artisanat marocain :

| Variable | Couleur | Usage |
|----------|---------|-------|
| `--or` | `#C8913A` | Accents, étoiles, boutons |
| `--indigo` | `#2C3E7A` | Hero, titres, admin |
| `--sable` | `#F5ECD7` | Fonds sections |
| `--vert` | `#2D6A4F` | Succès, livraisons |
| `--rouge` | `#A0302A` | Alertes, annulations |
| `--blanc` | `#FDFAF4` | Fond général |

Typographies :
- **Georgia** — titres et prix
- **DM Sans** — texte courant

---

## Licence

Projet éducatif / démonstration — MIT (framework Laravel).

---

© 2026 Tissu Artisanal · 🇲🇦 Coopérative Marocaine d'Artisanat Textile
