<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 6; $i++) {
            $program_id = 0;
            foreach (ProgramFixtures::PROGRAMS as $program) {
                $faker = Faker\Factory::create('fr_FR');
                $season = new Season();
                $season->setProgram($this->getReference('program_' . $program_id));
                $season->setNumber($i);
                $season->setYear($faker->numberBetween($min = 1990, $max = 2020));
                $season->setDescription($faker->text);
                $manager->persist($season);
                $season_index = (($i * count(ProgramFixtures::PROGRAMS)) + ($i + $program_id));
                $this->addReference('season_' . $season_index, $season);
                $program_id++;
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}