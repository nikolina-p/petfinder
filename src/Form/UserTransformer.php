<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use App\Service\UserService;

class UserTransformer implements DataTransformerInterface
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function transform($userId): ?int
    {
        return $userId;
    }

    /**
     * Transforms a int(ID) to a User object.
     */
    public function reverseTransform($userId): ?User
    {
        if (!$userId) {
            return null;
        }

        $user = $this->userService->findById($userId);

        if ($user === null) {
            throw new TransformationFailedException(sprintf(
                'An user with user ID "%s" does not exist!',
                $userId
            ));
        }

        return $user;
    }
}
