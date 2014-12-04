
===================
Esokia Admin Bundle
===================

Bundle sécurisé derrière l'url /admin/
Il a pour but de regrouper l'affichage de l'espace sécurisé et les actions générales de cet espaces. 

***************
Layout
***************

Le bundle Dispose de son propre template layout.html.twig qui extend base.html.twig.
Il peut donc être modifié indépendamment du reste de l'application

views/layout.html.twig

***************
Menu
***************
Le Bundle intègre un builder pour le menu de l'administration dans
Menu/MenuBuilder.php

***************
Command
***************
Le Bundle Admin accueil la commande d'initialisation du projet. 
Cette commande est un bon exemple pour créer vos propres commandes.

Command/InitProjectCommand.php
