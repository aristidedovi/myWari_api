<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface{

    private $entityManager;
    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data)
    {
        //$username = null;
       // dd($data->getUsername());

        if($data->getUsername() == null && $data->getPlainPassword() == null) {
            $firstname = strtoupper(substr($data->getFirstname(), 0,1));
            $lastname = strtoupper(substr($data->getLastname(), 0,1));
            $repo = $this->entityManager->getRepository(User::class);
            $u = $repo->findOneBy([],['id' => 'desc']);
            $numero = $u->getId()+1;
            $username = $firstname.''.$lastname.''.$numero;
            $data->setUsername($username);
            //dd($data->getUsername());

            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data,$username)
            );
           // dd($data);
            $data->eraseCredentials();
        }

       if($data->getPlainPassword() != null){
        //dd($data);
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data,$data->getPlainPassword())
            );
           // dd($data);
            $data->eraseCredentials();
        }
        //dd($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

}