<?php

namespace App\Controller;

use App\Entity\Musique;
use App\Form\MusiqueType;
use App\Repository\MusiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MusiqueController extends AbstractController
{
    #[Route('/musique', name: 'app_musique')]
    public function all(MusiqueRepository $repo, Request $request): Response
    {
        $musique=new Musique();
        $form=$this->createForm(MusiqueType::class, $musique);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $repo->save($musique,true);
            return $this->redirectToRoute('app_home');
        }
        return $this->render('musique/importation.html.twig', [
            'formMusique'=>$form->createView()
        ]);
    }
}
