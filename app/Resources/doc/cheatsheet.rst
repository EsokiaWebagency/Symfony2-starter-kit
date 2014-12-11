=========================================
Cheatsheeet commande line Symfony (CLI)
=========================================

Ce document a pour but de rassembler les lines de commandes indispensables à l'utilisation de Symfony:


Launch test server
""""""""""""""""""
Avec PHP4, vous pouvez lancer un serveur de test simplement avec la commande: 
``php app/console server:run``

Bien sur, ca ne lancera pas votre BDD



change ownership 
""""""""""""""""
Les problèmes de droits sur les dossiers sont frustrants. 
Voici les lignes de commande pour set les droits comme il faut.
Le user en exemple est celui par defaut d'une distribution AWS EC2. Modifiez le suivant vos besoins: 
Changer le propriétaire de l'ensemble des fichiers du projet:
``chown -R ec2-user``


Change folder chmod

``find /var/www/library/src -type d -exec chmod 755 {} \;``

``find /var/www/library/app/config -type d -exec chmod 755 {} \;``

``find /var/www/library/app/Resources -type d -exec chmod 755 {} \;``

``find /var/www/library/web -type d -exec chmod 755 {} \;``

``find /var/www/library/bin -type d -exec chmod 755 {} \;``

``find /var/www/library/vendor -type d -exec chmod 755 {} \;``


Change files chmod

``find /var/www/library/src -type f -exec chmod 644 {} \;``

``find /var/www/library/app/config -type f -exec chmod 644 {} \;``

``find /var/www/library/app/Resources -type f -exec chmod 644 {} \;``

``find /var/www/library/web -type f -exec chmod 644 {} \;``

``find /var/www/library/bin -type f -exec chmod 644 {} \;``

``find /var/www/library/vendor -type f -exec chmod 644 {} \;``



BDD
^^^
**Creer sa base de données:**

``php app/console doctrine:database:create``

**Voir les trequetes SQL qui seront executées lors de l'update**

``php app/console doctrine:schema:update --dump-sql``

**Update sa base de donnée**

``php app/console doctrine:schema:update --force``


Creation de fichiers
^^^^^^^^^^^^^^^^^^^^
**Creation Bundle :**

``php app/console generate:bundle``

**Génération d'un CRUD**

``php app/console generate:doctrine:crud``



Entites : 
^^^^^^^^^
**Creation d'entite**
``php app/console generate:doctrine:entity``
 
**Mise a jour des enites :**
``php app/console generate:doctrine:entities  BundleName``

``php app/console doctrine:generate:entities BundleNameBundle:Bundle``


**create form**

``php app/console generate:doctrine:form AcmeBlogBundle:Post``


**Install bundles assests:**
``php app/console assets:install``
on linux:
``php app/console assets:install web --symlink``




Deployement:
************ 

``php composer.phar dump-autoload --optimize``

**assetic:** 
``php app/console assetic:dump --env=prod``




