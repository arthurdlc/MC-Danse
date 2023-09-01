<?php

namespace App\Controller;

use App\Entity\StageEvenement;
use App\Form\StageEvenementType;
use App\Services\ImageUploaderHelper;
use App\Repository\DisciplineRepository;
use App\Repository\StageEvenementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/stageevenement')]
class StageEvenementController extends AbstractController
{
    #[Route('/evenement', name:'app_stage_evenement_aVenir', methods:['GET'])]
    function evenement(StageEvenementRepository $stageEvenementRepository, DisciplineRepository $disciplineRepository): Response
    {
        return $this->render('stage_evenement/evenement.html.twig', [
            'stage_evenements' => $stageEvenementRepository->findAll(),
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_stage_evenement_index', methods: ['GET'])]
    public function index(StageEvenementRepository $stageEvenementRepository, DisciplineRepository $disciplineRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('stage_evenement/index.html.twig', [
            'stage_evenements' => $stageEvenementRepository->findAll(),
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stage_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StageEvenementRepository $stageEvenementRepository, VideoUploaderHelper $videoUploaderHelper, DisciplineRepository $disciplineRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $stageEvenement = new StageEvenement();
        $form = $this->createForm(StageEvenementType::class, $stageEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errorMessage = $imageUploaderHelper->uploadImage($form, $stageEvenement);
            if (!empty($errorMessage)) {
                $stageEvenement->setImage("logo.png");  
            }

            $stageEvenementRepository->save($stageEvenement, true);

            return $this->redirectToRoute('app_stage_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stage_evenement/new.html.twig', [
            'stage_evenement' => $stageEvenement,
            'form' => $form,
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_stage_evenement_show', methods: ['GET'])]
    public function show(StageEvenement $stageEvenement, DisciplineRepository $disciplineRepository, ImageUploaderHelper $imageUploaderHelper): Response
    {
        return $this->render('stage_evenement/show.html.twig', [
            'stage_evenement' => $stageEvenement,
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stage_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StageEvenement $stageEvenement, DisciplineRepository $disciplineRepository, StageEvenementRepository $stageEvenementRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(StageEvenementType::class, $stageEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errorMessage = $imageUploaderHelper->uploadImage($form, $stageEvenement);
            if (!empty($errorMessage)) {
                $this->addFlash('danger', $translator->trans('An error is append: ') . $errorMessage);
    
            }
            $stageEvenementRepository->save($stageEvenement, true);

            return $this->redirectToRoute('app_stage_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stage_evenement/edit.html.twig', [
            'stage_evenement' => $stageEvenement,
            'form' => $form,
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_stage_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, StageEvenement $stageEvenement, StageEvenementRepository $stageEvenementRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); 

        if ($this->isCsrfTokenValid('delete'.$stageEvenement->getId(), $request->request->get('_token'))) {
            $stageEvenementRepository->remove($stageEvenement, true);
        }

        return $this->redirectToRoute('app_stage_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
