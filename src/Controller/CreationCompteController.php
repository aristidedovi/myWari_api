<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Depot;
use App\Entity\Partenaire;
use App\Entity\Role;
use App\Entity\User;
use App\Security\CompteNumero;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreationCompteController extends AbstractController
{
   private $userPasswordEncoder;
 
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
      $this->userPasswordEncoder = $userPasswordEncoder;
    
    }

    public function compteAvecNewPartenaire($json){
      $em = $this->getDoctrine()->getManager();
      $user = new User();
          $user->setUsername($json->partenaire->user->username);
          $user->setPassword($this->userPasswordEncoder->encodePassword($user,$json->partenaire->user->password));
          $user->setRoles($json->partenaire->user->roles); /*45054424394318*/
  
          $role = $json->partenaire->user->role_id;
          $repo = $this->getDoctrine()->getRepository(Role::class);
          $role = $repo->find($role);
          $user->setRole($role);
          $em->persist($user);
  
  
          $partenaire = new Partenaire();
          $partenaire->setNinea($json->partenaire->ninea);
          $partenaire->setRc($json->partenaire->rc);
          $partenaire->addUser($user);
          $em->persist($partenaire);
          
          $compte = new Compte();
          $numero = new CompteNumero();

         // $repo = $this->getDoctrine()->getRepository(Compte::class);
         // $resultat = $repo->findOneBy([], ['id' => 'desc']);
  
          $numero = $numero->getCompteNumero();
          $compte->setNumero($numero);
          $compte->setPartenaire($partenaire);
          $compte->setSolde($json->solde);
          $em->persist($compte);
  
          $depot = new Depot();
          $depot->setMntDeposser($json->depots->mntDeposser);
          $depot->setCompte($compte);
          $em->persist($depot);

         dd($compte);
  
          $em->flush();

    }

    public function compteSansNewPartenaire($json){

      $em = $this->getDoctrine()->getManager();
      $compte = new Compte();
  
      $numero = new CompteNumero();
      //$repo = $this->getDoctrine()->getRepository(Compte::class);
     // $resultat = $repo->findOneBy([], ['id' => 'desc']);

      $partenaireRepo = $this->getDoctrine()->getRepository(Partenaire::class);
      $partenaire = $partenaireRepo->find(intval($json->partenaire_id));
      $user = new User();
      foreach ($partenaire->getUsers() as $value) {
        if($value->getRole() === 'ROLE_PARTENAIRE'){
          $user = $value;
        }
      }

      $numero = $numero->getCompteNumero();
      $compte->setNumero($numero);
      $compte->setPartenaire($partenaire);
      $compte->setSolde($json->solde);
      $em->persist($compte);

      $depot = new Depot();
      $depot->setMntDeposser($json->depots->mntDeposser);
      $depot->setCompte($compte);
      $em->persist($depot);

      dd($compte);

      $em->flush();
    }


   /**
    * @Route(
    *    name="creation_compte",
    *    path="api/creation_compte",
    *    methods={"POST"}
    *)
    */
    public function __invoke(Request $request)
    {
        $data = $request->getContent();
        $json = json_decode($data,false);

        if(isset($json->partenaire_id)){

              $this->compteSansNewPartenaire($json);
              $return = [
                "code"=>"201",
                "content"=>"Creation de compte su partenaire reussi"
              ];
              $response = new JsonResponse();
              $response->setContent(json_encode($return));
              $response->headers->set('Content-Type', 'application/json');
              $response->setStatusCode(JsonResponse::HTTP_CREATED);
      
              return $response; 

        }else{

              $this->compteAvecNewPartenaire($json);
              $return = [
                "code"=>"201",
                "content"=>"Creation de compte su partenaire reussi"
              ];
              $response = new JsonResponse();
              $response->setContent(json_encode($return));
              $response->headers->set('Content-Type', 'application/json');
              $response->setStatusCode(JsonResponse::HTTP_CREATED);
      
              return $response; 
  
        }
  
    }
}