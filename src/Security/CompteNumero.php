<?php

namespace App\Security;

use App\Entity\Compte;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class CompteNumero 
{

    public function getCompteNumero()
    {
       // $date = "08/02/2020 00:00";
       // $code = date_create_from_format('d/m/Y H:i', $date)->getTimestamp();

        return time();
    }
   
}
