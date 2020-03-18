<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;


class PartenaireVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
        
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['PARTENAIRE_EDIT', 'PARTENAIRE_VIEW'])
            && $subject instanceof \App\Entity\Partenaire;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'PARTENAIRE_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                if($this->security->isGranted("ROLE_ADMIN_SYSTEME")){
                    return true;
                }
                break;
            case 'PARTENAIRE_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                if($subject->getIsActive() === true){
                    if (($subject === $user->getPartenaire() && $this->security->isGranted('ROLE_PARTENAIRE'))){
                        return true;
                    }elseif ($subject === $user->getPartenaire() && $this->security->isGranted('ROLE_ADMIN_PARTENAIRE')) {
                        return true;
                    }
                }elseif($this->security->isGranted('ROLE_ADMIN_SYSTEME')){
                    return true;
                }

                break;
        }

        return false;
    }
}
