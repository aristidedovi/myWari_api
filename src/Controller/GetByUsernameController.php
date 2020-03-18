<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class GetByUsernameController extends AbstractController
{

       /**
    * @Route(
    *    name="getUserByUsername",
    *    path="api/users/username/{username}",
    *    methods={"GET"}
    *)
    */
    public function __invoke($username, EntityManagerInterface $em) {
       $user = $em->getRepository(User::class)->findOneBy([
            'username' => $username
        ]);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
       // return $user;
       // dd($user);
       /* $return = [
            "code"=>"201",
            "content"=>"Creation de compte su partenaire reussi",
            "value"=>$username
          ];*/

          $user = $this->get('serializer')->serialize($user, 'json');

          $response = new Response($user);
      
          $response->headers->set('Content-Type', 'application/json');
          return $response;
     }
}
