<?php

namespace Esokia\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * @Cache(expires="+1 week", public=true, vary={"Cookie", "Accept-Encoding", "User-Agent"})
 */
class DefaultController extends Controller
{
    

    public function indexAction()
    {
        return $this->render('EsokiaFrontBundle:Default:index.html.twig');
    }
    
    /**
     * simplePageAction
     * Show a template with a page name 
     * the mor basic way to show a pseudo 'dynamique' page
     * 
     * @param string  $pageName the page name must be the name of a template of the folder Esokia\Bundle\FrontBundleview\default
     * @return template
     * @throws createNotFoundException
     */
    public function simplePageAction($pageName)
    {
        $view = "EsokiaFrontBundle:Default:".$pageName.".html.twig";
        if ( !$this->get('templating')->exists($view) ) {
           throw $this->createNotFoundException($this->get('translator')->trans('The %page% page does not exist', array('%page%' => $pageName)));
        }
        
        return $this->render($view);
    }
    
    
}
