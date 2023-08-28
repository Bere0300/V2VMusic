<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\MusiqueRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(GenreRepository $repo): Response
    {
        
        $genres= $repo->findAll();
        return $this->render('home/home.html', [
            'genres' => $genres,
        ]);
    }
    #[Route('/apropos', name: 'app_propos')]
    public function apropos(): Response
    {
        return $this->render('home/APropos.html', [
            'controller_name' => 'HomeController',
        ]);
    }
}

