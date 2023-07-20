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
        $home = new Page();
        $home->setNumPage(1);
        $home->setNom("");
        $manager->persist($home);

        $formationcenter = new Page();
        $formationcenter->setNumPage(2);
        $formationcenter->setNom("");
        $manager->persist($formationcenter);

        $manager->flush();
    }
}
