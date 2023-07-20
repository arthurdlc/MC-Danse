<?php

namespace App\Controller;

use App\Entity\Initiation;
use App\Form\InitiationType;
use App\Repository\InitiationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/initiation')]
class InitiationController extends AbstractController
{
    #[Route('/', name: 'app_initiation_index', methods: ['GET'])]
    public function index(InitiationRepository $initiationRepository): Response
    {
        return $this->render('initiation/index.html.twig', [
            'initiations' => $initiationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_initiation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InitiationRepository $initiationRepository): Response
    {
        $initiation = new Initiation();
        $form = $this->createForm(InitiationType::class, $initiation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $initiationRepository->save($initiation, true);

            return $this->redirectToRoute('app_initiation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('initiation/new.html.twig', [
            'initiation' => $initiation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_initiation_show', methods: ['GET'])]
    public function show(Initiation $initiation): Response
    {
        return $this->render('initiation/show.html.twig', [
            'initiation' => $initiation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_initiation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Initiation $initiation, InitiationRepository $initiationRepository): Response
    {
        $form = $this->createForm(InitiationType::class, $initiation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $initiationRepository->save($initiation, true);

            return $this->redirectToRoute('app_initiation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('initiation/edit.html.twig', [
            'initiation' => $initiation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_initiation_delete', methods: ['POST'])]
    public function delete(Request $request, Initiation $initiation, InitiationRepository $initiationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$initiation->getId(), $request->request->get('_token'))) {
            $initiationRepository->remove($initiation, true);
        }

        return $this->redirectToRoute('app_initiation_index', [], Response::HTTP_SEE_OTHER);
    }
}
