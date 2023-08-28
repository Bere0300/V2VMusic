<?php

namespace App\Controller;

use App\Entity\Musique;
use App\Form\MusiqueType;
use App\Repository\MusiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MusiqueController extends AbstractController
{
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
}
