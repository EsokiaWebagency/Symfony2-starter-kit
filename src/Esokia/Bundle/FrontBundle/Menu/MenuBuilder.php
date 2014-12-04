<?php

namespace Esokia\Bundle\FrontBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class MenuBuilder extends ContainerAware{


    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class' ,'nav nav-pills nav-stacked');
        $menu->addChild($this->container->get('translator')->trans('Home'),         array('route' => 'esokia_front_homepage'));
        $menu->addChild($this->container->get('translator')->trans('Contact Us'),   array('route' => 'EsokiaContactBundle_contact_new'));
        

    /*    $menu->addChild('About Me', array(
            'route' => 'page_show',
            'routeParameters' => array('id' => 42)
        ));*/
        // ... add more children

        return $menu;
    }
    
        public function footerMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class' ,'nav-pills nav-stacked nav-justified');

        $menu->addChild('© '.date('Y').' '.$this->container->getParameter('company.name'),  array('attributes' => array('class' => 'text-center')) );
        $menu->addChild($this->container->get('translator')->trans('Terms of service'),   array('route' => 'esokia_front_simple_page', 'routeParameters' => array('pageName' => 'legal')));
        
        
        $menu->addChild($this->container->get('translator')->trans('Contact Us'),   array('route' => 'EsokiaContactBundle_contact_new'));
        

    /*    $menu->addChild('About Me', array(
            'route' => 'page_show',
            'routeParameters' => array('id' => 42)
        ));*/
        // ... add more children

        return $menu;
    }
    
    
}