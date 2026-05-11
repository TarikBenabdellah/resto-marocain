<div align="center">
  
# 🍽️ Resto Marocain

### Application de Gestion de Restaurant

![Laravel](https://img.shields.io/badge/Laravel-11-red?logo=laravel&style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php&style=for-the-badge)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?logo=mysql&style=for-the-badge)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-yellow?logo=javascript&style=for-the-badge)
![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?logo=chartdotjs&style=for-the-badge)

![GitHub last commit](https://img.shields.io/github/last-commit/TarikBenabdellah/resto-marocain)
![GitHub repo size](https://img.shields.io/github/repo-size/TarikBenabdellah/resto-marocain)

</div>

---

## 📖 À propos du projet

**Resto Marocain** est une application web complète dédiée à la gestion moderne d'un restaurant marocain. Elle offre une expérience fluide aussi bien aux clients qu'aux gestionnaires.

| 💡 **Problématique** | ✅ **Solution** |
|----------------------|-----------------|
| Menus papier peu pratiques | Consultation du menu en ligne, recherche instantanée |
| Commandes mal transmises | Système de panier et validation digitale |
| Difficulté à suivre les commandes | Interface cuisine en temps réel, 3 statuts différents |
| Pas de retours clients | Système de notation (⭐ 1 à 5) et commentaires |
| Gestion manuelle des stocks et plats | Administration complète (CRUD + images) |

---

## ✨ Fonctionnalités clés

### 🧑‍🍳 **Côté Client**

| Fonctionnalité | Description |
|----------------|-------------|
| 🏠 **Page d'accueil** | Slider animé, présentation du restaurant, horaires d'ouverture |
| 🍕 **Menu interactif** | Affichage des plats par catégories (Marocain, Fast Food, Desserts, Boissons) |
| 🔍 **Recherche en temps réel** | Filtrage dynamique par nom ou description |
| 🛒 **Panier d'achat** | Ajout de plats, modification des quantités, suppression |
| 📝 **Commande** | Formulaire client (nom, téléphone), numéro de commande unique |
| ⭐ **Avis et notes** | Notation (1-5 étoiles), commentaires, modération par l'admin |

### 👑 **Côté Administrateur**

| Fonctionnalité | Description |
|----------------|-------------|
| 📊 **Dashboard statistique** | Graphiques (Chart.js) : ventes par mois, commandes par statut, top plats, jours d'affluence |
| 📦 **Gestion des commandes** | Liste des commandes, changement de statut (pending→preparing→ready→completed/cancelled) |
| 🍕 **Gestion des plats** | Ajout, modification, suppression, upload d'images, badge "recommandé" |
| ⭐ **Modération des avis** | Validation des commentaires clients avant publication |

### 🍳 **Côté Cuisine**

| Fonctionnalité | Description |
|----------------|-------------|
| 👨‍🍳 **Interface dédiée** | 3 colonnes : En attente / En préparation / Prêtes |
| 📋 **Détails des commandes** | Visualisation des plats commandés, quantités, prix |
| 🔄 **Mise à jour des statuts** | Un clic pour passer d'une étape à l'autre |

---

## 🛠️ Stack technique

| Catégorie | Technologies |
|-----------|--------------|
| **Backend** | Laravel 11, PHP 8.2 |
| **Frontend** | Blade, HTML5, CSS3, JavaScript |
| **Base de données** | MySQL |
| **Graphiques** | Chart.js |
| **Serveur local** | XAMPP (Apache + MySQL) |
| **Versionnement** | Git & GitHub |

---

## 🚀 Installation

### Prérequis

- XAMPP (Apache + MySQL)
- PHP 8.2 ou supérieur
- Composer
- Node.js (optionnel)

### Étapes d'installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/TarikBenabdellah/resto-marocain.git

# 2. Accéder au dossier
cd resto-marocain

# 3. Installer les dépendances PHP
composer install

# 4. Copier le fichier de configuration
cp .env.example .env

# 5. Générer la clé de l'application
php artisan key:generate

# 6. Démarrer le serveur
php artisan serve
```

---

## 🗄️ Base de données

### Configuration

1. **Créer la base de données** dans phpMyAdmin :
   - Nom : `restoflo_db`
   - Charset : `utf8mb4_general_ci`

2. **Configurer le fichier `.env`** :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restoflo_db
DB_USERNAME=root
DB_PASSWORD=
```

3. **Exécuter les migrations et seeders** :
```bash
php artisan migrate
php artisan db:seed
```

### Structure des tables

| Table | Description |
|-------|-------------|
| `categories` | Catégories des plats (Marocain, Fast Food, Desserts, Boissons) |
| `dishes` | Plats avec prix, description, image, note moyenne |
| `orders` | Commandes des clients |
| `order_items` | Détails des commandes (plats commandés) |
| `reviews` | Avis et notes des clients (1-5 étoiles) |
| `users` | Utilisateurs (admin) |

### Données de test incluses

Les seeders ajoutent automatiquement :
- 4 catégories (Marocain, Fast Food, Desserts, Boissons)
- 5 plats marocains (Cigan Fruit de Mer, Déo Salée, Tajine Kefta, Couscous Royal, Menu Harira)

---

## 🔐 Accès Administration

| Rôle | URL | Mot de passe |
|------|-----|--------------|
| **Administrateur** | http://127.0.0.1:8000/admin/login | `benabdellah00` |
| **Cuisine** | http://127.0.0.1:8000/kitchen | `benabdellah00` |

> ⚠️ **Pour le client** (menu, panier, commande) : accès libre sans mot de passe.

---

## 📁 Structure du projet

```
resto-marocain/
├── app/Http/Controllers/
│   ├── AdminController.php
│   ├── CartController.php
│   ├── KitchenController.php
│   ├── OrderController.php
│   └── ReviewController.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── admin/
│   ├── kitchen/
│   ├── reviews/
│   ├── cart.blade.php
│   ├── checkout.blade.php
│   ├── home.blade.php
│   └── menu.blade.php
├── routes/web.php
└── public/images/dishes/
```

---

## 👤 Auteur

**Tarik Benabdellah**

- GitHub : [@TarikBenabdellah](https://github.com/TarikBenabdellah)

---

## 📅 Année

**2024/2025**

---

## 📄 Licence

Projet développé dans un cadre professionnel. Tous droits réservés.