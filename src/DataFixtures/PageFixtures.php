<?php

namespace App\DataFixtures;

use App\Entity\Page;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PageFixtures extends Fixture
{
    public function __construct() {
    }
    public function load(ObjectManager $manager): void
    {
        $accueil = new Page();
        $accueil->setNom("Accueil");
        $manager->persist($accueil);

        $preparation = new Page();
        $preparation->setNom("Preparation au Marriages");
        $manager->persist($preparation);

        $manager->flush();
    }
}