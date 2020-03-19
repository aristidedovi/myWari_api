<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Customer;
use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
  /**
    * @Route(
    *    name="transaction",
    *    path="api/comptes/transaction/envoie",
    *    methods={"POST"}
    *)
    */
    public function __invoke(Request $request){

        $data = $request->getContent();
        $json = json_decode($data,false);

        $repo = $this->getDoctrine()->getRepository(Customer::class);

        $em = $this->getDoctrine()->getManager();

        $customer1 = $repo->findOneBy([
            'telephone' => $json->customerSender->telephone
        ]);

        $customer2 = $repo->findOneBy([
            'telephone' => $json->customerRetrait->telephone
        ]);

        if($customer1 === null){
            $customSender = new Customer();
            $customSender->setFirstname($json->customerSender->firstname);
            $customSender->setLastname($json->customerSender->lastname);
            $customSender->setAdresse($json->customerSender->adresse);
            $customSender->setIdentityCard($json->customerSender->identityCard);
            $customSender->setTypeIdentityCard($json->customerSender->typeIdentityCard);
            $customSender->setTelephone($json->customerSender->telephone);
            $customSender->setGenre($json->customerSender->genre);

            $em->persist($customSender);
            $em->flush();
        }else{
            $customSender = $customer1 ;
        }
        if($customer2 === null){
            $customRetrait = new Customer();
            $customRetrait->setFirstname($json->customerRetrait->firstname);
            $customRetrait->setLastname($json->customerRetrait->lastname);
            $customRetrait->setAdresse($json->customerRetrait->adresse);
            //$customRetrait->setIdentityCard($json->customerRetrait->identityCard);
            //$customRetrait->setTypeIdentityCard($json->customerRetrait->typeIdentityCard);
            $customRetrait->setTelephone($json->customerRetrait->telephone);
            //$customRetrait->setGenre($json->customerRetrait->genre);

            $em->persist($customRetrait);
            $em->flush();
        }else{
            $customRetrait = $customer2;
        }

        $transaction = new Transaction();
        $transaction->setMontantTranfere($json->montantTranfere);

        $compteRepo = $this->getDoctrine()->getRepository(Compte::class);
        $compte = $compteRepo->findOneBy([
            'id' => $json->compteSender
        ]);

        $compte->setSolde($compte->getSolde() - $transaction->getMontantTranfere());

        $transaction->setCompteSender($compte);
        $transaction->setCustomerSender($customSender);
        $transaction->setCustomerRetrait($customRetrait);

        $em->persist($transaction);
        $em->flush();

        $return = [
            "code"=>"200",
            "content"=>"Transfert effectuer avec sucess: code d'envoie = ".$transaction->getCodeEnvoie()
          ];
          $response = new JsonResponse();
          $response->setContent(json_encode($return));
          $response->headers->set('Content-Type', 'application/json');
          $response->setStatusCode(JsonResponse::HTTP_OK);

          return $response;

        //dd($transaction, $customSender, $customRetrait);
    }
}
/*
{
  "montantTranfere": 10000,
  "compteSender": "2",
  "customerSender": {
    "firstname": "Aristide",
    "lastname": "dovi",
    "genre": "M",
    "identityCard": "234567890",
    "typeIdentityCard": "ID",
    "telephone": "21345657",
    "adresse": "liberté 5"
  },
  "customerRetrait": {
    "firstname": "fatou",
    "lastname": "thiam",
    "telephone": "25236453645",
    "adresse": "liberté 5"
  }
}
*/