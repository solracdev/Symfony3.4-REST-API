<?php

namespace AppBundle\DataFixtures\ORM\Processor;

use AppBundle\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserProcessor implements ProcessorInterface {

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEnconder;

    public function __construct(UserPasswordEncoderInterface $passwordEnconder) {
        
        $this->passwordEnconder = $passwordEnconder;
    }

    public function postProcess(string $id, $object): void {
        
        if (!$object instanceof User) {
            
            return;
            
        }
        
        $password = $this->passwordEnconder->encodePassword($object, $object->getPassword());
        
        $object->setPassword($password);
        
    }

    public function preProcess(string $id, $object): void {
        
    }

}
