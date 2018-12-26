<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    private $userRepository;

    private $passwordEncoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function newUser(User $user): void
    {
        $user->setPassword($this->encodePassword($user));
        if (empty($user->getRoles())) {
            $user->setRoles();
        }
        $this->persist($user);
    }
    public function persist(User $user): void
    {
        $this->userRepository->persist($user);
    }

    public function encodePassword(User $user) : string
    {
        return $this->passwordEncoder->encodePassword($user, $user->getPassword());
    }
}
