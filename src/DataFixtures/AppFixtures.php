<?php

namespace App\DataFixtures;

use App\Entity\Pen;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 60; $i++) {
            $pen = new Pen();
            $pen ->setName($faker->name());
            $pen->setPrice($faker->randomFloat($nbMaxDecimals = 2, $min = 3, $max = 600));
            $manager->persist($pen);
        }

        $manager->flush();
    }
}
