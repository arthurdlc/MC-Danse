<?php

namespace App\Controller;

use App\Entity\Discipline;
use App\Form\DisciplineType;
use App\Services\ImageUploaderHelper;
use App\Repository\DisciplineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/discipline')]
class DisciplineController extends AbstractController
{
    #[Route('/liste', name: 'app_discipline_liste', methods: ['GET'])]
    function liste(DisciplineRepository $disciplineRepository): Response
    {
        return $this->render('discipline/liste.html.twig', [
            'disciplines' => $disciplineRepository->findAll(),
        ]); 
    }

    #[Route('/', name: 'app_discipline_index', methods: ['GET'])]
    public function index(DisciplineRepository $disciplineRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('discipline/index.html.twig', [
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_discipline_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImageUploaderHelper $imageUploaderHelper, DisciplineRepository $disciplineRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $discipline = new Discipline();
        $form = $this->createForm(DisciplineType::class, $discipline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errorMessage = $imageUploaderHelper->uploadImage($form, $discipline);
            if (!empty($errorMessage)) {
                $discipline->setImage("logo.png");  
            }

            $disciplineRepository->save($discipline, true);


            return $this->redirectToRoute('app_discipline_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('discipline/new.html.twig', [
            'discipline' => $discipline,
            'form' => $form,
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_discipline_show', methods: ['GET'])]
    public function show(Discipline $discipline, ImageUploaderHelper $imageUploaderHelper, DisciplineRepository $disciplineRepository): Response
    {
        return $this->render('discipline/show.html.twig', [
            'discipline' => $discipline,
            'disciplines' => $disciplineRepository->findAll(),
        ]); 
    }


    #[Route('/{id}/edit', name: 'app_discipline_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ImageUploaderHelper $imageUploaderHelper, Discipline $discipline, DisciplineRepository $disciplineRepository, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(DisciplineType::class, $discipline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $errorMessage = $imageUploaderHelper->uploadImage($form, $discipline);
            if (!empty($errorMessage)) {
                $this->addFlash('danger', $translator->trans('An error is append: ') . $errorMessage);
    
            }
            $disciplineRepository->save($discipline, true);

            return $this->redirectToRoute('app_discipline_index', [], Response::HTTP_SEE_OTHER); 
        }

        return $this->renderForm('discipline/edit.html.twig', [
            'discipline' => $discipline,
            'form' => $form,
            'disciplines' => $disciplineRepository->findAll(),  
        ]);
    }

    #[Route('/{id}', name: 'app_discipline_delete', methods: ['POST'])]
    public function delete(Request $request, Discipline $discipline, DisciplineRepository $disciplineRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$discipline->getId(), $request->request->get('_token'))) {
            $disciplineRepository->remove($discipline, true);
        }

        return $this->redirectToRoute('app_discipline_index', [], Response::HTTP_SEE_OTHER);
    }
}
