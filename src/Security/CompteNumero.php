<?php

namespace App\Security;

use App\Entity\Compte;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class CompteNumero 
{

    public function getCompteNumero($usernamePartenaire, $idCompte)
    {
        $code = substr($usernamePartenaire,0,2);
        $code .= $idCompte;

        return $code;
    }
   
}
