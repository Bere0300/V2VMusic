<?php

namespace App\Controller;

use App\Repository\MusiqueRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil_Artiste', name: 'app_artiste')]
    public function index(MusiqueRepository $repo): Response
    {
        $musiques = $repo->findAll();
        return $this->render('profil/profilArtiste.html', [
            'musiques' => $musiques,
        ]);
    }
    #[Route('/profil_Auditeur', name: 'app_auditeur')]
    public function auditeur(): Response
    {
        return $this->render('profil/profilAuditeur.html', [
            'controller_name' => 'ProfilController',
        ]);
    }



  
 

}
