<?php

namespace AppBundle\Tests\Security\User;

use AppBundle\Security\User\PosixUserProvider;

class PosixUserProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadUserByUsername()
    {
        $provider = new PosixUserProvider();
        $provider->loadUserByUsername('root');
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testUsernameNotFoundException()
    {
        $provider = new PosixUserProvider();
        $provider->loadUserByUsername(uniqid());
    }

    public function testSupportsClass()
    {
        $provider = new PosixUserProvider();
        $this->assertTrue($provider->supportsClass('AppBundle\Security\User\PosixUser'));
        $this->assertFalse($provider->supportsClass('Symfony\Component\Security\Core\User\User'));
    }
}
