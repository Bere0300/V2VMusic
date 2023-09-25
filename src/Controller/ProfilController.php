<?php

namespace App\Controller;


use App\Form\EditProfileType;
use App\Repository\UserRepository;
use App\Repository\MusiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfilController extends AbstractController
{
    #[Route('/profile/profil_{id}', name: 'app_profil')]
    public function index($id,MusiqueRepository $repo, UserRepository $userRepo): Response
    {
        $musiques = $repo->findAll();
        $user= $userRepo->find($id);
        $favoris = $user->getFavoris(); 
        return $this->render('profil/profil.html.twig', [
            'musiques' => $musiques,
            'user'=> $user,
            'favoris'=>$favoris
        ]);
    }
    

    #[Route('/profil/{id}', name: 'app_view_profil')]
    public function viewProfile($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $musiques= $user->getMusiques();


        return $this->render('profil/oneArtiste.html.twig', [
            'user' => $user,
            'musiques'=>$musiques
        ]);
    }

    //------------------------------------------EDIT PROFILE------------------------
    #[Route('profile/editProfile/{id}', name: 'app_edit_profil')]
    public function updateProfil($id, UserRepository $repo, Request $request, SluggerInterface $slugger)
    {
        $profil= $repo->find($id);
        $form=$this->createForm(EditProfileType::class, $profil);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file =$form->get('photo')->getData();

            if($file) {
                $fileName=$slugger->slug($profil->getPseudo()). uniqid() . "." . $file->guessExtension();
               
                try{
                    /*on déplace l'image uploadé dans le dossier configuré dans les paramètres (voir service.yaml)
                    avec le nom $filename*/
                    $file->move($this->getParameter('photos_profil'), $fileName);

                }catch(FileException $e){
                    //gestion des erreurs d'upload

                }
                $profil->setPhoto($fileName);
            }
           
            $repo->save($profil,1);
            return $this->redirectToRoute('app_profil', array('id'=>$id));

        }

            return $this->render('profil/editProfile.html.twig', [
                'formProfil'=>$form->createView()
            ]);
    }

    #[Route('/profile/favoris_{id}', name: 'app_affichage_favoris')]
    public function afficherFavoris($id, UserRepository $repo)
    {
        $user= $repo->find($id);
        $favoris = $user->getFavoris(); 

        return $this->render('musique/favoris.html.twig', [
            'user'=> $user,
            'favoris'=>$favoris

        ]);
    }
}
