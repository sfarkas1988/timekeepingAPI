<?php

namespace AppBundle\Security;

use AppBundle\Exception\UserNotFoundException;
use AppBundle\Service\UserService;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class ApiKeyUserProvider
 * @package AppBundle\Security
 */
class ApiKeyUserProvider implements UserProviderInterface
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param $apiKey
     * @return null|string
     */
    public function getUsernameForApiKey($apiKey)
    {
        try {
            $user = $this->userService->getUserByApiKey($apiKey);
            return $user->getUsername();
        } catch (UserNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param string $username
     * @return \AppBundle\Entity\User|null
     */
    public function loadUserByUsername($username)
    {
        try {
            $user = $this->userService->getUserByUsername($username);
            return $user;
        } catch (UserNotFoundException $e) {
            return null;
        }
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}