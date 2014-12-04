
======================================
Installation
======================================

git clone https://github.com/EsokiaWebagency/Symfony2-starter-kit

Ouvrir la ligne de commande et aller dans le dossier de l'application puis executer la commande: 
``php app/console esokia:distribution:init``

Suivre les instructions de la commande.



************************
Installation command
************************

Cette commande est localisée dans namespace Esokia\Bundle\AdminBundle\Command\InitProjectCommand;
Cette commande sert à faciliter l'installation de base. 


Que fait la commande ?
-----------------------

La commande execute pour vous en une fois les différentes commandes classiques a lancer a l'installation de Symfony. 
Elle vous assiste egalement dans les parametrages spécifiques de la distribution.

Tout ce que la commande fait peut etre fait manuellement de la maniere classique. 
Les parametres de la distribution sont placés en premier dans le fichier app/config/parameters.yml


 0. check les requirements (php app/check.php et verification de NodeJs)
 1. Vous assiste dans le réglage des parametre de base de donnees et les parametre specifiques de la distribution Esokia
 2. Install les dependences avec php composer.phar install
 3. warmup le cache en dev (php app/console )
 4. dump assets (compile les fichiers less en CSS classiques)
 5. Ajoute les données par defaut (un user admin / admin et un message de contact de démonstration)


Ce qu'elle ne fait pas : 
-----------------------

Elle ne règle pas les paramètres de Swift déja réglés pour la plupart des servers locaux

mailer_transport: smtp
mailer_host: 127.0.0.1
mailer_user: null
mailer_password: null


elle ne fait pas le café, malheureusement ;-)