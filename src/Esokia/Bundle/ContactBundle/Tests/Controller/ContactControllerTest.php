<?php

namespace Esokia\Bundle\ContactBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{

    public function testSendingEmail()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        $route = $client->getContainer()->get('router')->generate('EsokiaContactBundle_contact_new', array(), true);
        // send a test email

        $crawler = $client->request('GET', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /contact-us");

        // Fill in the form and submit it
        $form = $crawler->selectButton('Send')->form(array(
            'esokia_bundle_contactbundle_contact[name]'  => 'test name',
            'esokia_bundle_contactbundle_contact[email]'  => 'test@mytestemail.test',
            'esokia_bundle_contactbundle_contact[subject]'  => 'test subject',
            'esokia_bundle_contactbundle_contact[message]'  => 'test message',
        ));
        

       $client->submit($form);
       $crawler = $client->followRedirect();
    
       //echo $client->getResponse()->getContent();die;
        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('h4:contains("Success!")')->count(), 'h4:contains("Success!")');
       
    }

}
