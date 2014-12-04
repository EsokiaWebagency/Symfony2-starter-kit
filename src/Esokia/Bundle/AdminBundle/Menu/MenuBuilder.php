<?php

namespace Esokia\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class MenuBuilder extends ContainerAware{


    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class' ,'list-unstyled');
        $menu->addChild('Home',   array('route' => 'esokia_admin_homepage'));
        $menu->addChild('Contacts',   array('route' => 'contact'));
        
        






    /*    $menu->addChild('About Me', array(
            'route' => 'page_show',
            'routeParameters' => array('id' => 42)
        ));*/
        // ... add more children

        return $menu;
    }
}