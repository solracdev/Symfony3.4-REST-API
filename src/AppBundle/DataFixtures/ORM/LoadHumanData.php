<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Person;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;

class LoadHumanData implements ORMFixtureInterface {

    public function load(ObjectManager $manager) {

        $person = new Person();

        $person->setFirstName("Carlos");
        $person->setLastName("Garcia Lopez");
        $person->setDateOfBirth(new DateTime("1987-03-08"));

        $manager->persist($person);
        $manager->flush();
    }

}
