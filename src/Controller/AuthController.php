<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AuthController extends AbstractController
{

    public function creationuser(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $roles = $request->request->get('roles');
        
        if(!$roles){
            $roles = [];
        }

        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user,$password));
        $user->setRoles(array($roles));

        print_r($user);
        die();
        
        $em->persist($user);
        $em->flush();

        return new Response(sprintf("User %s ajouter avec success", $user->getUsername()));
    }

    public function __invoke(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $this->creationuser($request, $encoder);
    }
}
