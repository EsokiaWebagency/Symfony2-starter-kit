
===================
Esokia Front Bundle
===================

Bundle Public derrière l'url /
Il a pour but de regrouper l'affichage de l'espace public et les actions générales de cet espaces. 
De fournir une home page au projet

***************
Layout
***************
Le bundle Dispose de son propre template layout.html.twig qui extend base.html.twig.
Il peut donc être modifié indépendamment du reste de l'application

views/layout.html.twig

***************
Menu
***************
Le Bundle intègre un builder pour le menu du front end dans
Menu/MenuBuilder.php

Ce builder crée 2 menus:
**mainMenu**, affiché a gauche de toutes les pages du bundle
**footerMenu**, affiché en bas de toutes les pages



***************
Simple page
***************

Une methode permet l'affichage type CMS le plus basic du monde
Il est utilisé par exemple pour appelé le template des conditions générales

Il suffit de passer le nom d'un template twig du dossier views/default en paramettre: 

public function simplePageAction($pageName)
{
    $view = "EsokiaFrontBundle:Default:".$pageName.".html.twig";
    if ( !$this->get('templating')->exists($view) ) {
        throw $this->createNotFoundException($this->get('translator')->trans('The %page% page does not exist', array('%page%' => $pageName)));
    }
    return $this->render($view);
}
    





***************
Cache
***************
Le controller utilise les annotations pour le cache avec comme paramètres par défaut: 

``@Cache(expires="+1 week", public=true, vary={"Cookie", "Accept-Encoding", "User-Agent"})``

Ces paramètres conviennent à la plupart des applications, mais il est conseillé de régler le cache avec soin en fonction de ses besoins. 