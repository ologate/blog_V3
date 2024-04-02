<?php

namespace App\DataFixtures;

use App\Entity\utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;



class blog extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $utilisateur=new utilisateur;
        $utilisateur="";
    }
}
?>