<?php

namespace App\Security;

use App\Entity\Pet;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PetVoter extends Voter
{
    //possible actions:
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($action, $subject)
    {
        if (!in_array($action, array(self::VIEW, self::EDIT))) {
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

        switch ($action) {
            case self::VIEW:
                return $this->canView($pet, $user);
            case self::EDIT:
                return $this->canEdit($pet, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Pet $pet, User $user)
    {
        //everybody can view Pet
        return true;
    }

    private function canEdit(Pet $pet, User $user)
    {
        return ($user->getId() == $pet->getOwner()->getId() or $user->hasRole("ROLE_ADMIN"));
    }
}
