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
        $role->setLibelle("ROLE_SUPER_ADMIN_SYSTEME");
        $manager->persist($role);

        $role1 = new Role();
        $role1->setLibelle("ROLE_ADMIN_SYSTEME");
        $manager->persist($role1);

        $role2 = new Role();
        $role2->setLibelle("ROLE_CAISSIER_SYSTEME");
        $manager->persist($role2);

        $role3 = new Role();
        $role3->setLibelle("ROLE_PARTENAIRE");
        $manager->persist($role3);

        $role4 = new Role();
        $role4->setLibelle("ROLE_ADMIN_PARTENAIRE");
        $manager->persist($role4);

        $role5 = new Role();
        $role5->setLibelle("ROLE_USER_PARTENAIRE");
        $manager->persist($role5);

        $manager->flush();

        $this->addReference("ROLE_SUPER_ADMIN_SYSTEME", $role);


        $user = new User("superadmin");
        $user->setFirstname("Super");
        $user->setLastname("Admin");
        $user->setEmail("superadmin@gmail.com");
        $user->setTelephone("778580286");
        $user->setPassword($this->encoder->encodePassword($user,"superadmin"));
        $user->setRoles(array("ROLE_SUPER_ADMIN_SYSTEME")); /*45054424394318*/
        $user->setRole($this->getReference("ROLE_SUPER_ADMIN_SYSTEME"));
        $manager->persist($user);
        // $product = new Product(); !31ArIs93DoVi08
        // $manager->persist($product);

        $manager->flush();
    }
}
