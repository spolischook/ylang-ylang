<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
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

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return UsernamePasswordToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (true === $this->pamAuth($token->getUsername(), $token->getCredentials())) {
            return $token;
        }

        throw new AuthenticationException(
            'Bad credentials',
            403
        );
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
        && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param Request $request
     * @param $username
     * @param $password
     * @param $providerKey
     * @return UsernamePasswordToken
     */
    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey, in_array($username, $this->rootUsers) ? ['ROLE_USER', 'ROLE_ADMIN'] : ['ROLE_USER']);
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function pamAuth($username, $password)
    {
        return pam_auth($username, $password);
    }
}
