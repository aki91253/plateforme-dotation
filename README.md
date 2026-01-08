#README
#Plateforme de donation - Réseau Canopé
#Réalisér dans une période de stage de 2 Élèves de BTS SIO 2 année (Option SLAM), Lerda Jean-mathieu et Sorace Kevin
#Cette Application permet aux utilisateurs (Écoles, Colleges, Univerités,...) de consulter et réserver des expositions, avec une interface administrateur pour la gestion des contenus et des #réservations.
#Environnement de Développement : MAMP
#Version : 1.0
#Date : Du 5 Janvier 2026 au 13 Février 2026
#Lien du projet : https://github.com/aki91253/plateforme-dotation.git
#Lien de la documentation : https://github.com/aki91253/plateforme-dotation/blob/main/README.md

--- Aperçu de la landing page
#dans le dossier /assets/img/home.png

--- Installation
1. Cloner le projet
2. Importer la base de données
3. Lancer le projet via un serveur local (XAMPP, WAMP)
4. Accéder au projet via localhost

--- Technologies utilisées
#Cette Application Web à été développé à partir d'un framework pour le CSS : Taliwind CSS 
#Le serveur web est Apache
#Le serveur de base de données est MariaDB
#Le langage de base de données est MySQL
#Le langage de programmation est PHP et Javascript
#Les fonctions de sécurité sont stockées dans la page intitulé "security" à la racine
#les fonctions principales sont stockées dans le /assets/js/main.js
#toutes les fonctions de connexion utilisateur sont dans le /includes/auth.php

--- Fonctionnalités principales
- Consultation des expositions
- Rechercher des expositions
- ajout des dotations à la sélection
- Réservation d’une exposition
- Gestion des utilisateurs (admin)
- Gestion des expositions (admin)
- Gestion des réservations (admin)

--- Structure du projet
/  
├── assets/    
        └──CSS/
            └──style.css (style du background)
        └──img/
            └──images utlisées dans l'app (comme logo, sociaux, ...) + images des produits
        └──js/
            └──main.js (fonctions principales pour améliorer UX)
├── includes/  
        └──pages inclues dans autres pages (footer, header, sécurité, ...)  
├── admin/ 
        └──différents_pages_php_admin
├── différentes_pages_php_utilisateurs
└── README.md

--- Rôles
- Utilisateur : consulter et réserver, créer un compte, se connecter, se deconnecter, modifier son compte, contacter l'administrateur (par mail)
- Administrateur : gérer expositions et réservations
- super admin : gérer les utilisateurs et les administrateurs

