<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModoType;
use App\Entity\Musique;
use App\Form\AdminType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\MusiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModerationController extends AbstractController
{

    #[Route(path: '/admin/passer_en_modo_{id}', name: 'app_modo')]
    public function addAdmin( Request $request, UserRepository $repo, User $users)
    {
       $secret="v2vmusic";
       $user= $repo->find($users->getId());
       $form=$this->createForm(ModoType::class);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
            if($form->get("mdpForm")->getData() == $secret){
                $user->setRoles(['ROLE_MODO']);
                $repo->save($user,1);
                $this->addFlash('success', 'Vous êtes autorisé à passer en modérateur !');
            }
            else{
                $this->addFlash('error', 'Vous n\'êtes pas autorisé à passer en modérateur !');
            }
            return $this->redirectToRoute('app_home');
       }
       return $this->render('moderation/add_modo.html.twig', 
       [
        'formModo'=>$form->createView(),
        'user'=>$user
       ]);
    }
    #[Route('/modo/moderation', name: 'app_moderation')]
    public function moderer(MusiqueRepository $repo, Request $request): Response
    {
        $musiques = $repo->findMusique($request->query->getInt('page', 1));

        return $this->render('moderation/index.html.twig', [
            'musiques' => $musiques,
        ]);
    }
    #[Route('/modo/ajout/moderation/{id}', name: 'app_ajout_moderation')]
    public function Ajout( MusiqueRepository $repo,MailerInterface $mailer, Musique $musiques): Response
    {
        $musique = $repo->find($musiques->getId());
        $moderation= true;
        $musique->setModeration($moderation);
        $email = (new Email())
        ->from('admin@v2vmusic.fr')
        ->to($musique->getUser()->getEmail())
        ->subject('No-Reply - Modération de musique')
        ->text('Bonjour, Votre musique ' .  $musique->getTitre() . ' a été mise en ligne sur notre site V2VMusic. Cordialement, l\'équipe V2VMusic');

        $mailer->send($email);
        $repo->save($musique,true);
        
        return $this->redirectToRoute('app_moderation');
    }
}
