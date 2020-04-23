<?php
namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

final class RoleGetCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
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
        return Role::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $em = $this->entityManager->getRepository(Role::class);
        //$this->getUser();
        //$user = $this->security->getUser();
       // dd($this->security->getUser()->getRole()->getLibelle());
       $roleCaissier = ['ROLE_CAISSIER_SYSTEME'];
     // dd(json_encode($roleCaissier));
      //dd($this->security->getUser()->getRoles());
        if($this->security->getUser()->getRoles() === ['ROLE_ADMIN_SYSTEME']){

            $role = $em->findByRoleLike('%CAISSIER_SYSTEME%');
            //dd($user);
            //$user = $em->findBy(
            //    ['partenaire' => null,'roles' => 'ROLE_CAISSIER']
           // );
        }elseif($this->security->getUser()->getRoles() === ['ROLE_SUPER_ADMIN_SYSTEME']){
            $role = $em->findByRoleLike('%SYSTEME%');

        }elseif ($this->security->getUser()->getRoles() === ['ROLE_PARTENAIRE']) {
           // dd($this->security->getUser()->getPartenaire()->getId());
           $role = $em->findByRoleLike('%PARTENAIRE%');

        }elseif ($this->security->getUser()->getRoles() === ['ROLE_ADMIN_PARTENAIRE']) {
            // dd($this->security->getUser()->getPartenaire()->getId());
            $role = $em->findByRoleLike('%USER_PARTENAIRE%');

         }elseif ($this->security->getUser()->getRoles() === ['ROLE_CAISSIER_SYSTEME']) {
            $return = [
                "code"=>"403",
                "content"=>"Access refuser"
              ];
              $response = new JsonResponse();
              $response->setContent(json_encode($return));
              $response->headers->set('Content-Type', 'application/json');
              $response->setStatusCode(JsonResponse::HTTP_FORBIDDEN);
              return $response;
         }
        return $role;
    }
}