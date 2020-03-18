<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Depot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class CreationDepotController extends AbstractController
{

   /**
    * @Route(
    *    name="depot_compte",
    *    path="api/depot_compte",
    *    methods={"POST"}
    *)
    * 
    */
    public function __invoke(Request $request)
    {
        if (!$this->isGranted('ROLE_CAISSIER')) {
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

        if(isset($json->mntDeposser) && isset($json->compte->numero)){
            if(!is_int($json->mntDeposser)){
                $return = [
                    "code"=>"400",
                    "content"=>"Le montant saisie n'est pas un entier"
                  ];
                  $response = new JsonResponse();
                  $response->setContent(json_encode($return));
                  $response->headers->set('Content-Type', 'application/json');
                  $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);

                  return $response;
            }
            $em = $this->getDoctrine()->getManager();

            //dd($json);
            $compte = new Compte();
            $repo = $this->getDoctrine()->getRepository(Compte::class);
            $compte = $repo->findOneBy([
                'numero' => $json->compte->numero
            ]);

            if($compte === null){
                $return = [
                    "code"=>"400",
                    "content"=>"Le numero de compte est incorecte"
                  ];
                  $response = new JsonResponse();
                  $response->setContent(json_encode($return));
                  $response->headers->set('Content-Type', 'application/json');
                  $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);

                  return $response;
            }

            $depot = new Depot();
            $depot->setMntDeposser($json->mntDeposser);
            $depot->setCompte($compte);
             /** Mise a jour du solde su compte implémenter dans un listner/DepotSubcriber */

            $em->persist($depot);
            $em->flush();

            $return = [
                "code"=>"201",
                "content"=>"Dépot éffectuer avec success"
              ];
              $response = new JsonResponse();
              $response->setContent(json_encode($return));
              $response->headers->set('Content-Type', 'application/json');
              $response->setStatusCode(JsonResponse::HTTP_CREATED);

        }else{
            $return = [
                "code"=>"400",
                "content"=>"Aucune données ne doivent etre null"
              ];
              $response = new JsonResponse();
              $response->setContent(json_encode($return));
              $response->headers->set('Content-Type', 'application/json');
              $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
        }

        return $response;
    }
}
