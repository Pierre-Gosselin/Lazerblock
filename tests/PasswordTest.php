<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PasswordTest extends WebTestCase
{
    public function testCheckPassword(){

        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            '/rejoindre-la-force'
        );

        $form = $crawler->selectButton('Rejoindre la force')->form();

        $form['register[password][first]'] = 'pass1';
        $form['register[password][second]'] = 'pass2';

        $crawler = $client->submit($form);

        $this->assertEquals(1,
            $crawler->filter('li:contains("Les mots de passe doivent être identiques.")')->count()
        );
    }
}
