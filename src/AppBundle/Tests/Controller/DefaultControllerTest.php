<?php

namespace AppBundle\Tests\Controller;

class DefaultControllerTest extends AbstractController
{
    public function testIndex()
    {
        $crawler = $this->request('/', 'GET', 200);
        $this->assertContains('Symfony 2.7 test job', $crawler->filter('.container .page-header')->text());
    }
}
