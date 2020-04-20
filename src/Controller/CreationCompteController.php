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
   private $entityManager;
 
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder,EntityManagerInterface $entityManager)
    {
      $this->userPasswordEncoder = $userPasswordEncoder;
      $this->entityManager = $entityManager;

    }

    public function compteAvecNewPartenaire($json){

 /*
    {
        "partenaire":
        {
          "ninea": "2096817",
          "rc": "5728/1997",
          "user":
            {
              "username": "partenaire01",
              "roles": [
                  "ROLE_PARTENAIRE"
              ],
              "role": "4",
              "password":"partenaire01"
            }
        },
        "depots":
          {
            "mntDeposser": "7000000"
            }
    }
  */
          $em = $this->getDoctrine()->getManager();

          if($json->partenaire->user->password == null && $json->partenaire->user->username == null ){
            $firstname = strtoupper(substr($json->partenaire->user->firstname, 0,1));
            $lastname = strtoupper(substr($json->partenaire->user->lastname, 0,1));
            $repo = $this->entityManager->getRepository(User::class);
            $u = $repo->findOneBy([],['id' => 'desc']);
            $numero = $u->getId()+1;
            $username = $firstname.''.$lastname.''.$numero;
          }else{

          }

          $user = new User();
          $user->setUsername($username);
          $user->setPassword($this->userPasswordEncoder->encodePassword($user,$username));
          //$user->setRoles($json->partenaire->user->roles); /*45054424394318*/

          $role = $json->partenaire->user->role;
          $user->setRoles([$role]);
          $repo = $this->getDoctrine()->getRepository(Role::class);
          $role = $repo->findOneBy([
            'libelle' => $role
          ]);
          $user->setRole($role);
          //dd($user);


          $user->setFirstname($json->partenaire->user->firstname);
          $user->setLastname($json->partenaire->user->lastname);
          $user->setEmail($json->partenaire->user->email);
          $user->setTelephone($json->partenaire->user->telephone);
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
          $em->persist($compte);

          $depot = new Depot();
          $depot->setMntDeposser($json->depots->mntDeposser);
          $depot->setCompte($compte);
          $em->persist($depot);

          //dd($compte);

          $em->flush();
          return $compte;

    }

    public function compteSansNewPartenaire($json){

  /*
  {
        "partenaire":
          {
            "ninea": "2096817"
          },
        "depots":
          {
            "mntDeposser": "7000000"
          }
    }
    */

      $em = $this->getDoctrine()->getManager();
      $compte = new Compte();

      $numero = new CompteNumero();

      $partenaireRepo = $this->getDoctrine()->getRepository(Partenaire::class);
      $partenaire = $partenaireRepo->findOneBy([
        "ninea" => $json->partenaire->ninea
      ]);
     // $user = new User();
     // foreach ($partenaire->getUsers() as $value) {
     //   if($value->getRole() === 'ROLE_PARTENAIRE'){
      //    $user = $value;
      //  }
     // }

      $numero = $numero->getCompteNumero();
      $compte->setNumero($numero);
      $compte->setPartenaire($partenaire);
      $em->persist($compte);

      $depot = new Depot();
      $depot->setMntDeposser($json->depots->mntDeposser);
      $depot->setCompte($compte);
      $em->persist($depot);

      //dd($compte);

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
      if (!$this->isGranted('ROLE_ADMIN_SYSTEME')) {
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
        $data = $request->getContent();
        $json = json_decode($data,false);
       // $compte = new Compte();

        if(isset($json->partenaire->ninea) && !isset($json->partenaire->rc)){

              $compte = $this->compteSansNewPartenaire($json);
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

             $compte =  $this->compteAvecNewPartenaire($json);
              $return = [
                "code"=>"201",
                "content"=>"Creation de compte su partenaire reussi",
                "numero" => $compte->getNumero()
              ];
              $response = new JsonResponse();
              $response->setContent(json_encode($return));
              $response->headers->set('Content-Type', 'application/json');
              $response->setStatusCode(JsonResponse::HTTP_CREATED);

              return $response;
        }

    }
}