<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransactionRetraitController extends AbstractController
{
 /**
    * @Route(
    *    name="transaction_retrait",
    *    path="api/comptes/transaction/retrait/{codeEnvoie}",
    *    methods={"PUT"}
    *)
    */
    public function __invoke($codeEnvoie, Request $request){

        $data = $request->getContent();
        $json = json_decode($data,false);

        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $transaction = $repo->findOneBy([
            'codeEnvoie' => $codeEnvoie
        ]);

        $compteRepo = $this->getDoctrine()->getRepository(Compte::class);
        $compte = $compteRepo->findOneBy([
            'id' => $json->compteRetrait
        ]);

        $compte->setSolde($compte->getSolde() + $transaction->getMontantTranfere());
        $transaction->setCompteRetrait($compte);

        $transaction->getCustomerRetrait()->setIdentityCard($json->customerRetrait->identityCard);
        $transaction->getCustomerRetrait()->setTypeIdentityCard($json->customerRetrait->typeIdentityCard);
        $transaction->getCustomerRetrait()->setGenre($json->customerRetrait->genre);
        $transaction->setIsRetiret(true);

       $em = $this->getDoctrine()->getManager();
       $em->persist($transaction);
       $em->flush();


       $return = [
        "code"=>"200",
        "content"=>"Retrait effectuer avec sucess: numéro transaction = ".$transaction->getNumero()
      ];
      $response = new JsonResponse();
      $response->setContent(json_encode($return));
      $response->headers->set('Content-Type', 'application/json');
      $response->setStatusCode(JsonResponse::HTTP_OK);

      return $response;
    }
}
/*
{
  "compteRetrait": "3",
  "isRetiret":true,
  "customerRetrait": {
    "genre": "M",
    "identityCard": "234567890",
    "typeIdentityCard": "ID"
  }
}
*/