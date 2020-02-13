<?php 

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class LoginTest extends WebTestCase
{
    public function testLoginPage()
    {
    $client = static::createClient();
    $crawler = $client->request('GET', '/me-connecter');
    $form = $crawler->selectButton('Se connecter')->form([
        'email' => 'admin@laserwars.com',
        'password' => '12345678'
    ]);
    $client->submit($form);
    $this->assertResponseRedirects('/');    
    }
}