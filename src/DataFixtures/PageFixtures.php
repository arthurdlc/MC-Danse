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
<<<<<<< HEAD
        $accueil->setNumPage(1);
        $accueil->setCorp("");
=======
>>>>>>> e8f1096bfd875e55b04691171eaee5de19d085b7
        $manager->persist($accueil);

        $preparation = new Page();
        $preparation->setNom("Preparation au Marriages");
<<<<<<< HEAD
        $preparation->setNumPage(2);
        $preparation->setCorp("");
=======
>>>>>>> e8f1096bfd875e55b04691171eaee5de19d085b7
        $manager->persist($preparation);

        $manager->flush();
    }
}