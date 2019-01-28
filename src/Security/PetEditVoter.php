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
        if ($action != 'edit') {
            return false;
        }

        if (!$subject instanceof Pet) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($action, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var Pet $post */
        $pet = $subject;

        return $this->canEdit($pet, $user);
    }

    private function canEdit(Pet $pet, User $user)
    {
        return ($user->getId() == $pet->getOwner()->getId() or $user->hasRole("ROLE_ADMIN"));
    }
}
