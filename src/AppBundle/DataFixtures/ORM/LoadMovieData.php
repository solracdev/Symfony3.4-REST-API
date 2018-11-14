<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Movie;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;

class LoadMovieData implements ORMFixtureInterface {

    public function load(ObjectManager $manager) {

        $movie = new Movie();
        $movie->setTitle("Hook");
        $movie->setYear(1992);
        $movie->setTime(120);
        $movie->setDescription("Bangarang!");

        $manager->persist($movie);
        $manager->flush();
    }

}
