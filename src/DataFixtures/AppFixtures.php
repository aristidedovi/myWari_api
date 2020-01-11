<?php

namespace App\DataFixtures;

use App\Entity\Role;
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

        $role = new Role();
        $role->setLibelle("ROLE_SUPER_ADMIN");
        $manager->persist($role);

        $role1 = new Role();
        $role1->setLibelle("ROLE_ADMIN");
        $manager->persist($role1);

        $role2 = new Role();
        $role2->setLibelle("ROLE_CAISSIER");
        $manager->persist($role2);

        $manager->flush();

        $this->addReference("ROLE_SUPER_ADMIN", $role);


        $user = new User("Super Admin");
        $user->setPassword("superadmin");
        $user->setRoles(array("ROLE_SUPER_ADMIN")); /*45054424394318*/
        $user->setRole($this->getReference("ROLE_SUPER_ADMIN"));
        $manager->persist($user);
        // $product = new Product(); !31ArIs93DoVi08
        // $manager->persist($product);

        $manager->flush();
    }
}
