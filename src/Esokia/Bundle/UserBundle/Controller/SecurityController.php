<?php

/*
 * This file extend the one which is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 *
 */

namespace Esokia\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
	
/**
 * Change this method to override normal login action
 * 
 * Override normal login form to allow login by URL
 * 
 * @param Request $request
 * @throws AccessDeniedException
 */
    public function loginAction(Request $request)
    {  

       return parent::loginAction($request);
       
     }
        




     /**
     * Allow to renew session in ajax when persistent connection are needed
     * 
     */
    public function renewSessionAction(Request $request)
    {
        if($request->isxmlhttprequest()){
            
            $securityContext = $this->container->get('security.context');
            if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')|| $securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
                return new JsonResponse(array('check'=>'success'));
            }else{
               return new JsonResponse(array('check'=>'error', 'message'=>'logout'));
            }
            
           
        }else{
         return new JsonResponse(array('check'=>'error', 'message'=>'not allowed'));
        }
    }
    
}
