<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 1000; $i++) {
           $category = new Category();
           $category->setName($faker->word);
           $manager->persist($category);
           $this->addReference("category_" . $i, $category);
        
           $program = new Program();
           $title =$faker->word(1);
           $program->setTitle($title);
           $program->setSlug(mb_strtolower($title));
           $program->setSummary($faker->text(100));
           $program->setCategory($this->getReference("category_" . $i));
           $program->setCountry($faker->country);
           $program->setYear($faker->year($max = 'now'));
           $this->addReference("program_".$i, $program);
           $manager->persist($program);
        
            $actor = new Actor();
            $firstname = $faker->firstName;
            $actor->setName($firstname);
            $actor->setSlug(mb_strtolower($firstname));
            $actor->addProgram($this->getReference("program_" . $i));
            $manager->persist($actor);
        }
        $manager->flush();
    }
}
