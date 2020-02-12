<?php

namespace App\Security;

use App\Exception\AccountDeletedException;
use App\Entity\User as AppUser;
use DateTime;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user is deleted, show a generic Account Not Found message.
       /* if ($user->isDeleted()) {
            throw new AccountDeletedException();
        }*/

        if(!$user->getIsActive()){
            throw new DisabledException('....');
        }elseif (!$user->getPartenaire()->getIsActive()) {
            throw new DisabledException('....');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
       /* if ($user->isExpired()) {
            throw new AccountExpiredException('...');
        }*/
        $date = new DateTime("now");
        if($user->getAffectations()[count($user->getAffectations()) - 1]->getAffecterEndAt() < $date){
            throw new AccountExpiredException('...');
        }
    }
}