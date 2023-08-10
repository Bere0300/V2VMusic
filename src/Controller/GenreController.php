<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreController extends AbstractController
{
    #[Route('/genre', name: 'app_genre')]
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

        return $this->render('genre/genre.html', [
            'genres' => $genres,
            'formGenre'=> $form->createView()
            
        ]);
    }
    #[Route('/delete_genre_{id}', name: 'app_delete_genre')]
    public function deleteGenre($id, GenreRepository $repo)
    {
        $genre= $repo->find($id);
        $repo->remove($genre,1);

        return $this->redirectToRoute('app_genre');
    }
  
    #[Route('/update_genre_{id}', name: 'app_update_genre')]
    public function updateGenre($id,GenreRepository $repo, Request $request, SluggerInterface $slugger): Response
    {        
        $genre= $repo->find($id);
        $form=$this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $genre->setSlug($slugger->slug($genre->getNom()));
            
            $repo->save($genre,true);
            return $this->redirectToRoute('app_genre');
        }

        return $this->render('genre/genre.html', [
            'genre' => $genre,
            'formGenre'=> $form->createView()
            
        ]);
    }
    #[Route('/genre_{id}', name: 'app_genre_view')]
    public function View($id, GenreRepository $repo)
    {
        $genre= $repo->find($id);

        return $this->render('genre/OneGenre.html', [
            'genre'=>$genre
        ]);
    }
    #[Route('/genre_artiste_{id}', name: 'app_artiste_genre')]
    public function artisteGenre($id, GenreRepository $repo)
    {
        $genre= $repo->find($id);
        return $this->render('genre/artisteGenre.html', ['genre' => $genre]);
    }

}
