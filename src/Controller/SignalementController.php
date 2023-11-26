<?php

namespace App\Controller;

use App\Entity\Musique;
use App\Form\SignalType;
use App\Entity\Signalement;
use Symfony\Component\Mime\Email;
use App\Repository\MusiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SignalementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class SignalementController extends AbstractController
{
    #[Route('/signalement{id}', name: 'app_signalement')]
    public function index(Request $request, EntityManagerInterface $em, MusiqueRepository $m, Musique $musiques, MailerInterface $mailer ): Response
    {
        $signal = New Signalement();
        $musique = $m->find($musiques->getId());
        $form= $this->createForm(SignalType::class, $signal);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $signal->setMusique($musique);
            $em->persist($signal);
            $em->flush();

            $nombreSignalements = count($musique->getSignalements());

            if ($nombreSignalements == 3) {
                // Supprimez la musique si le nombre de signalements est == 3
                $email = (new Email())
                ->from('admin@v2vmusic.fr')
                ->to($musique->getUser()->getEmail())
                ->subject('No-Reply - Signalement')
                ->text('Bonjour, Pour cause de trop de signalement pour votre musique ' .  $musique->getTitre() . ' , nous sommes dans l\'obligation de la supprimer du site. Cordialement, l\'équipe V2VMusic');
        
                $mailer->send($email);

                $m->remove($musique, 1);
                return $this->redirectToRoute('app_home');
            }
            else{
                $this->addFlash('success', 'Merci! Votre signalement a été envoyé');
                return $this->redirectToRoute('app_oneMusique', array('id' => $musique->getId()));
            }
        }

       
        return $this->render('signalement/formSignal.html.twig', [
            'formSignal' => $form->createView(),
        ]);

       
    }
}
