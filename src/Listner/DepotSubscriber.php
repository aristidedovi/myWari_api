<?php
namespace App\Listner;

use App\Entity\Compte;
use App\Entity\Depot;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DepotSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

  //  private $passwordEncoder;
  private $em;

    public function __construct(EntityManagerInterface $em/*UserPasswordEncoderInterface $passwordEncoder*/)
    {
       // $this->passwordEncoder = $passwordEncoder;
       $this->em = $em;
    }

    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Depot) {
            return;
        }elseif ($entity instanceof Depot) {
            $this->updateCompte($entity);
        }
    }

    private function updateCompte($entity){
        $solde = $entity->getCompte()->getSolde();
        $entity->getCompte()->setSolde($solde + $entity->getMntDeposser());
        
        //echo $entity->getCompte()->getSolde();
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
       /* $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);*/
    }
}