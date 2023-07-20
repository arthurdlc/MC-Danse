<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Component\Mime\Email;
use App\Repository\DisciplineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contact')]
class ContactController extends AbstractController
{
    /**
     * @Route("/send-email", name="send_email")
     */
    public function sendEmail(Request $request, MailerInterface $mailer, DisciplineRepository $disciplineRepository)
    {
        $first_name = $request->request->get('first_name');
        $last_name = $request->request->get('last_name');
        $email = $request->request->get('email');
        $phone_number = $request->request->get('phone_number');
        $subject = $request->request->get('subject');
        $message = $request->request->get('message');

        $email = (new Email())
            ->from('delacour.a1109@ik.me')
            ->to('delacour.a1109@ik.me')
            ->subject($subject)
            ->text("Ce mail provient du formulaire de contact du site Internet 'MC-Danse.fr'\n\nLes informations fournies peuvent être erronées ou ne pas correspondres aux coordonnées réelles de la personne ayant envoyé ce message, l'auteur de ce message peut donc se faire passer pour quelqu'un d'autre !\n\n\nNom : $last_name\n\nPrénom : $first_name\n\nEmail : $email\n\nTéléphone : $phone_number\n\nSujet : $subject\n\n\n\n$message");

        $mailer->send($email);

        // Add a flash message to indicate success
        $this->addFlash('success', 'Le message a bien été envoyé, nous vous recontacterons par mail ou par téléphone le plus vite possible !');

        return new RedirectResponse($this->generateUrl('contact'));
    }

    /**
     * @Route("/", name="contact", methods={"GET","POST"})  
     */
    public function contact(Request $request, DisciplineRepository $disciplineRepository): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Envoi de l'e-mail en utilisant l'action sendEmailAction
            return $this->redirectToRoute('send_email');
        }

        return $this->render('/contact/index.html.twig', [
            'form' => $form->createView(),
            'disciplines' => $disciplineRepository->findAll(),
        ]);
    }
}