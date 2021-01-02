<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testShowIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('html h1', 'NASA API');
        $this->assertSelectorTextContains('html a.btn-primary', 'Login');
        $this->assertSelectorTextContains('html a.btn-secondary', 'Register');
    }

    public function testLogin()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('user@user.mail');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('html h2.token', 'Your Token is');
        $this->assertSelectorTextContains('html h2.expiresat', 'And expires at');
        $this->assertSelectorTextContains('html a.btn-primary', 'Logout');
        $this->assertSelectorTextContains('html a.btn-secondary', 'API');
        $this->assertSelectorTextContains('html a.btn-info', 'Regenerate Token');
    }
}