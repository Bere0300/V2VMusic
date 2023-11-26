<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Services\MusiqueService;
use App\Repository\GenreRepository;
use App\Repository\MusiqueRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreController extends AbstractController
//--------------------------------------------------------Gestion admin des genres--------------------------------------
{
    #[Route('admin/genre', name: 'app_genre')]
    public function index(GenreRepository $repo, Request $request, SluggerInterface $slugger): Response
    {        
        $genres= $repo->findAll();
        $genre= new Genre(); 
        $form=$this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $genre->setSlug($slugger->slug($genre->getNom()));
            $repo->save($genre,true);
            return $this->redirectToRoute('app_genre');
        }

        return $this->render('genre/adminGenre.html.twig', [
            'genres' => $genres,
            'formGenre'=> $form->createView()
            
        ]);
    }
    #[Route('/admin/delete_genre_{id}', name: 'app_delete_genre')]
    public function deleteGenre(GenreRepository $repo, Genre $genres)
    {
        $genre= $repo->find($genres->getId());
        $repo->remove( $genre , 1);

        return $this->redirectToRoute('app_genre');
    }
  
    #[Route('/admin/update_genre_{id}', name: 'app_update_genre')]
    public function updateGenre(GenreRepository $repo, Request $request, SluggerInterface $slugger, Genre $genres): Response
    {        
        $genre= $repo->find($genres->getId());
        $form=$this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $genre->setSlug($slugger->slug($genre->getNom()));
            
            $repo->save($genre,true);
            return $this->redirectToRoute('app_genre');
        }

        return $this->render('genre/adminGenre.html.twig', [
            'genre' => $genre,
            'formGenre'=> $form->createView()
            
        ]);
    }

    // ---------------------------------pages des genres ---------------------------

    #[Route('/genre_musiques_{id}', name: 'app_genre_view')]
    public function View(GenreRepository $repo, UserRepository $userRepository, MusiqueRepository $musiqueRepo, Genre $genres)
    {
        $genre= $repo->find($genres->getId());
        $musiques = $genre->getMusiques();
        $artistes= $genre->getUsers();
        $genres = $repo->findAll();
        $user = $userRepository->findAll();
        $musiques= $musiqueRepo ->findMusiqueByGenre($genre);

        return $this->render('genre/OneGenre.html.twig', [
            'genre'=>$genre,
            'musiques'=>$musiques,
            'genres'=>$genres,
            'artistes'=>$artistes,
            'user'=> $user
        ]);
    }

    //-----------------Artiste par genre----------------------------------------
    #[Route('/genre_artiste_{id}', name: 'app_artiste_genre')]
    public function artisteGenre(GenreRepository $repo, Genre $genres)
    {
        $genre= $repo->find($genres->getId());
        $users = $genre->getUsers();
        $genres = $repo-> findAll();

        return $this->render('genre/artistebyGenre.html.twig', ['genre' => $genre, "genres"=>$genres, 'users'=>$users]);
    }
   
    
   
 

}
