<?php

namespace Esokia\Bundle\UserBundle\Listener;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
/**
 * Description of geoTrackingListener
 *
 * @author user
 */
class GeoTrackingListener {
    protected $security;
    protected $doctrine;
    protected $geolocalisationService;
    
   public function __construct(SecurityContextInterface $security,  $doctrine, $geolocalisationService)
   {
      $this->security = $security;
      $this->doctrine = $doctrine;
      $this->geolocalisationService = $geolocalisationService;

   }

   
   /**
    * Set basical geo infos on user login
    * @param InteractiveLoginEvent $event
    */
   public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
   {
       
        $user = $this->security->getToken()->getUser();
       
            //get user entity
        $em = $this->doctrine->getEntityManager();
        $request = $event->getRequest();
        
        //get Geo infos
        $geoInfos = $this->geolocalisationService->getGeoInfos($request);
        

        //update user
        $user->setUserGeo($geoInfos);
        $geoInfos = json_decode($geoInfos);
        $user->setLongitude($geoInfos->longitude);
        $user->setLatitude($geoInfos->latitude);
        $user->setCountry($geoInfos->country);
        $em->persist($user);
        $em->flush();
        
        
        
   }
}
