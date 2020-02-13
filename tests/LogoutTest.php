<?php 

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class LogoutTest extends WebTestCase {
    
    function testLogout()
    {
        $client = static::createClient();
        $client->request('GET', '/me-deconnecter');
        $this->assertResponseRedirects("");
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

}
