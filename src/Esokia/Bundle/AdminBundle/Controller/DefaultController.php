<?php

namespace Esokia\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * @Cache(expires="+1 hour" , vary={"Cookie", "Accept-Encoding", "User-Agent"})
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EsokiaAdminBundle:Default:index.html.twig');
    }
}
