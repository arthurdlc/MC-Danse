<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreparationController extends AbstractController
{
    
    #[Route("/preparation", name: 'app_preparation')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_page_show', ['id' => 4]);
    }
}