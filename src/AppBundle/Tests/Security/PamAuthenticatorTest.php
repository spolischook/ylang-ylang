<?php

namespace AppBundle\Tests\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;

class PamAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthenticateValidToken()
    {
        $pamAuthenticatorMock = $this->getPamAuthenticatorMock(true);

        $this->assertInstanceOf('Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken', $pamAuthenticatorMock->authenticateToken(
            new UsernamePasswordToken('test', 'test', 'main', []), new InMemoryUserProvider(), 'main'
        ));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testAuthenticateNotValidToken()
    {
        $pamAuthenticatorMock = $this->getPamAuthenticatorMock(false);

        $pamAuthenticatorMock->authenticateToken(
            new UsernamePasswordToken('test', 'test', 'main', []), new InMemoryUserProvider(), 'main'
        );
    }

    public function testCreateToken()
    {
        $this->assertInstanceOf('Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken',
            $this->getPamAuthenticatorMock(true)->createToken(new Request(), 'test_login', 'test_pass', 'provider'));
    }

    public function testSupportsToken()
    {
        $pamAuthenticatorMock = $this->getPamAuthenticatorMock(false);

        $token = new UsernamePasswordToken('test_login', 'test_pass', 'provider1');
        $this->assertTrue($pamAuthenticatorMock->supportsToken($token, 'provider1'));
        $this->assertFalse($pamAuthenticatorMock->supportsToken($token, 'provider2'));

        $token = new UsernamePasswordToken('test_login', 'test_pass', 'provider2');
        $this->assertFalse($pamAuthenticatorMock->supportsToken($token, 'provider1'));
        $this->assertTrue($pamAuthenticatorMock->supportsToken($token, 'provider2'));
    }

    /**
     * @param bool $isValidToken
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPamAuthenticatorMock($isValidToken)
    {
        $pamAuthenticatorMock = $this->getMockBuilder('AppBundle\Security\PamAuthenticator')
            ->setConstructorArgs([[]])
            ->setMethods(['pamAuth'])
            ->getMock();
        $pamAuthenticatorMock->method('pamAuth')->willReturn($isValidToken);

        return $pamAuthenticatorMock;
    }
}
