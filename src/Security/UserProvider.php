<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class UserProvider extends EntityUserProvider implements UserProviderInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username): User
    {
        $user = $this->userRepository->loadUserByUsername($username);
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException();
        }        if (!($reloadedUser = $this->userRepository->findOneById($user->getId()))) {
        throw new UsernameNotFoundException('User does not exist.');
    }        return $reloadedUser;
    }

    public function supportsClass($class)
    {
        return $class instanceof App\Entity\User;
    }
}