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

    public function save()
    {
        //$entityManager = $this->getDoctrine()->getManager();
        //$entityManager->persist($user);
        //$entityManager->flush();

    }

    public function encodePassword(User $user) : string
    {
        return $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
    }

    public function isPasswordValid(UserInterface $user, $raw)
    {
        // TODO: Implement isPasswordValid() method.
    }
}