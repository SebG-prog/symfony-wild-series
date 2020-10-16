<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Bobby',
        'Bobbette'
    ];

    public function load(ObjectManager $manager)
    {
        $index = 0;
        foreach (self::ACTORS as $key => $name) {
            $actor = new Actor();
            $actor->setName($name);
            $actor->addProgram($this->getReference('program_0'));  
            
            $manager->persist($actor);
            $this->addReference('actor_' . $key, $actor);
            $index++;
        }

        for ($index; $index < 50; $index++) {
            $faker = Faker\Factory::create('fr_FR');
            $actor = new Actor();
            $actor->addProgram($this->getReference('program_' . $faker->numberBetween($min = 0, $max = 5 )));
            $actor->setName($faker->name);
            $manager->persist($actor);
            $this->addReference('actor_' . $index, $actor);
        }
        
        $manager->flush();
    }

    public function getDependencies()  
    {
        return [ProgramFixtures::class];  
    }
}