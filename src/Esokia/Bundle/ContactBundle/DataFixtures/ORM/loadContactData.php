<?php

namespace Esokia\Bundle\ContactBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Esokia\Bundle\ContactBundle\Entity\Contact;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
 
        $contact = new Contact();
        $contact->setName('Fake contact');
        $contact->setEmail('Fake@email.test');
        $contact->setIp('127.0.0.1');
        $contact->setSubject('Use this interface to view all your sent mails');
        $contact->setMessage('This is the simple way to have a small but effective email list.');
        $manager->persist($contact);
        $manager->flush();
    }
}
