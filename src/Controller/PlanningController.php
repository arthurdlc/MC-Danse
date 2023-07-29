<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Form\PlanningType;
use App\Services\ImageUploaderHelper;
use App\Repository\PlanningRepository;
use App\Repository\DisciplineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/planning')]
class PlanningController extends AbstractController
{
    #[Route('/planing', name:'app_planning_planing', methods:['GET'])]
    function planing(PlanningRepository $planningRepository, DisciplineRepository $disciplineRepository): Response
    {
        return $this->render('planning/planing.html.twig', [
            'plannings' => $planningRepository->findAll(),
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_planning_index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository, DisciplineRepository $disciplineRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('planning/index.html.twig', [
            'plannings' => $planningRepository->findAll(),
            'disciplines'=> $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_planning_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlanningRepository $planningRepository, DisciplineRepository $disciplineRepository, ImageUploaderHelper $imageUploaderHelper): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errorMessage = $imageUploaderHelper->uploadImage($form, $planning);
            if (!empty($errorMessage)) {
                $planning->setImage("logo.png");  
            }

            $planningRepository->save($planning, true);

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
            'disciplines'=> $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_planning_show', methods: ['GET'])]
    public function show(Planning $planning, DisciplineRepository $disciplineRepository, ImageUploaderHelper $imageUploaderHelper): Response
    {
        
        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
            'disciplines'=> $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, PlanningRepository $planningRepository, DisciplineRepository $disciplineRepository, ImageUploaderHelper $imageUploaderHelper): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errorMessage = $imageUploaderHelper->uploadImage($form, $planning);
            if (!empty($errorMessage)) {
                $this->addFlash('danger', $translator->trans('An error is append: ') . $errorMessage);
    
            }

            $planningRepository->save($planning, true);

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
            'disciplines'=> $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $planningRepository->remove($planning, true);
        }

        return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
    }
}
