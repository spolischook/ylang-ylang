<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class PamAuthenticator implements SimpleFormAuthenticatorInterface
{
    /** @var  array */
    protected $rootUsers;

    /**
     * @param array $rootUsers
     */
    public function __construct(array $rootUsers)
    {
        $this->rootUsers = $rootUsers;
    }
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (pam_auth($token->getUsername(), $token->getCredentials())) {
            return new UsernamePasswordToken(
                $token->getUsername(),
                $token->getCredentials(),
                $providerKey,
                in_array($token->getUsername(), $this->rootUsers) ? ['ROLE_USER', 'ROLE_ADMIN'] : ['ROLE_USER']
            );
        }

        throw new AuthenticationException(
            'Bad credentials',
            403
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
        && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
