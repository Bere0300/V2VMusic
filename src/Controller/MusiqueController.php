<?php

namespace App\Controller;

use DateTime;
use App\Entity\Musique;
use App\Form\MusiqueType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\UserRepository;
use App\Repository\GenreRepository;
use App\Repository\MusiqueRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MusiqueController extends AbstractController
{

//---------------------------------------AJOUT D'UNE MUSIQUE--------------------------------------------------
    #[Route('/musique', name: 'app_musique')]
    public function new(MusiqueRepository $repo, Request $request, SluggerInterface $slugger): Response
    {
        $musique=new Musique(); 
        $musique->setUser($this->getUser());
        $form=$this->createForm(MusiqueType::class, $musique);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file2=$form->get('fichier')->getData();
            $file =$form->get('photo')->getData();
            $fileName=$slugger->slug($musique->getNom()). uniqid() . "." . $file->guessExtension();
            $fileName2=$slugger->slug($musique->getTitre()). uniqid() . "." . $file2->guessExtension();
            try{
                /*on déplace l'image uploadé dans le dossier configuré dans les paramètres (voir se'rvice.yaml)
                avec le nom $filename*/
                $file->move($this->getParameter('photos_musique'), $fileName);
                $file2->move($this->getParameter('fichiers_musique'), $fileName2);
            }catch(FileException $e){
                //gestion des erreurs d'upload

            }
            $musique->setPhoto($fileName);
            $musique->setFichier($fileName2);
            $repo->save($musique,true);
            return $this->redirectToRoute('app_artiste');
        }
        return $this->render('musique/importation.html.twig', [
            'formMusique'=>$form->createView()
        ]);
    }
//-----------------------------GESTION DES MUSIQUES COTE USER-------------------------------------
    #[Route('/all_musique_{id}', name: 'app_all_musique')]
    public function All($id, MusiqueRepository $repo)
    {
        $musiques = $repo->find($id);

        return $this->render('musique/allMusique.html.twig', 
        [
            'musiques' => $musiques
        ]);
    }


    #[Route('/delete_musique_{id}', name: 'app_delete_musique')]
    public function delete($id, MusiqueRepository $repo)
    {
        $musique = $repo->find($id);
        $repo->remove( $musique , 1);

        return $this->redirectToRoute('app_all_musique');
    }
    
    #[Route('/update_musique_{id}', name: 'app_update_musique')]
    public function update($id, MusiqueRepository $repo, Request $request, SluggerInterface $slugger)
    {
        $musique= $repo->find($id);
        $form=$this->createForm(MusiqueType::class, $musique);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file2=$form->get('fichier')->getData();
            $file =$form->get('photo')->getData();

            if($file && $file2) {
                $fileName=$slugger->slug($musique->getNom()). uniqid() . "." . $file->guessExtension();
                $fileName2=$slugger->slug($musique->getTitre()). uniqid() . "." . $file2->guessExtension();
                try{
                    /*on déplace l'image uploadé dans le dossier configuré dans les paramètres (voir service.yaml)
                    avec le nom $filename*/
                    $file->move($this->getParameter('photos_musique'), $fileName);
                    $file2->move($this->getParameter('fichiers_musique'), $fileName2);
                }catch(FileException $e){
                    //gestion des erreurs d'upload

                }
                $musique->setPhoto($fileName);
                $musique->setFichier($fileName2);
            }
           
            $repo->save($musique,true);
            return $this->redirectToRoute('app_artiste');

        }

            return $this->render('musique/importation.html.twig', [
                'formMusique'=>$form->createView()
            ]);
    }

//---------------------------MUSIQUE PAR GENRE --------------------------------------------------

    #[Route('/musique_genre_{id}', name: 'app_musique_genre')]
    public function musiqueByGenre($id, GenreRepository $repo,)
    {
        $genre = $repo->find($id);
        $musiques= $genre->getMusiques();
        $genres = $repo->findAll();

        return $this->render('musique/musiqueByGenre.html.twig', [
            'genres'=>$genres,
            'musiques'=>$musiques,
            'genre' =>$genre,
        ]);
    }

    //-----------------------ECOUTE D'UNE MUSIQUE--------------------------------

    #[Route('/oneMusique_{id}', name: 'app_oneMusique')]
    public function OneMusique($id, MusiqueRepository $repo)
    {
        $musique = $repo->find($id);
        $favoris = $musique->getFavoris();

        return $this->render('musique/oneMusique.html.twig', [
            'musique'=>$musique,
            'favoris'=>$favoris

        ]);
    }
    //--------------------------GESTION ADMIN---------------------------------------

    #[Route('/admin/allMusiques', name: 'app_allMusiques')]
    public function MusiqueAdmin(MusiqueRepository $repo, GenreRepository $genreRepo)
    {
        $musiques = $repo->findAll();
        return $this->render('musique/musiqueAdmin.html.twig', [
            'musique'=>$musiques,
        ]);
    }

    #[Route('/admin/delete_musique_{id}', name: 'app_deleteAdmin_musique')]
    public function deleteAdmin($id, MusiqueRepository $repo)
    {
        $musique = $repo->find($id);
        $repo->remove( $musique , 1);

        return $this->redirectToRoute('app_allMusique');
    }
    //-------------------------AJOUT et RETRAIT FAVORIS-----------------------------

    #[Route('/profile/ajoutFavoris_{id}', name: 'app_favoris')]
    public function ajoutFavoris(Musique $musique, MusiqueRepository $repo)
    {
        if(!$musique){
            throw new NotFoundHttpException('Pas de musique trouvée');
        }
        $musique->addFavori($this->getUser());
        $repo->save($musique,1);
        $this->addFlash('success', 'Musique ajoutée à vos favoris');
        return $this->redirectToRoute('app_oneMusique', array('id'=>$musique->getId()));
    }
    
    #[Route('/profile/retraitFavoris_{id}', name: 'app_retrait_favoris')]
    public function retraitFavoris(Musique $musique, MusiqueRepository $repo)
    {   
        if(!$musique){
            throw new NotFoundHttpException('Pas de musique trouvée');
        }
        $musique->removeFavori($this->getUser());
        $repo->save($musique,1);
        $this->addFlash('error', 'Musique retirée de vos favoris');
        return $this->redirectToRoute('app_oneMusique', array('id'=>$musique->getId()));
    }



    //----------------------------AFFICHAGE FAVORIS-------------------------------------
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
