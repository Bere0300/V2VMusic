<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil_Artiste', name: 'app_artiste')]
    public function index(): Response
    {
        return $this->render('profil/profilArtiste.html', [
            'controller_name' => 'ProfilController',
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
