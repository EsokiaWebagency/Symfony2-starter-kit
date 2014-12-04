
======================================
Layouts
======================================

Le layouting applique les normes de Symfony dans l'héritage.
Il utilise le grid systeme de bootstrap3 pour la mise en forme http://getbootstrap.com/css/#grid

Si vous n’êtes pas familiers de ce framework, lisez bien la doc http://getbootstrap.com/

Les layouts utilisent la fonction `{% spaceless %} <http://twig.sensiolabs.org/doc/tags/spaceless.html>`_ de twig et le filtre trim est ajouté quand nécessaire avec son shortcut (en ajoutant - avant le rendering: {% block blockname -%})


************************
Structure
************************
La base de l'application est définie dans: 
app/Resources/views/base.html.twig

Il existe 3 layouts placés dans les Bundles.
Ils héritent tous de base.html.twig

Le bundle Admin et le Bundle front on chacun leur Layout 
src/Esokia/Bundle/AdminBundle/Resources/views/layout.hml.twig
src/Esokia/Bundle/FrontBundle/Resources/views/layout.hml.twig

Au départ, ces layouts sont rigoureusement les mêmes, seul les zones assetics changent.

Sur une application classique, il y a de grandes chances que le front end soit radicalement différent de l'admin zone. 
Vous pouvez ainsi adaptez vos chartes graphiques en fonction des besoins de votre application. 


Un dernier layout est utilisé par EsokiaUserBundle
src/Esokia/Bundle/UserBundle/Resources/views/layout.hml.twig

Il est utilisé par les formulaires de login et de resetting, 


Assetics
-----------------------

Assetic est un système puissant qui permet beaucoup de chose.
Si vous n'avez pas envie de lire la doc, en résumé (très réducteur):
En dev, Assetic permet de garder une flexibilité total sur ses assets, css et js essentiellement, en créant autant de fichiers qu'on le souhaite pour garder le code le plus modulable et le plus lisible possible.
Il modifie les fichiers pour nous éviter au maximum les problèmes de cache navigateur dans cette phase.
Au passage en mode production, assetic vas compiler tous ces fichiers pour n'en faire qu'un et peut meme les minified, voir les packed suivant les outis que vous parametrer.
Assetic peut allez beaucoup plus loin comme par exemple envoyer les fichiers compilés directement sur S3 et appeler (seulement en Prod) les liens vers ces fichiers via cloud front.



Dans la distribution Esokia, Assetic est parametre avec UglifyJs, UglifyCss et Less.
Tous les modules nodes sont installés dans l'application, il vous suffit donc d'avoir NodeJs en ligne de commande sur votre machine pour utiliser la compilation d'assetic.
Tout est préparé pour vous.
Dans un soucis de ré-usabilité, les paramètre d'assetics sont classiques. 
Les fichiers sont compilés en local dans les dossiers
/web/css/compiled/ et /web/js/compiled/


Pour utiliser assetics, vous devez ajouter les fichiers css et js dans des balises spécifiques:
Pour les CSS:
 {% stylesheets 
        'css/bootstrap.css' 
        'css/global.css' 

        filter='cssrewrite' 
        filter='?uglifycss' 
        output='css/compiled/main.css' %}
    <link rel="stylesheet" href="{{ asset_url }}" />
{% endstylesheets %}

Pour les Javascripts: 
{% javascripts 
        'js/bootstrap.js' 
        'js/global.js'  

        filter='?uglifyjs2' 
        output='js/compiled/main.js' %}
        <script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}




base.html.twig propose un premier niveau d'assets, global a toute l'application. 
C'est là que sont ajoutés les fichiers bootstrap et jQuery.
En production il génère les fichiers

 - main.css
 - main.js

Les Layouts des bundles EsokiaFrontBundle et EsokiaAdminBundle disposent aussi de leur propre assetic
ils produisent les fichiers: 

 - front.css
 - front.js
 - admin.css
 - admin.js



Cette approche à des avantages et des défauts. 

Inconvénient

 - Elle génère deux requêtes alors qu'elle ne pourrait en générer qu'une.

Avantages

 - Les assets sont plus légers car ils n'ont pas les classes et fonctions de l'autre zone
 - Les deux zones sont complètements indépendantes graphiquement ce qui nous économise beaucoup de problèmes de développements



Note: Jquery est un cas spécial
Le recompiler a tendance a engendrer des bugs. 
Il est donc appelé 'à l'ancienne' sur le CDN officiel
Si votre application doit fonctionner sans acces internet, pensez a modifier la balise: 
``<script src="//code.jquery.com/jquery-2.1.1.min.js"></script>``
Ligne 92 du fichier app/Resources/views/base.html.twig par
<script src="/js/jquery-2.1.1.min.js"></script>



Zoning
-----------------------

Twig permet de modifier les dans tous les sens.
On peut hériter de templates, includes d'autres templates et on peut modifier les zones des templates parents. 

Il faut donc prévoir une structure qui permette d'utiliser au mieux ces possibilités


Voici la structure proposé par la distribution Esokia

Representation
^^^^^^^^^^^^^^
`En image<layouting.png>`_

Description en détails
^^^^^^^^^^^^^^^^^^^^^^
Base.html.twig
**************
Le template définit les balises HTML de base selon la norme HTML5.
De haut en bas, il propose: 

 - un block title pour parametrer le titre de la page: 
   {% block title %}{{ sitename|trans }}{% endblock %}
 - un block stylesheets pour parametrer les CSS. Vous devriez toujours ajouter vos CSS dans ce block et passer par les assetics.
 - deux block {% block bodyclass %}{% endblock %} et {% block bodyid %}{% endblock %} pour ajouter des class ou un id au body de votre page
 - un block header dans lequel est définit un block rightHeader
 - Les flash messages adaptés aux classes de bootstrap
 - un block body
 - un block footer
 - un block javascript pour parametrer les JS. Vous devriez toujours ajouter vos JS dans ce block et passer par les assetics.


admin layout et front layout
****************************

Au départ, seul le contenu des blocks javascripts, css et l'appel des menus diffèrent dans ces layouts. 
Ils étendent Base.html.twig et ajoutent quelques zones au block body: 

 - l'affichage du menu sur la gauche
 - un block content_header
 - un block content





Créations d'images de zoning pour comprendre comme il faut.