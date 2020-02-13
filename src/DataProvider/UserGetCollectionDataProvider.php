<?php
namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\Security;

final class UserGetCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
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
        return User::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $em = $this->entityManager->getRepository(User::class);
        
        //$this->getUser();
        //$user = $this->security->getUser();
       // dd($this->security->getUser()->getRole()->getLibelle());
        if($this->security->getUser()->getRole()->getLibelle() ==='ROLE_ADMIN'){
            $user = $em->findBy( 
                ['partenaire' => null,'role' => 3]
            );
        }elseif($this->security->getUser()->getRole()->getLibelle() === 'ROLE_SUPER_ADMIN'){
            $user = $em->findBy( 
                ['partenaire' => null]
            );
        }
        return $user;
       
    }
}