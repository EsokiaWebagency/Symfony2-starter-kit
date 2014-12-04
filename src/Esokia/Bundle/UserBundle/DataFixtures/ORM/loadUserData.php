<?php

namespace Esokia\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Esokia\Bundle\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    
        /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager =  $this->container->get('fos_user.user_manager');

        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('default@email.admin');
        $userAdmin->setRoles(array('ROLE_ADMIN'));
        $userAdmin->setEnabled(true);
        $userAdmin->setFirstname('admin');
        $userAdmin->setName('admin');

        
        
        //$userAdmin->setSalt(md5(uniqid()));

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($userAdmin)
        ;
        $userAdmin->setPassword($encoder->encodePassword('admin', $userAdmin->getSalt()));
        
        
        
        $userManager->updateUser($userAdmin, true);

    }
}
