# 🛒 Manga-wara — E-commerce Manga & Figurines

## 🎯 Objectif du projet

**Manga-wara** est une plateforme e-commerce spécialisée dans la vente de **mangas** et de **figurines**. Le site propose :

- Un catalogue de produits dynamique (mangas & figurines),
- La gestion d’un panier et de commandes sécurisées,
- Un espace utilisateur avec gestion de profil, adresses et historique de commandes,
- Un espace d’administration pour gérer les produits, utilisateurs et commentaires.

---

## 🛠️ Technologies utilisées

- **Back-end** : PHP 8+, Symfony 6
- **Front-end** : Twig, HTML5, CSS3, JavaScript
- **Base de données** : MySQL (db_mangawara)
- **ORM** : Doctrine
- **PDF** : FPDF
- **Paiement** : Stripe API
- **Outils** : Composer, Git, Symfony CLI

---

## 🚀 Instructions d’installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/johanis074/manga-wara.git
cd manga-wara
```

### 2. Installer les dépendances

```bash
composer install
```

> 💡 Si tu utilises Webpack Encore ou du JS compilé :  
> ```bash
> npm install
> ```

### 3. Configurer l’environnement

Créer un fichier `.env.local` à la racine du projet :

```dotenv
DATABASE_URL="mysql://root:password@127.0.0.1:3306/db_mangawara"
STRIPE_SECRET_KEY=sk_test_...
STRIPE_PUBLIC_KEY=pk_test_...
```

### 4. Initialiser la base de données

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

### 5. Lancer le serveur

```bash
symfony server:start
```

ou

```bash
php -S localhost:8000 -t public
```

---

## ✅ Accès rapide

- **Site client** : [http://localhost:8000](http://localhost:8000)
- **Espace admin** : [http://localhost:8000/admin](http://localhost:8000/admin)  
  **Identifiants test** : `admin@example.com` / `password`

---

## 🧪 Tests (optionnel)

> Ajouter des tests avec PHPUnit pour valider les entités, services et contrôleurs (à venir).

---

## 🌐 Déploiement (optionnel)

> Prévu pour une mise en ligne via Render, Railway ou autre plateforme cloud.

---

## 👤 Auteur

Développé par [**johanis074**](https://github.com/johanis074)