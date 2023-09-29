<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\UserRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire_{id}', name: 'app_formCommentaire')]
    public function OneProduct($id,UserRepository $repo, CommentaireRepository $comRepo, Request $request)
    {
        $commentaire =new Commentaire();
        $user= $repo->find($id);
        $form=$this->createForm(CommentaireType::class, $commentaire);
        $form-> handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $commentaire->setDateDeCreation(new DateTime("now"));
            $commentaire->setUser($user);
            $comRepo->save($commentaire,true);
            return $this->redirectToRoute('app_home');
        }
        return $this->render('commentaire/formCommentaire.html.twig', [
            'formCommentaire'=>$form->createView(),
        ]);
    }

    #[Route('/commentaires/{id}', name: 'app_commentaire_user')]
    public function commentairesbyProduit($id,UserRepository $repo)
    {

        $user= $repo->find($id);
        $commentaires= $user->getCommentaires();
        $users=$repo ->findAll();
      

        return $this->render('commentaire/commentaire.html.twig',['users'=>$users, 'commentaires'=>$commentaires]);
    }

}
