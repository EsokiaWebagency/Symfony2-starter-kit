======================================
Distribution Symfony standard d'Esokia
======================================

Cette application est une distribution de Symfony basée sur la Symfony 2.3 LTS `distribution standard <https://github.com/symfony/symfony-standard>`_.

Le but de cette application est de faire gagner en productivité à nos équipes en leur fournissant une application de départ pour leurs projets. 


Il ne s'agit ni d'un Bundle, ni d'un CMS, ni d'une application plug and play.
Elle n'a pas pour but de concurrencer Sonata ou les autres projets, mais juste d’etre d'un point de départ pour les projets habituels. 

Cette distribution regroupe et prépare donc les bundle indispensables à la plupart des applications basées sur symfony, et une structure de Bundle d'application pour bien commencer.

Elle fournis aussi un certain nombre de modèles pour faciliter le développement.

Bien sur c'est encore à vous de créer votre application. 


********
Features
********
    - Templates de base avec `bootstrap <http://getbootstrap.com/>`_ et `Jquery <http://jquery.com/>`_
    - Structure de base avec un bundle Front et un Bundle admin sécurisé
    - Un bundle User qui extend le Bundle FOSUserBundle avec un Crud 
    - Un Bundle Contact avec un contact form pour le front et le management des contact dans le backend
    - Les templates du Crud generators sont personnalises pour être en adéquation avec la structure du site
    - Traduction pré-installé avec les textes en anglais et en français
    - Les paramètre de base ont des valeurs par défauts
    - Les assetics sont préparés
    - Ajout d'un script d'installation
    - Liste de Bundle indispensables a tout projet sérieux, installé et paramétrés
    - Les modules NodesJs indispensable sont installés en local    


*************
Requirements
*************

En plus des `requirements <http://symfony.com/doc/current/reference/requirements.html>`_ classiques de Symfony, la distribution Esokia necessite l'installation de `NodeJs <http://nodejs.org/>`_. 
Les modules uglify et Less sont installés en local dans l'application



******************************
Bundles
******************************
Bundles préinstallés
==============================

   - `FOSUserBundle <https://github.com/FriendsOfSymfony/FOSUserBundle>`_
      Gestion des utilisateurs et de la sécurité, on ne le présente plus
   - `KnpMenuBundle <https://github.com/KnpLabs/KnpMenuBundle>`_
      Presque aussi célèbre, pour gérer les menus
   - `bootstrap-bundle <https://github.com/braincrafted/bootstrap-bundle>`_
      Beaucoup moins connu, il permet d'ajouter simplement les fonctionnalités de Bootrstap au generator, aux menus, au formulaire etc...
   -  `EoHoneypotBundle <https://github.com/eymengunay/EoHoneypotBundle>`_
      Un Bundle tres simple pour créer un champ Honeypot et diminuer le nombre de spam de manière transparente pour l'utilisateur
   - `StofDoctrineExtensionsBundle <https://github.com/stof/StofDoctrineExtensionsBundle>`_
     Indispensable timestampables, sortables et autres
   - `KnpPaginatorBundle <https://github.com/KnpLabs/KnpPaginatorBundle>`_
     Pagination et tri des listes
   - `JMSTranslationBundle <https://github.com/schmittjoh/JMSTranslationBundle>`_
     Pour simplifier la creation des fichier de traduction
   - `DoctrineFixturesBundle <http://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html>`_
     pour ajouter des données par défaut
            
  


Bundles Spécifiques
==============================

  - EsokiaFrontBundle,
    Un canvas pour l'affichage de pages non sécurisé
    Avec une gestion Ultra basique des pages texte à afficher
  - EsokiaAdminBundle,
    Un canvas pour l'affichage de pages sécurisé
  - EsokiaUserBundle
    Extend FosUserBundle et propose des fonctionnalités de base comme une liste des utilisateur, ou une interface pour la création de nouveaux utilisateurs
  - EsokiaContactBundle
    Cote front, le bundle affiche un formulaire de contact, coté admin, la liste des messages envoyés



installation
============
Voir la doc d'installation



Normes conseillées
==================
Toutes les bonnes pratiques ne sont pas réspectées dans cette distribution, mais voici quelques normes qui vous faciliteront la vie: 
Normes et best practices


Templating
==========
La distribution Esokia propose un templating utiisant bootstrap. 
Voir la documentation.


Cache
=====
Réglage du cache et comment l'appliquer efficacement sur son application


Mise en production
==================
Voir la documentation