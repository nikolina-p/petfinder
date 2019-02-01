<?php

namespace App\Security;

use App\Entity\Pet;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PetEditVoter extends Voter
{
    protected function supports($action, $subject)
    {
        return $action === 'edit' && $subject instanceof Pet;
    }

    protected function voteOnAttribute($action, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        return $this->canEdit($subject, $user);
    }

    private function canEdit(Pet $pet, User $user)
    {
        return $user->hasRole("ROLE_ADMIN") || $user->getId() === $pet->getOwner()->getId();
    }
}
