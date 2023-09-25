<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\MusiqueRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(GenreRepository $repo, MusiqueRepository $repoM, UserRepository $userRepository ): Response
    {
        $musiques= $repoM->findby([], ['id'=>'DESC'], 5 );
        $genres= $repo->findAll(); 
        $activeUsers = $userRepository->findByIsActive();
       
        return $this->render('home/home.html.twig', [
            'genres' => $genres,
            'musiques'=>$musiques,
            'activeUsers' => $activeUsers,
        ]);
    }
    #[Route('/apropos', name: 'app_propos')]
    public function apropos(): Response
    {
        return $this->render('home/APropos.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/profile/commentaire', name: 'app_commentaire')]
    public function commentaire()
    {
        return $this->render('commentaire.html.twig');
    }
}

