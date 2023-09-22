<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form= $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $data= $form->getData();
            $adresse = $data['email'];
            $content= $data['content'];
            $nom = $data['nom'];
            $prenom = $data['prenom'];
            
            $email = (new Email())
            ->from($adresse)
            ->to('admin@v2vMusic.com')
            ->subject('Demande de contact de' ." ". $prenom ." ". $nom)
            ->text($content);

            $mailer->send($email);
            $this->addFlash('success', 'Votre message a été envoyé');

            return $this->redirectToRoute('app_home');
        }


        return $this->render('contact/contact.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }
}
