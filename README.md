# Mini Twitter

Mini Twitter est une application web inspirée du célèbre réseau social, permettant aux utilisateurs de publier des tweets, de s’abonner à d’autres membres et d’interagir avec leurs publications.
Ce projet a été développé avec Symfony et une base de données MySQL.

- Installation du projet en local

# Prérequis

Avant de commencer, assurez-vous d’avoir installé sur votre machine :

PHP ≥ 8.2

Composer

Symfony CLI

MySQL

Node.js & npm (si le projet contient du front compilé avec Webpack Encore)

# Installation:

Cloner le projet :

git clone https://github.com/ton-pseudo/mini-twitter.git
cd mini-twitter

Installer les dépendances PHP :

composer install

Installer les dépendances front (si applicable) :

npm install
npm run build

# Modifier les variables de connexion à la base de données :

DATABASE_URL="mysql://user:password@127.0.0.1:3306/mini_twitter?serverVersion=8.0"

Base de données & migrations

Créer la base de données :

- php bin/console doctrine:database:create

# Lancer les migrations :

php bin/console doctrine:migrations:migrate

Création d’un utilisateur administrateur

Via une commande Symfony :

php bin/console app:create-admin


# Fonctionnalités principales

Inscription et authentification des utilisateurs

Création, modification et suppression de tweets

Like / Unlike des tweets

Système d’abonnement entre utilisateurs

Fil d’actualité personnalisé (tweets des utilisateurs suivis)

Interface d’administration (gestion des utilisateurs et des tweets)

Interface responsive adaptée aux mobiles:

# User Stories réalisées:

- 1	En tant qu’utilisateur, je peux créer un compte	
- 2	En tant qu’utilisateur, je peux me connecter et me déconnecter	
- 3	En tant qu’utilisateur connecté, je peux publier un tweet	
- 4	En tant qu’utilisateur, je peux voir les tweets des autres	
- 5	En tant qu’utilisateur, je peux suivre ou ne plus suivre quelqu’un	
- 6	En tant qu’utilisateur, je peux liker un tweet	
- 7	En tant qu’administrateur, je peux gérer les utilisateurs et les tweets	
- 8	En tant qu’utilisateur, je vois un fil d’actualité personnalisé