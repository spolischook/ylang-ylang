<?php

namespace AppBundle\Tests\Security;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;

class PamAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthenticateValidToken()
    {
        $pamAuthenticatorMock = $this->getPamMock(true);

        $this->assertInstanceOf('Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken', $pamAuthenticatorMock->authenticateToken(
            new UsernamePasswordToken('test', 'test', 'main', []), new InMemoryUserProvider(), 'main'
        ));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testAuthenticateNotValidToken()
    {
        $pamAuthenticatorMock = $this->getPamMock(false);

        $pamAuthenticatorMock->authenticateToken(
            new UsernamePasswordToken('test', 'test', 'main', []), new InMemoryUserProvider(), 'main'
        );
    }

    /**
     * @param bool $isValidToken
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPamMock($isValidToken)
    {
        $pamAuthenticatorMock = $this->getMockBuilder('AppBundle\Security\PamAuthenticator')
            ->setConstructorArgs([[]])
            ->setMethods(['pamAuth'])
            ->getMock();
        $pamAuthenticatorMock->method('pamAuth')->willReturn($isValidToken);

        return $pamAuthenticatorMock;
    }
}
