<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
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
        return in_array($attribute, ['EDIT_USER', 'VIEW_USER','POST_USER'])
            && $subject instanceof \App\Entity\User;
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
            case 'EDIT_USER':
                // logic to determine if the user can EDIT
                // return true or false
                if($subject === $user && $this->security->isGranted('ROLE_ADMIN')){
                    return true;
                }elseif ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    # code...
                }
                break;
            case 'VIEW_USER':
                // logic to determine if the user can VIEW
                // return true or false
                if($this->security->isGranted('ROLE_CAISSIER')){
                    if ($subject === $user) {
                        return true;
                    }
                }elseif ($this->security->isGranted('ROLE_ADMIN')) {
                    if($subject === $user){
                        return true;
                    }
                }elseif ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }elseif($this->security->isGranted('ROLE_PARTENAIRE') || $this->security->isGranted('ROLE_ADMIN_PARTENAIRE') || $this->security->isGranted('ROLE_USER_PARTENAIRE')){
                    if ($subject === $user && $subject->getPartenaire()->getIsActive() === true) {
                        return true;
                    }elseif ($subject->getPartenaire() === $user->getPartenaire()) {
                        return true;
                    }
                }
                break;
            case 'POST_USER':
                if ($this->security->isGranted('ROLE_SUPER_ADMIN')){
                    return true;
                }elseif ($this->security->isGranted('ROLE_ADMIN')) {
                    if($subject->getRole() === $user->getRole()){
                        return true;
                    }
                }
                
            break;
        }

        return false;
    }
}
