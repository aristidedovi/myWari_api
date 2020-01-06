<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $user = new User("Super Admin");
        $user->setPassword($this->encoder->encodePassword($user,"superadmin"));
        $user->setRoles(array("ROLE_SUPER_ADMIN")); /*45054424394318*/
        $manager->persist($user);
        // $product = new Product(); !31ArIs93DoVi08
        // $manager->persist($product);

        $manager->flush();
    }
}
