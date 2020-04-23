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
                if($this->security->isGranted('ROLE_SUPER_ADMIN_SYSTEME')){
                    return true;
                }elseif($this->security->isGranted('ROLE_ADMIN_SYSTEME')){
                    if($subject === $user || $subject->getRoles() ===["ROLE_CAISSIER_SYSTEME"]){
                        return true;
                    }
                }elseif($this->security->isGranted('ROLE_CAISSIER_SYSTEME')){
                    if($subject === $user){
                        return true;
                    }
                }if($this->security->isGranted('ROLE_PARTENAIRE')){
                    return true;
                }
                break;
            case 'VIEW_USER':
                // logic to determine if the user can VIEW
                // return true or false
                //dd( ["ROLE_CAISSIER_SYSTEME"], $user);
                if($this->security->isGranted('ROLE_SUPER_ADMIN_SYSTEME')){
                   // dd($user);
                    return true;
                }
                if($this->security->isGranted('ROLE_ADMIN_SYSTEME')){
                    if($subject === $user || $subject->getRoles() == ["ROLE_CAISSIER_SYSTEME"] ){
                        return true;
                    }
                }

                if($this->security->isGranted('ROLE_CAISSIER_SYSTEME')){
                    if ($subject === $user) {
                        return true;
                    }
                }

                if($this->security->isGranted('ROLE_PARTENAIRE')){
                    return true;
                }elseif ($this->security->isGranted('ROLE_ADMIN_PARTENAIRE')) {
                    if(($subject === $user && $subject->getPartenaire()->getIsActive() === true)){
                        return true;
                    }elseif($subject->getRoles() === ["ROLE_USER_PARTENAIRE"]){
                        return true;
                    }
                }elseif ($subject->getPartenaire() === $user->getPartenaire()) {
                    return true;
                }
                break;
            case 'POST_USER':
                // logic to determine if the user can VIEW
                // return true or false
                if ($this->security->isGranted('ROLE_SUPER_ADMIN_SYSTEME')){
                    if($subject->getRoles() === ['ROLE_CAISSIER_SYSTEME'] || $subject->getRoles() === ['ROLE_ADMIN_SYSTEME'] || $subject->getRoles() === ['ROLE_ADMIN_PATENAIRE'] ){
                        return true;
                    }
                }elseif ($this->security->isGranted('ROLE_ADMIN_SYSTEME')) {
                    if($subject->getRoles() === ['ROLE_CAISSIER_SYSTEME']){
                        return true;
                    }
                }elseif ($this->security->isGranted(('ROLE_PARTENAIRE'))) {
                    if($subject->getRoles() === ["ROLE_ADMIN_PARTENAIRE"] || $subject->getRoles() === ["ROLE_USER_PARTENAIRE"]  ){
                        return true;
                    }
                }elseif($this->security->isGranted('ROLE_ADMIN_PARTENAIRE')){
                    if($subject->getRoles() === ["ROLE_USER_PARTENAIRE"]){
                        return true;
                    }
                }
                break;
        }

        return false;
    }
}
