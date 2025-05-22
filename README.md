##  Billetterie Officielle des Jeux Olympiques & Paralympiques Paris 2024

Bienvenue dans le dépôt officiel de l'application de **vente de billets en ligne** pour les Jeux Olympiques et Paralympiques de Paris 2024.

## Description du projet
Ce projet a pour objectif de permettre aux utilisateurs de consulter, réserver et acheter des billets pour les événements sportifs des Jeux de Paris 2024 via une plateforme web sécurisée. Il inclut des fonctionnalités spécifiques pour les visiteurs, les utilisateurs connectés, les employés chargés de la validation des billets, ainsi que les administrateurs responsables de la gestion globale de la plateforme.

Développée avec Symfony 6 pour le back-end, MySQL pour la base de données, et une interface responsive en HTML/CSS/JavaScript/Bootstrap, cette application suit l’architecture MVC et respecte les standards de sécurité modernes.

## Utilisateurs cibles
Visiteur : peut consulter les offres et filtrer les billets.

Utilisateur connecté : peut réserver, payer, télécharger un QR code et voir son historique.

Employé : peut scanner les billets via un QR code à l’entrée des événements.

Administrateur : peut gérer les ventes, les billets, les stocks, les employés et les utilisateurs.

## Fonctionnalités principales
 Consultation des billets disponibles avec filtres et tri

 Ajout de billets au panier et visualisation du total

 Paiement sécurisé avec redirection si l'utilisateur n'est pas connecté

 Génération de QR code pour chaque billet acheté

 Historique des achats

 Espace employé avec interface de scan de billets

 Espace administrateur pour la gestion des utilisateurs, employés, stocks et statistiques

## Sécurité
L'application utilise Symfony Security avec gestion des rôles (ROLE_USER, ROLE_EMPLOYE, ROLE_ADMIN) et chiffrement des mots de passe via BCrypt. Les pages sensibles sont protégées par authentification.

## Architecture technique
Langage : Symfony 6

Base de données : MySQL

Interface utilisateur : HTML, CSS, JS, Bootstrap

Authentification : Symfony Security

QR Code : Génération à l’achat du billet

Structure du projet : MVC (Model - View - Controller)

## Fonctionnalités principales

- Inscription et connexion des utilisateurs (sécurisée)
- Navigation des événements et sélection des billets
- Ajout au panier avec différentes offres (solo, duo, familiale)
- Paiement en ligne sécurisé (Stripe)
- Génération de QR Code après achat
- Téléchargement des billets au format JPG
- Espace admin pour gestion des billets, stocks, employés et utilisateurs


## Technologies utilisées

### Front-end
- HTML5 / SCSS / Bootstrap 5
- JavaScript
- Twig (avec Symfony) 

### Back-end
- PHP 8.2 avec Symfony 6.4  
- MySQL
- Doctrine ORM (Symfony) ou JPA (Spring)

### Sécurité
- Authentification via mots de passe chiffrés (bcrypt)
- Middleware/Firewalls pour la restriction des accès
- Double clé unique lors de l’achat pour sécuriser chaque billet
- Vérification des billets via QR Code

---

## Structure du projet

```bash
olympics-ticketing/
│
├── assets/                 # Fichiers sources (SCSS, JS)
├── public/                 # Ressources accessibles publiquement
├── src/                    # Code source de l'application
├── templates/              # Templates HTML/Twig
├── config/                 # Configuration (routes, services...)
├── migrations/             # Fichiers de migration de la base de données
├── README.md               # Ce fichier
└── ...


## Lancer le projet localement

Si vous souhaitez installer le projet sur votre ordinateur pour le tester en local, il faudra :

### Vérifier que vous avez bien les pré-requis ou les installer
* Vérifier que PHP est bien installé sur votre ordinateur. Pour vérfier cela, il suffit de taper cette commande dans le terminal et de lire le résultat.
    ```bash
        php -v
    ```
    Sinon vous pouvez l'installer à partir de [cette page][6].

* Vérifier que composer est bien installé.
    ```bash
        composer -v
    ```
    Sinon vous pouvez l'installer en allant sur [cette page][7].

* [Installer Symfony CLI][2].
* Enfin, vous aurez besoin d'installer une base de données (j'ai utilisé [mysql][3] dans ce projet).

### Récupérer le projet et le copier sur votre ordinateur
* Cloner le projet
    ```bash
        git clone 
    ```
* Aller dans le dossier billetterie_2024
    ```bash
        cd billetterie_2024
    ```
* Installer les dépendances avec la commande suivante :
    ```bash
        composer install
    ``` 
* Copier la base de données pour tester l'application avec la commande suivante si vous avez installer mysql :
    ```bash
        mysql -u [NOM_UTILISATEUR] -p [NOM_DE_LA_BASE] < base_de_données.sql
    ```
### Variables d'environnements

Pour que le projet fonctionne vous aurez besoin de renseigner deux variables d'environnnements dans le fichier .env.

`DATABASE_URL`

afin que le projet puisse être lié à votre base de données.

`MAILER_DSN` 

pour l'envoi automatique des mails via l'application. Vous pouvez utiliser [mailtrap][4] qui vous permettra de vérifier si l'envoi des mails se fait correctement (il existe d'autres services identiques si vous souhaitez en utiliser un autre). 

### Lancer l'application avec le serveur local
Vous êtes enfin prêt à voir ce que l'application donne :-). 

## Démarrer le serveur local
Vérifier que tout est bien installé :

```bash
    symfony check:requirements
````
Si tout est bon, vous pouvez lancer votre serveur local avec la commande :
```bash
    symfony serve
```
Et rendez-vous sur le lien qui est écrit dans le terminal. L'adresse est du type : https://127.0.0.1:8000 Vous serez alors sur la page d'accueil de l'application. Pour vous connecter, vous pouvez utiliser le "manuel d'utilisation" qui se trouve dans le dossier "documentation".
## Déploiement avec O2switch

Pour déployer ce projet avec O2switch, il faut avoir un compte sur O2switch suivre les étapes suivantes. Vous pouvez également regardez cette [documentation][9] pour le déployement d'une application symfony.
1) Installer [O2switch CLI][8]
2) Vérifier que l'installation s'est bien faîte
    ```bash
        O2switch --version
    ```
3) S'authentifier via le terminal à O2switch
    ```bash
   -     O2switch login -i
    ```
4) Initialiser un dépôt git
    ```bash
        git init
        git add .
        git commit -m "initial import"
    ```
5) Aller dans le dossier du projet, et si c'est déjà fait entrer la commande :
    ```bash
     O2switch create
    ```
6) Créer un fichier procfile et le rajouter au dépôt distant :
    ```bash
        echo 'web: O2switch-php-apache2 public/' > Procfile
        git add Procfile
        git commit -m "O2switch Procfile"
    ```
7) Pour rajouter une base de données, vous devez passer par le site web. Je vous conseille d'installer [MySQL][10] et importer le fichier base_de_données.sql qui est présent dans le dépôt que vous avez récupéré.
8) Ensuite, installer [mailtrap][11] pour l'envoi automatique de mail.
9) Renseigner les variables d'environnements afin que l'application fonctionne sur le serveur distant via l'application web Héroku. Vous pouvez les renseigner directement depuis le terminal avec cette commande : 
    ```bash
        O2switch config:set APP_ENV=prod
    ```
    Ou alors, vous pouvez les renseigner depuis le site web.

10) Enfin pour lancer le déployement, il faut écrire cette commande :

    ```bash
        git push O2switch master
    ```
Et voilà ! 

S'il vous manque quelque chose, ou que tout ne s'est pas bien passé, vous allez recevoir un message d'erreur avec ce qu'il faut corriger. 

Si tout s'est bien passé, vous pouvez alors aller sur l'adresse du site web et tester l'application. 
## Auteur
[Serap DEVECIOGLU][5]

[1]: https://billetterie_2024_JO.fr
[2]: https://symfony.com/download
[3]: https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/
[4]: https://mailtrap.io/
[5]: https://github.com/dev-ser
[6]: https://www.php.net/manual/fr/install.php
[7]: https://getcomposer.org/download/
[8]: https://faq.o2switch.fr
[9]: https://faq.o2switch.fr/guides/php/heberger-application-symfony/
[10]: https://faq.o2switch.fr/cpanel/bases-de-donnees/phpmyadmin/
[11]: https://faq.o2switch.fr/questions/utiliser-smtp-externe/
