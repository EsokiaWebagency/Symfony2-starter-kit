
======================================
Mise en production
======================================

La mise en production est toujours une étape compliquée. 
EsokiaAdminBundle intègre une commande pour vous aider. 

Mais la mise en production dépend tout de même beaucoup de la machine cliente. 

Il y aura donc toujours des taches manuelles a faire. 



1 Préparez votre application
""""""""""""""""""""""""""""""
 - supprimer les dossier présent dans app/cache
 - Supprimer tous les fichiers dans app/logs
 - Supprimer tous les dossiers dans web/bundles
 - Supprimez tous les fichiers présents dans vendors (sisi, la tache d'installation s'occupera de les installer)
 - Modifiez le Favicon.co (c'est important !)

Si votre client utilise APC, ou un autre PHP Accelerator c'est le moment de préparer les réglages: 
http://symfony.com/fr/doc/2.3/book/performance.html



Un maximum de parametres sont réglés par defaut, mais il est tout a fait possible que vous ayez a modifier des choses la main. 

2 Préparez la machine cliente
"""""""""""""""""""""""""""""

Pour éviter les soucis, préparez dès maintenant la machine en y ajoutant les modules nécéssaires au bon fonctionnement de Symfony2:
http://symfony.com/doc/2.3/reference/requirements.html



3 Script d'installation
"""""""""""""""""""""""""

Pour aider le client a mettre en place sa distribution sur son serveur, Esokia à créé une commande simple pour le guider.
L'installateur doit donc lancer 2 commandes: 

 - php composer.phar install --no-dev --optimize-autoloader
 - php app/console esokia:distribution:prod-install


La première ligne de commande va installer et optimiser les assets.
La seconde fait le reste: 

 - check requirements
 - cache warmup en prod
 - dump assets en prod
 - Obliger l'utilisateur a paramétrer: 

   - site.email: jg@jg2.com
   - database_driver: pdo_mysql
   - database_host: 127.0.0.1
   - database_port: null
   - database_name: test2
   - database_user: root
   - database_password: null
   - mailer_transport: smtp
   - mailer_host: 127.0.0.1
   - mailer_user: null
   - mailer_password: null
   - locale: en
 - générer un unique secret
 - Créer les tables en BDD 
 - assister l'utilisateur dans la création d'un administrateur
 - Sur apache : proposer d'ajouter des entête de caching pour les assets dans le .htaccess


Ce qu'il ne fait pas: 
 - il ne met pas en place les paramètres d'un accelerateur PHP
 - il ne permet pas de réglage spécifiques.
   Si vous avez de tels réglages à faire, ils ne devraient pas entrer en conflit avec ceux de la ligne de commande.
   Vous pouvez donc librement les régler avant d'envoyer l'application au client, ou retoucher la commande pour les intégrer.
 - il ne met pas en place le virtual host