<?php


namespace Esokia\Bundle\UserBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * This service manage the geo localisation
 * It use  the Telize web service with currently no limitations (30-09-2014)
 * @link http://www.telize.com/
 * 
 * 
 */
class GeoInfoHelper
{
    private $geoApiUrl;
    private $url;
    
    public function __construct($geoApiUrl) {
        $this->geoApiUrl = $geoApiUrl;
    }
    
    
    
    
    /**
     * Construct url
     */
     private function constructUrl($ip){
         $this->url = $this->geoApiUrl.'/'.$ip;
    }

 
    
    
    
    //Connect get all infos in json
    public function getGeoInfos($request){

        //generate URL 
        $this->constructUrl($request->getClientIp());
        
        //attach
        return $this->sendToApi();
        
    }
    

    
    
    
    
    
    
    
     /**
     * Send file to API in Curl
     * @return stdClass
     */
        private function sendToApi(){
          
            $curl = curl_init($this->url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $return = curl_exec($curl);
            curl_close($curl);
          
           
          return $return;
            
    }
    
    
    
}
