<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends AbstractController
{
    public function testIndex()
    {
        $this->request('/admin', 'GET', 302);
        $this->logIn("user1", "user1");

        $this->request('/admin', 'GET', 200);
    }

    public function testFormSubmit()
    {
        $client = $this->logIn("admin", "admin");
        $crawler = $client->request('GET', '/admin');
        $form = $crawler->selectButton('Filter')->form();
        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
