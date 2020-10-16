<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 16; $i++) {
            $faker = Faker\Factory::create('fr_FR');

            $episode = new Episode();
            $episode->setTitle($faker->words($min =1, $max = 5));
            $episode->setNumber($faker->numberBetween($min = 1, $max=20));
            $episode->setSummary($faker->text);
            $episode->setSeason($this->getReference('season_' . $faker->numberBetween($min = 0, $max = 5)));

            $manager->persist($episode);
            $this->addReference('episode' . $i, $episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}