<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\UserRepository;
use App\Repository\CommentaireRepository;
use App\Repository\MusiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire_{id}', name: 'app_formCommentaire')]
    public function Commentaire($id, CommentaireRepository $comRepo, Request $request, MusiqueRepository $musiqueRepo)
    {
        $commentaire =new Commentaire();
        $musique= $musiqueRepo->find($id);
        $form=$this->createForm(CommentaireType::class, $commentaire);
        $form-> handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $commentaire->setDateDeCreation(new DateTime("now"));
            $commentaire->setUser($this->getUser());
            $commentaire->setMusique($musique);
            $comRepo->save($commentaire,true);
            return $this->redirectToRoute('app_oneMusique', array('id'=>$musique->getId()));
        }
        return $this->render('commentaire/formCommentaire.html.twig', [
            'formCommentaire'=>$form->createView(),
        ]);
    }
 //-----------------------------------------CommentaireParUtilisateur----------------------------------------
    #[Route('/commentaires/{id}', name: 'app_commentaire_user')]
    public function commentairesbyUser($id,UserRepository $repo)
    {
        $user= $repo->find($id);
        $commentaires= $user->getCommentaires();
        $users=$repo ->findAll();
        return $this->render('commentaire/commentaire.html.twig',['users'=>$users, 'commentaires'=>$commentaires]);
    }
    //-----------------------------------------CommentaireParMusique----------------------------------------
    #[Route('/commentaires/musique/{id}', name: 'app_commentaire_musique')]
    public function commentairesbyMusique($id,MusiqueRepository $repo)
    {
        $musique= $repo->find($id);
        $commentaires= $musique->getCommentaires();
        $musiques=$repo ->findAll();
        return $this->render('musique/oneMusique.html.twig',['musiques'=>$musiques, 'commentaires'=>$commentaires]);
    }

    // #[Route('/commentaires/profil/{id}', name: 'app_commentaire_user')]
    // public function commentairesprofil($id,UserRepository $repo, CommentaireRepository $comRepo)
    // {
    //     $user= $repo->find($id);
    //     $commentaires= $user->getCommentaires();
    //     $commentaire= $comRepo->findby([], ['id'=>'desc'], 2);
    //     return $this->render('profil/profil.html.twig',['commentaire'=>$commentaire, 'commentaires'=>$commentaires]);
    // }

}
