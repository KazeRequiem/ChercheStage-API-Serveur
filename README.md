# 🚀 Projet Web - Gestion des Offres de Stage

## 📌 Présentation du projet

Les étudiants cherchent des stages via leurs réseaux et en postulant à des offres. Ce projet vise à centraliser ces offres sur une plateforme unique, facilitant ainsi la recherche de stage.

## 📅 Déroulement du projet

Le projet suit plusieurs étapes :

1. **Organisation et planification** (groupes de 4)
2. **Maquettage & Frontend** (HTML, CSS, JS)
3. **Modélisation & Base de données** (MCD, SQL)
4. **Développement Backend** (PHP, MVC, POO, PDO)

## 📜 Cahier des charges

Le site permet de :
✅ Regrouper les offres de stage 📑
✅ Gérer les entreprises 🤝
✅ Gérer les utilisateurs (admin, pilote, étudiant) 👤
✅ Filtrer les offres par compétences 🔍
✅ Postuler avec CV et lettre de motivation 📎
✅ Suivre les candidatures 📊
✅ Offrir un design responsive 📱

## 🛠️ Architecture du projet

Le projet est structuré en deux parties principales :

- **MVC** : Contient le frontend avec HTML, CSS, JavaScript et le rendu des vues.
- **API** : Gère la logique backend avec la partie middleware et la gestion des données via PDO.

## 🛠️ Qu'est-ce que PDO ?

PDO (PHP Data Objects) est une extension de PHP qui fournit une interface uniforme pour accéder aux bases de données. Elle permet :

- Une **abstraction** des bases de données (MySQL, PostgreSQL, SQLite...)
- Une meilleure **sécurité** avec l'utilisation des requêtes préparées (évite les injections SQL)
- Une **gestion des erreurs** plus propre et plus flexible
- Une **performance accrue** en optimisant l'exécution des requêtes

Dans notre projet, PDO est utilisé pour toutes les interactions avec la base de données via des classes en PHP orienté objet.

## 🛠️ Qu'est-ce qu'un Middleware ?

Un middleware est un composant qui intercepte et traite les requêtes HTTP avant qu'elles n'atteignent leur destination finale. Il est utilisé pour :

- **Gérer l'authentification** et l'autorisation des utilisateurs 🔑
- **Filtrer et valider les données** reçues dans les requêtes
- **Gérer les réponses et les erreurs** de manière centralisée ⚠️
- **Optimiser la sécurité** (éviter les attaques CSRF, injections SQL, etc.)

Dans notre projet, le middleware est une couche intermédiaire qui s'assure que seules les requêtes valides accèdent aux fonctionnalités de l'API.

## 🔧 Configuration requise

### Prérequis
- **Serveur Apache** avec support PHP
- **Base de données SQL** (MySQL, PostgreSQL, MariaDB…)
- **PHP avec PDO** activé ⚠️ **Important !**

Dans votre fichier `php.ini` (CLI), assurez-vous que cette ligne est bien décommentée :

```ini
extension=pdo_mysql
```

### 📂 Installation
1. **Cloner le dépôt** :
   ```sh
   git clone https://github.com/votre-repo.git
   cd votre-repo
   ```
2. **Configurer la base de données**
   - Importez le fichier `database.sql`
   - Modifiez `config.php` avec vos identifiants SQL
3. **Lancer le serveur**
   ```sh
   php -S localhost:8000
   ```
4. **Accéder à l'application** via [http://localhost:8000](http://localhost:8000)

## 🏗️ Stack Technique

- **Frontend (MVC)** : HTML5, CSS3, JavaScript
- **Backend (API)** : PHP (MVC, POO, PDO, PSR-12), Middleware
- **Base de données** : MySQL, MariaDB…
- **Sécurité** : Cookies, validation des entrées, protections contre injections SQL
- **Outils** : Vhost Apache, moteur de template, PWA (optionnel)

## 🏁 Objectif final

📢 **Démonstration et soutenance** : Présentation technique du projet devant un jury simulant un client.
