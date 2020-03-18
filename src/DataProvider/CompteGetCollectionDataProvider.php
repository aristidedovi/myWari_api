<?php
namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Compte;
use App\Entity\Partenaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

final class CompteGetCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{

    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }


    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Compte::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $em = $this->entityManager->getRepository(Compte::class);
        //$this->getUser();
        //$user = $this->security->getUser();
       // dd($this->security->getUser()->getRole()->getLibelle());
      // $roleCaissier = ['ROLE_CAISSIER_SYSTEME'];
     // dd(json_encode($roleCaissier));
      //dd($this->security->getUser()->getRoles());
        if($this->security->getUser()->getRoles() === ['ROLE_ADMIN_SYSTEME']){

            $compte = $em->findAll();
            //dd($user);
            //$user = $em->findBy(
            //    ['partenaire' => null,'roles' => 'ROLE_CAISSIER']
           // );
        }elseif($this->security->getUser()->getRoles() === ['ROLE_SUPER_ADMIN_SYSTEME']){

            $compte = $em->findAll();

        }elseif ($this->security->getUser()->getRoles() === ['ROLE_PARTENAIRE']) {
           // dd($this->security->getUser()->getPartenaire()->getId());
            $compte = $em->findBy([
                'partenaire' => $this->security->getUser()->getPartenaire()
            ]);

        }elseif ($this->security->getUser()->getRoles() === ['ROLE_ADMIN_PARTENAIRE']) {
            // dd($this->security->getUser()->getPartenaire()->getId());
            $compte = $em->findBy([
                'partenaire' => $this->security->getUser()->getPartenaire()
            ]);

         }elseif ($this->security->getUser()->getRoles() === ['ROLE_CAISSIER_SYSTEME']) {

            $compte = $em->findAll();

         }elseif ($this->security->getUser()->getRoles() === ['ROLE_USER_PARTENAIRE']) {
            $compte = $em->findBy([
                'partenaire' => $this->security->getUser()->getPartenaire()
            ]);
         }
        return $compte;
    }
}