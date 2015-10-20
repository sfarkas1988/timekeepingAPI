<?php

namespace AppBundle\Service;

use AppBundle\DTO\UserDTO;
use AppBundle\Entity\User;
use AppBundle\Exception\UserNotFoundException;
use AppBundle\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserService
 * @package AppBundle\Service
 */
class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $userRepository;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserManagerInterface $userManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserManagerInterface $userManager,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('AppBundle:User');
        $this->userManager = $userManager;
        $this->validator = $validator;
    }

    /**
     * @param $apiKey
     * @return \AppBundle\Entity\User
     * @throws UserNotFoundException
     */
    public function getUserByApiKey($apiKey)
    {
        $user = $this->userRepository->findOneBy(array('apiKey' => $apiKey));
        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param $username
     * @return \AppBundle\Entity\User
     * @throws UserNotFoundException
     */
    public function getUserByUsername($username)
    {
        $user = $this->userRepository->findOneBy(array('username' => $username));
        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param $email
     * @param $password
     * @return \FOS\UserBundle\Model\UserInterface
     * @throws ValidationException
     */
    public function registerUser($email, $password)
    {
        $user = $this->userManager->createUser();
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(true);

        $errors = $this->validator->validate($user, null, array('registration'));
        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }

        $this->userManager->updateUser($user);
        return UserDTO::withEntity($user);
    }

    /**
     * @param $id
     * @return UserDTO
     * @throws UserNotFoundException
     */
    public function getUserById($id)
    {
        $user = $this->userRepository->find($id);
        if ($user === null) {
            throw new UserNotFoundException();
        }

        return UserDTO::withEntity($user);
    }
}