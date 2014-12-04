<?php

namespace Esokia\Bundle\FrontBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlControllerTest extends WebTestCase
{

    /** @dataProvider provideUrls */
public function testPageIsSuccessful($url)
{
    $client = self::createClient();
    $client->request('GET', $url);

    $this->assertTrue($client->getResponse()->isSuccessful());
}

public function provideUrls()
{
    return array(
        array('/'),
        array('/contact-us'),
        array('/login'),
        array('/content/legal'),
        // ...
    );
}

}
