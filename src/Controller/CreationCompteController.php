<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreationCompteController
{
 
    public function __construct()
    {
        
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
        $json = json_decode($data,true);

        //$user = new User($json["user"]['username']);
        
                //$username = $request->request->get("user");
        //die(json_encode($data));
       // die($json['user']);
        //$response = new Response($json["user"]);
        //$response->headers->set('Content-Type', 'application/json');
        //die($data);
        print_r($json["partenaire"][0]["rc"],'\n');
        return  new JsonResponse($json);   
    }
}