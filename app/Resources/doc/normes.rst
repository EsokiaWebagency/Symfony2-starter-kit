
======================================
Normes et best practices
======================================

La distribution ne respecte pas toutes les nouvelles best practices de Symfony. 
Elle cherche surtout à coller le plus possible aux documentation qu'on trouve le plus facilement en ligne. 


Voici quelques conseils pour améliorer votre expérience de développement. 


Annotation VS YML
""""""""""""""""""
A l'usage, j'utilise les annotations pour les paramètres de mes entités, et les fichiers YML pour le reste (routing, services etc)





Services
""""""""

Les services sont un système très sympa de symfony pour la ré-utilisabilité de voter codes
http://symfony.com/fr/doc/2.3/book/service_container.html

**Usez et abusez des services!**

Je vous conseil de créer un service a chaque fois que vous devez interfacer votre application avec une API externe (un service flickr par exemple)
et a chaque fois que votre code doit être facilement accessible à de nombreux endroits de l'application (un service Utils ou Tools par exemple)




Lazy load
"""""""""


**NE VOUS LAISSER PAS AVOIR PAR LE LAZY LOAD**

Doctrine applique le principe du Lazy load. Donc si vous ne demandez pas l’affichage d’une entité, elle n’est pas chargé au départ.
Cette méthode a des avantages, mais peut être dangereuse dans les listes. 
Explications: 
Prenons une entité Plan Road, qui est liée a Road.
Si vous voulez afficher une liste de plans, vous avez récupéré toutes les entités Plan, mais PAS les entités Road liées. 
Donc si vous affichez dans les lignes $plan->getName() et $plan->getRoad()->getName(), a chaque ligne, doctrine va faire une requete pour récuperer RoadContent. 
Donc 1000 lignes = 1000 requetes… c’est pas top. 

Les relation 1-1, n’utilisent pas le lazy load, mais les relations 1-n et n-n l’utilisent par defaut. 
Suivant les cas, vous pouvez ajouter une annotation pour obliger doctrine a faire un join a chaque requete: 
``* @ORM\JoinColumn(name="providerId", referencedColumnName="id")``

Ou mieux faire la requete vous même dans un repository



Requetes
^^^^^^^
Utilisez au maximum les requetes préféfinies. 
 - find
 - findAll
 - findBy
 - findOneBy




Annexes
^^^^^^^

Je vous conseil également la lecture assidue de ces articles: 
 - `Official symfony best practices <http://symfony.com/doc/2.3/best_practices/index.html>`_

