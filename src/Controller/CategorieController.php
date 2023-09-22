<?php

namespace App\Controller;

use App\Form\GenreType;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{

    #[Route('/admin/categorie', name: 'app_categorie')]

    public function All (CategorieRepository $repo, Request $request, SluggerInterface $slugger): Response
    {        
        $categories= $repo->findAll();
        $categorie= new Categorie(); 
        $form=$this->createForm(CategorieType ::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $categorie->setSlug($slugger->slug($categorie->getNom()));
            
            $repo->save($categorie,true);
            return $this->redirectToRoute('app_categorie');
        }

        return $this->render('categorie/adminCategorie.html.twig', [
            'categories' => $categories,
            'formCategorie'=> $form->createView()
            
        ]);
    }
    #[Route('/delete_categorie_{id}', name: 'app_delete_categorie')]
    public function deleteCategorie ($id, CategorieRepository $repo)
    {
        $categorie = $repo->find($id);
        $repo->remove($categorie,1); 

        return $this->redirectToRoute('app_categorie');
    }

  
}
