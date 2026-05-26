# VoyageVista 2026

Projet web dynamique en PHP natif + MySQL + PDO pour la démonstration VoyageVista.

## Fonctionnalités principales

- navigation entre destinations, transports, hébergements, activités, favoris, panier, profil et admin
- inscription, connexion, déconnexion et gestion des rôles `voyageur` / `admin`
- catalogue MySQL avec recherche et filtres
- composition d’un séjour depuis `circuit.php`
- panier en session avec modification, suppression et validation simulée
- création de réservation en base + historique utilisateur
- notifications utilisateur avec marquage comme lu
- tableau de bord admin avec stats, utilisateurs et gestion simple des destinations

## Prérequis

- PHP 8.0+ recommandé
- MySQL / MariaDB
- WAMP, MAMP ou `php -S`

## Installation

1. Créez une base MySQL locale accessible avec l’utilisateur configuré dans `config/database.php`.
   Valeurs par défaut :
   - hôte : `127.0.0.1`
   - port : `3306`
   - base : `voyagevista`
   - utilisateur : `root`
   - mot de passe : vide

2. Importez le script SQL :

```sql
SOURCE database/voyagevista.sql;
```

3. Lancez le projet.

Avec WAMP / MAMP :
- placez le dossier dans le répertoire web habituel
- ouvrez `http://localhost/Projet-VoyageVista-2026/`

Avec le serveur PHP intégré :

```bash
php -S localhost:8000
```

Puis ouvrez :

```text
http://localhost:8000/index.php
```

## Comptes de test

- admin : `admin@voyagevista.fr` / `admin123`
- voyageur : `test@voyagevista.fr` / `test123`

## Parcours de démo conseillé

1. Aller sur `Destinations` puis utiliser recherche + filtres.
2. Ouvrir un `circuit` depuis une destination.
3. Ajouter un transport, un hébergement et une activité au panier.
4. Ouvrir `Panier`, modifier une quantité, puis valider la réservation simulée.
5. Aller sur `Profil` pour voir l’historique et les notifications.
6. Se connecter en admin pour ouvrir `Admin`, voir les stats et changer un rôle utilisateur.

## Remarques techniques

- la connexion PDO est centralisée dans `config/database.php`
- les protections minimales demandées sont en place : `password_hash`, `password_verify`, requêtes préparées, échappement HTML, recalcul serveur des prix
- si MySQL n’est pas accessible, les pages dynamiques affichent un message propre au lieu de casser l’interface
