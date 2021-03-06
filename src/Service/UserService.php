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

    public function getUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function findById(int $id): User
    {
        return $this->userRepository->findOneById($id);
    }

    public function saveChanges(User $user): void
    {
        $user->setPassword($this->encodePassword($user));
        $this->userRepository->saveChanges();
    }

    public function deleteUser(User $user): void
    {
        $this->userRepository->delete($user);
    }
}
