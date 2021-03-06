<?php

namespace App\Security\Voter;

use DateTime;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;


class CompteVoter extends Voter
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
        return in_array($attribute, ['COMPTE_EDIT', 'COMPTE_VIEW'])
            && $subject instanceof \App\Entity\Compte;
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
            case 'COMPTE_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'COMPTE_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
               // foreach ($subject->getAffectations() as $key => $value) {
               //     echo($key." => ".$value->getUser()->getId());
               // }
               $date = new DateTime("now");
               if($this->security->isGranted('ROLE_PARTENAIRE') || $this->security->isGranted('ROLE_ADMIN_PARTENAIRE')){
                   if($subject->getPartenaire() === $user->getPartenaire()){
                        return true;
                   }
                
               }elseif ($this->security->isGranted('ROLE_USER_PARTENAIRE') && $subject->getPartenaire() === $user->getPartenaire()) {
                    if($subject->getAffectations()[count($subject->getAffectations()) - 1]->getUser() === $user && 
                        $subject->getAffectations()[count($subject->getAffectations()) - 1]->getAffecterEndAt() > $date){
                        // echo($subject->getAffectations()[count($subject->getAffectations()) - 1]->getAffecterEndAt()->format('Y-m-d H:i:s')." ". $date->format('Y-m-d H:i:s'));
                        return true;
                    }
               }
                
                break;
        }

        return false;
    }
}
