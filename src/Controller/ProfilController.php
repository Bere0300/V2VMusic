<?php

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\Mapping\Id;
use App\Form\EditProfileType;
use App\Repository\UserRepository;
use App\Repository\MusiqueRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfilController extends AbstractController
{
    #[Route('/profile/profil_{id}', name: 'app_profil')]
    public function index(MusiqueRepository $repo, UserRepository $userRepo, User $users): Response
    {
        if ($this->getUser() !== $users) {
            return $this->redirectToRoute('app_home');
        }
        $musiques = $repo->findAll();
        $user = $userRepo->find($users->getId());

        return $this->render('profil/profil.html.twig', [
            'musiques' => $musiques,
            'user' => $user,
        ]);
    }


    #[Route('/profil/{id}', name: 'app_view_profil')]
    public function viewProfile(UserRepository $userRepository, User $users): Response
    {
        $user = $userRepository->find($users->getId());

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $musiques = $user->getMusiques();


        return $this->render('profil/oneArtiste.html.twig', [
            'user' => $user,
            'musiques' => $musiques
        ]);
    }

    //------------------------------------------EDIT PROFILE------------------------
    #[Route('profile/editProfile{id}', name: 'app_edit_profil')]
    public function updateProfil(UserRepository $repo, Request $request, SluggerInterface $slugger, User $users)
    {
        
        if ($this->getUser() !== $users) {
            return $this->redirectToRoute('app_home');
        }

        $profil = $repo->find($this->getUser());

        $form = $this->createForm(EditProfileType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photo')->getData();

            if ($file) {
                $fileName = $slugger->slug($profil->getPseudo()) . uniqid() . "." . $file->guessExtension();

                try {
                    /*on déplace l'image uploadé dans le dossier configuré dans les paramètres (voir service.yaml)
                    avec le nom $filename*/
                    $file->move($this->getParameter('photos_profil'), $fileName);
                } catch (FileException $e) {
                    //gestion des erreurs d'upload

                }
                $profil->setPhoto($fileName);
            }

            $repo->save($profil, 1);
            return $this->redirectToRoute('app_profil', array('id' => $profil->getId()));
        }

        return $this->render('profil/editProfile.html.twig', [
            'formProfil' => $form->createView(),
            'profil' => $profil
        ]);
    }
    //-------------------------------Suppression du compte cote User-------------------------
    
    #[Route('/profile/user_delete_{id}', name: 'app_user_delete')]
    public function delete(UserRepository $repoUser, User $users): Response
    {
        if ($this->getUser() !== $users) {
            return $this->redirectToRoute('app_home');
        }
        $user = $repoUser->find($this->getuser());
        $repoUser->remove($user, 1);

        return $this->redirectToRoute('app_home');
    }
    //----------------AFFICHAGE FAVORIS---------------------------------------
    #[Route('/profile/favoris_{id}', name: 'app_affichage_favoris')]
    public function afficherFavoris(UserRepository $repo)
    {
        $user = $repo->find($this->getUser());
        $favoris = $user->getFavoris();

        return $this->render('musique/favoris.html.twig', [
            'user' => $user,
            'favoris' => $favoris

        ]);
    }
}
