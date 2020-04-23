<?php
namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
      // $roleCaissier = ['ROLE_CAISSIER_SYSTEME'];
     // dd(json_encode($roleCaissier));
      //dd($this->security->getUser()->getRoles());
        if($this->security->getUser()->getRoles() === ['ROLE_ADMIN_SYSTEME']){

            $user = $em->findByLikeRoles('%CAISSIER%');
            //dd($user);
            //$user = $em->findBy(
            //    ['partenaire' => null,'roles' => 'ROLE_CAISSIER']
           // );
        }elseif($this->security->getUser()->getRoles() === ['ROLE_SUPER_ADMIN_SYSTEME']){

            $user = $em->findAllSystemeUser('%SUPER%');

        }elseif ($this->security->getUser()->getRoles() === ['ROLE_PARTENAIRE']) {
           // dd($this->security->getUser()->getPartenaire()->getId());
            $user = $em->findAllPartenaireUser('%ROLE_PARTENAIRE%',$this->security->getUser()->getPartenaire()->getId());

        }elseif ($this->security->getUser()->getRoles() === ['ROLE_ADMIN_PARTENAIRE']) {
            // dd($this->security->getUser()->getPartenaire()->getId());
            $user = $em->findByLikeRoles('%USER%');

         }elseif ($this->security->getUser()->getRoles() === ['ROLE_CAISSIER_SYSTEME']) {

            $user = $em->findOneBy([
                'username' => $this->security->getUser()->getUsername()
            ]);
           /* $return = [
                "code"=>"403",
                "content"=>"Access refuser"
              ];
              $response = new JsonResponse();
              $response->setContent(json_encode($return));
              $response->headers->set('Content-Type', 'application/json');
              $response->setStatusCode(JsonResponse::HTTP_FORBIDDEN);
              return $response;*/
         }elseif ($this->security->getUser()->getRoles() === ['ROLE_USER_PARTENAIRE']) {

            $user = $em->findOneBy([
                'username' => $this->security->getUser()->getUsername()
            ]);
         }
        return $user;
    }
}