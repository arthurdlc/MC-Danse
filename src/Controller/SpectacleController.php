<?php

namespace App\Controller;

use App\Entity\Spectacle;
use App\Form\SpectacleType;
use App\Services\ImageUploaderHelper;
use App\Repository\SpectacleRepository;
use App\Repository\DisciplineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;                                      

#[Route('/spectacle')]
class SpectacleController extends AbstractController
{
    #[Route('/aVenir', name:'app_discipline_aVenir', methods:['GET'])]
    function aVenir(SpectacleRepository $spectacleRepository, DisciplineRepository $disciplineRepository): Response
    {
        return $this->render('spectacle/aVenir.html.twig', [
            'spectacles' => $spectacleRepository->findAllInTheFutur(),
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_spectacle_index', methods: ['GET'])]
    public function index(SpectacleRepository $spectacleRepository, DisciplineRepository $disciplineRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('spectacle/index.html.twig', [
            'spectacles' => $spectacleRepository->findAll(),
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_spectacle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SpectacleRepository $spectacleRepository, DisciplineRepository $disciplineRepository, ImageUploaderHelper $imageUploaderHelper): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $spectacle = new Spectacle();
        $form = $this->createForm(SpectacleType::class, $spectacle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errorMessage = $imageUploaderHelper->uploadImage($form, $spectacle);
            if (!empty($errorMessage)) {
                $spectacle->setImage("logo.png");  
            }

            $spectacleRepository->save($spectacle, true);


            return $this->redirectToRoute('app_spectacle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('spectacle/new.html.twig', [
            'spectacle' => $spectacle,
            'form' => $form,
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_spectacle_show', methods: ['GET'])]
    public function show(Spectacle $spectacle, SpectacleRepository $spectacleRepository, ImageUploaderHelper $imageUploaderHelper, DisciplineRepository $disciplineRepository): Response
    {
        return $this->render('spectacle/show.html.twig', [
            'spectacle' => $spectacle,
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_spectacle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Spectacle $spectacle, ImageUploaderHelper $imageUploaderHelper, SpectacleRepository $spectacleRepository, DisciplineRepository $disciplineRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(SpectacleType::class, $spectacle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errorMessage = $imageUploaderHelper->uploadImage($form, $spectacle);
            if (!empty($errorMessage)) {
                $this->addFlash('danger', $translator->trans('An error is append: ') . $errorMessage);
    
            }
            $spectacleRepository->save($spectacle, true);

            return $this->redirectToRoute('app_spectacle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('spectacle/edit.html.twig', [
            'spectacle' => $spectacle,
            'form' => $form,
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_spectacle_delete', methods: ['POST'])]
    public function delete(Request $request, Spectacle $spectacle, SpectacleRepository $spectacleRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$spectacle->getId(), $request->request->get('_token'))) {
            $spectacleRepository->remove($spectacle, true);
        }

        return $this->redirectToRoute('app_spectacle_index', [], Response::HTTP_SEE_OTHER);
    }
}
