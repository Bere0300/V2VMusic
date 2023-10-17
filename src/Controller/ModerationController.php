<?php

namespace App\Controller;

use App\Form\ModoType;
use App\Form\AdminType;
use App\Repository\UserRepository;
use App\Repository\MusiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModerationController extends AbstractController
{

    #[Route(path: '/admin/passer_en_modo_{id}', name: 'app_modo')]
    public function addAdmin($id, Request $request, UserRepository $repo)
    {
       $secret="v2vmusic";
       $user= $repo->find($id);
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
    public function moderer(MusiqueRepository $repo): Response
    {
        $musique = $repo->findAll();

        return $this->render('moderation/index.html.twig', [
            'musique' => $musique,
        ]);
    }
    #[Route('/modo/ajout/moderation/{id}', name: 'app_ajout_moderation')]
    public function Ajout($id, MusiqueRepository $repo): Response
    {
        $musique = $repo->find($id);
        $moderation= true;
        $musique->setModeration($moderation);
        $repo->save($musique,true);
        
        return $this->redirectToRoute('app_moderation');
    }
}
