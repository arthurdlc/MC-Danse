<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PreparationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route("/preparation", name: 'app_preparation')]
    public function index(): Response
    {
        $pageRepository = $this->entityManager->getRepository(Page::class);
        $page = $pageRepository->find(6);

        if ($page) {
            return $this->render('page/show.html.twig', [
                'page' => $page,
            ]);
        } else {
            // Traitez le cas où la page avec ID 4 n'a pas été trouvée
            // Vous pouvez rediriger, afficher un message d'erreur, etc.
        }
    }
}