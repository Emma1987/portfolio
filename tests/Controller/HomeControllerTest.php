<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndexRedirects()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $locale = $client->getRequest()->getLocale();
        $redirectLocale = in_array($locale, ['fr', 'en']) ? $locale : 'fr';

        $this->assertResponseRedirects('/' . $redirectLocale . '/');
    }
}
