<?php 

namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PagesSecuredTest extends WebTestCase
{
   
    /**
     * SetUp Authenticate
     *
     */
    public function setUp()
    {
        $this->client = static::createClient([], [
        'PHP_AUTH_USER' => 'admin@laserwars.com',
        'PHP_AUTH_PW'   => '12345678',
        ]);
    }

    /**
     * Simulate login and stock session
     *
     */
    public function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';
        $token = new UsernamePasswordToken('admin', null, $firewallName, ['ROLE_ADMIN']);
        $session->set('_security_'.$firewallName, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    /**
     * Test page Gift
     *
     */
    public function testPageGift()
    {
        $this->logIn();
        $this->client->request('GET', '/');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test  homepage 
     *
     */
    public function testPagehome()
    {
        $this->logIn();
        $this->client->request('GET', '/');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test page Admin
     *
     */
    public function testPageadmin()
    {
        $this->logIn();
        $this->client->request('GET', '/admin');
        $this->assertSame(301, $this->client->getResponse()->getStatusCode());
    }
    
}