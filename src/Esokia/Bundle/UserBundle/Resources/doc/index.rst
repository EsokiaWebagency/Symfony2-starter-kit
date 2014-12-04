
===================
Esokia User Bundle
===================

Bundle de gestion des utilisateurs
Ce Bundle étend et override les templates de l'excellent `FOSUserBundle <https://github.com/FriendsOfSymfony/FOSUserBundle>`_

Son but est de fournir une interface simple de gestion utilisateur.
- Un formulaire de login intégré au template
- Un formulaire reset password intégré au template
- la gestion de base des droits utilisateurs
- les champs minimum d'un utilisateur (nom, prénom)
- Une interface d'adiministration des utilisateurs


Note: A l'installation, un utilisateur par défaut est créé avec les codes admin / admin. 
Il est bien sur impératif de changer ces credentials au plus vite.


***************
FOSUserBundle
***************
Pour ceux qui ne le connaissent pas encore, il s'agit DU bundle externe indispensable à Symfony2. 
C'est le socle qui vous permet de créer des interfaces sécurisées.
Très bien pensé et extrêmement puissant, il est assez complexe a maîtriser au départ.

Dans le Bundle EsokiaUserBundle, FOSUser est pré-paramétré pour les applications classiques, bien sur il faudra certainement le retoucher pour chaque besoin spécial du client.

***************
Security.yml
***************

FOSUser est intimement lié au fichié sucurity.yml qui se trouve dans app/config de votre application. 

C'est souvent la partie la plus pénible à mettre en place pour un nouveau projet. 

Voici la configuration de la distribution, elle devrait convenir à un grand nombre de projet, mais libre à vous de la modifier au besoin: 


security:
    encoders:
         Esokia\Bundle\UserBundle\Entity\User: 
            algorithm:            pbkdf2
            hash_algorithm:       sha512
            encode_as_base64:     true
            iterations:           34  
        
        

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]
        #special hack to access easily to the list of roles when needed
        #update this role each time you add a new role
        ROLE_ALL:         [ROLE_USER, ROLE_ADMIN]


    providers:
        fos_userbundle:
            id: fos_user.user_provider.username


    firewalls:
        dev:
            pattern:  ^/(_(profiler|wdt)|css|images|js)/
            security: false

        main:
            pattern: ^/
            form_login:
                provider: fos_userbundle
                csrf_provider: form.csrf_provider
            logout:       true
            anonymous:    true

            remember_me:
                key:        %secret% 

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: ROLE_ADMIN } 





 

***************
Front
***************

Il s'agit d'une simple implémentation du login et du reset form de FOSUserBundle


***************
Back end
***************
Un bouton en haut a gauche vous permet d'accéder à la gestion des utilisateurs de l'application. 
Il est sécurisé pour les profils PROFIL_ADMIN, ce qui signifie que les utilisateur qui pourraient accéder au back end avec d'autre profils créés par vous ne verront pas ce bouton. 

C'est un bonne exemple d’implémentation des droits au niveau des templates twigs: 
Dans 
Esokia\Bundle\AdminBundle\Controller\layout.html.twig ligne 23

{% if is_granted('ROLE_ADMIN') %}
  <a href="{{path('admin_user')}}" class="btn btn-default">{{'User'|trans}}</a>
{% endif %}


Crud User
=========

Le Bundle intègre un Crud de gestion des utilisateurs, avec édition des profils et gestion des droits pour chaque utilisateurs.

Pour la gestion des droits, il y a une astuce pour récupérer simplement la liste actuel des droits disponible.
Dans le fichier security.yml, vous avez peut être vu passer
    #special hack to access easily to the list of roles when needed
    #update this role each time you add a new role
    ROLE_ALL:         [ROLE_USER, ROLE_ADMIN]

Pensez a update ce paramètre si vous souhaitez qu'il soit disponible dans les interface de création et d'édition de l'utilisateur.




************************************************************
Bonus: Simple Geo tracking et exemple de listener
************************************************************

EsokiaUserBundle intègre un système simple de geotracking par adresse IP.
Ce système utilise deux des systemes les plus interessants de Symfony.

1. un `service <http://symfony.com/fr/doc/2.3/book/service_container.html>`_
 pour gérer l'api de l'outils de geotracking utilisé. Lisez la doc et abusez des services, c'est vraiment très pratique.
2. un `listener <http://symfony.com/fr/doc/2.3/cookbook/service_container/event_listener.html>`_
, qui va mettre a jour les coordonnées géographiques de l'utilisateur a chaque fois  qu'il se logue, en écoutant l'événement 'login' et ce complètement indépendamment du reste de l'application.
Plus complexe a appréhender, ce n'en est pas moins une fonctionnalité extrêmement puissante de Symfony2.   
