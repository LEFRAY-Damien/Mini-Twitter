# Mini Twitter

Mini Twitter est une application web inspirée du célèbre réseau social, permettant aux utilisateurs de publier des tweets, de s’abonner à d’autres membres et d’interagir avec leurs publications.
Ce projet a été développé avec Symfony et une base de données MySQL.

- Installation du projet en local

# Prérequis

Avant de commencer, assurez-vous d’avoir installé sur votre machine :

- PHP ≥ 8.2

- Composer

- Symfony CLI

- MySQL


# Installation:

Cloner le projet :
```bash
git clone https://github.com/LEFRAY-Damien/Mini-Twitter
cd mini-twitter
```

### Installer les dépendances PHP :

``composer install``


# Modifier les variables de connexion à la base de données :

```DATABASE_URL="mysql://user:password@127.0.0.1:3306/mini_twitter?serverVersion=8.0"```

### Base de données & migrations

### Créer la base de données :

``php bin/console doctrine:database:create``

# Lancer les migrations :

``php bin/console doctrine:migrations:migrate``

### Création d’un utilisateur administrateur

- Via une commande Symfony :

``php bin/console app:create-admin``


# Fonctionnalités principales

- Inscription et authentification des utilisateurs

- Création, modification et suppression de tweets

- Like / Unlike des tweets

- Système d’abonnement entre utilisateurs

- Fil d’actualité personnalisé (tweets des utilisateurs suivis)

- Interface d’administration (gestion des utilisateurs, des tweets et des signalements)

- Interface responsive adaptée aux mobiles:

# User Stories réalisées:

- 1	En tant qu’utilisateur, je peux créer un compte	
- 2	En tant qu’utilisateur, je peux me connecter et me déconnecter	
- 3	En tant qu’utilisateur connecté, je peux publier un tweet	
- 4	En tant qu’utilisateur, je peux voir les tweets des autres	
- 5	En tant qu’utilisateur, je peux suivre ou ne plus suivre quelqu’un	
- 6	En tant qu’utilisateur, je peux liker un tweet
- 7	En tant qu’utilisateur, je vois un fil d’actualité personnalisé
- 8	En tant qu’administrateur, je peux gérer les tweets
- 9	En tant qu’administrateur, je peux gérer les signalements
- 10 En tant qu’administrateur, je peux bannir/susprendre
- 11 En tant qu'administrateur, je peux supprimer les tweets et les signalements


## Structure du projet
```
└── 📁src
    └── 📁Command
        ├── CreateAdminCommand.php
    └── 📁Controller
        ├── .gitignore
        ├── AdminController.php
        ├── FollowController.php
        ├── HomeController.php
        ├── LikeController.php
        ├── ProfileController.php
        ├── RegistrationController.php
        ├── RepliesController.php
        ├── ReportController.php
        ├── RetweetController.php
        ├── SecurityController.php
        ├── TweetController.php
    └── 📁Entity
        ├── .gitignore
        ├── Follow.php
        ├── Like.php
        ├── Replies.php
        ├── Report.php
        ├── Retweet.php
        ├── Tweet.php
        ├── User.php
    └── 📁Form
        ├── LikeType.php
        ├── LoginFormType.php
        ├── RegistrationFormType.php
        ├── RepliesType.php
        ├── ReportType.php
        ├── RetweetType.php
        ├── SearchType.php
        ├── TweetType.php
        ├── UserType.php
    └── 📁Model
        ├── SearchData.php
    └── 📁Repository
        ├── .gitignore
        ├── FollowRepository.php
        ├── LikeRepository.php
        ├── RepliesRepository.php
        ├── ReportRepository.php
        ├── RetweetRepository.php
        ├── TweetRepository.php
        ├── UserRepository.php
    └── 📁Security
        ├── UserChecker.php
    └── Kernel.php
```

```
└── 📁templates
    └── 📁admin
        ├── adminTweet.html.twig
        ├── adminUser.html.twig
        ├── index.html.twig
    └── 📁bundles
        └── 📁TwigBundle
            └── 📁Exception
                ├── error.html.twig
    └── 📁components
        ├── _search_data.html.twig
    └── 📁follow
        ├── index.html.twig
    └── 📁home
        ├── index.html.twig
    └── 📁like
        ├── _delete_form.html.twig
        ├── _form.html.twig
        ├── edit.html.twig
        ├── index.html.twig
        ├── new.html.twig
        ├── show.html.twig
    └── 📁profile
        ├── followers.html.twig
        ├── following.html.twig
        ├── profile_edit.html.twig
        ├── profile_retweets.html.twig
        ├── profile_tweets.html.twig
        ├── profile_view.html.twig
    └── 📁registration
        ├── register.html.twig
    └── 📁replies
        ├── _delete_form.html.twig
        ├── _form.html.twig
        ├── edit.html.twig
        ├── index.html.twig
        ├── new.html.twig
        ├── show.html.twig
    └── 📁report
        ├── index.html.twig
    └── 📁retweet
        ├── _delete_form.html.twig
        ├── _form.html.twig
        ├── edit.html.twig
        ├── index.html.twig
        ├── new.html.twig
        ├── show.html.twig
    └── 📁security
        ├── login.html.twig
    └── 📁sidebar
        ├── sidebar.html.twig
    └── 📁tweet
        ├── _delete_form.html.twig
        ├── _form.html.twig
        ├── edit.html.twig
        ├── index.html.twig
        ├── new.html.twig
        ├── show.html.twig
    └── base.html.twig
```

### 

Crédit by :
- [@Noam](https://github.com/Noam72T)
- [@Damien](https://github.com/LEFRAY-Damien)
- [@Kyllian](https://github.com/KyllianLerousseau)
- [@Vincent](https://github.com/VincentLeducArinfo)
- [@JohnDoe](https://github.com/Sulayman2005)