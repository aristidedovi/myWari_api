<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class DepotVoter extends Voter
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
        return in_array($attribute, ['DEPOT_EDIT', 'DEPOT_VIEW'])
            && $subject instanceof \App\Entity\Depot;
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
            case 'DEPOT_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'DEPOT_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                if($this->security->isGranted('ROLE_PARTENAIRE') || $this->security->isGranted('ROLE_ADMIN_PARTENAIRE')){
                    if ($subject->getCompte()->getPartenaire() === $user->getPartenaire()) {
                        return true;
                    }
                }elseif ($this->security->isGranted('ROLE_CAISSIER_SYSTEME')) {
                    return true;
                }
                break;
        }

        return false;
    }
}
