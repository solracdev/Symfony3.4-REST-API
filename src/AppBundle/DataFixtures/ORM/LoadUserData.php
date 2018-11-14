<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadUserData implements ORMFixtureInterface, ContainerAwareInterface {

    /**
     *
     * @var ContainerInterface 
     */
    private $container;

    public function load(ObjectManager $manager) {
        
        $passwordEnconder = $this->container->get("security.password_encoder");

        $user = new User();
        $user->setUsername("Cachuli");
        $user->setPassword($passwordEnconder->encodePassword($user, "123456"));
        $user->setRoles([User::ROLE_ADMIN]);

        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername("Paco");
        $user2->setPassword($passwordEnconder->encodePassword($user2, "qazxsw"));
        $user2->setRoles([User::ROLE_ADMIN]);
        
        $manager->persist($user2);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null) {

        $this->container = $container;
        
    }

}
