<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Musique;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\MusiqueRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire_{id}', name: 'app_formCommentaire')]
    public function Commentaire( CommentaireRepository $comRepo, Request $request, MusiqueRepository $musiqueRepo, Musique $musiques, MailerInterface $mailer)
    {
        $commentaire = new Commentaire();
        $musique = $musiqueRepo->find($musiques->getId());
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setDateDeCreation(new DateTime("now"));
            $commentaire->setUser($this->getUser());
            $commentaire->setMusique($musique);

            $email = (new Email())
            ->from('admin@v2vmusic.fr')
            ->to($musique->getUser()->getEmail())
            ->subject('No-Reply - Commentaire')
            ->text('Bonjour,' . $commentaire->getUser()->getPrenom(). ' ' .'a commenté votre musique ' .' '. $musique->getTitre() .' : ' . $commentaire->getContent() .' '. 'et la note est '.  $commentaire->getNote(). '/5'. ' ' .'Cordialement, l\'équipe V2VMusic.');
    
            $mailer->send($email);
            $comRepo->save($commentaire, true);
            $this->addFlash('success', 'Votre commentaire a été posté');
            return $this->redirectToRoute('app_oneMusique', array('id' => $musique->getId()));
        }
        return $this->render('commentaire/formCommentaire.html.twig', [
            'formCommentaire' => $form->createView(),
        ]);
    }
    //-----------------------------------------CommentaireParUtilisateur----------------------------------------
    #[Route('profile/commentaires/{id}', name: 'app_commentaire_user')]
    public function commentairesbyUser(UserRepository $repo, User $users)
    {
        if ($this->getUser() !== $users) {
            return $this->redirectToRoute('app_home');
        }

        $user = $repo->find($this->getUser());
        $commentaires = $user->getCommentaires();
        $users = $repo->findAll();
        return $this->render('commentaire/commentaire.html.twig', ['users' => $users, 'commentaires' => $commentaires]);
    }

    //------------------------------------------Commentaire Admin ---------------------------------------------

    #[Route('/admin/allCommentaires', name: 'app_allCommentaires')]
    public function MusiqueAdmin(CommentaireRepository $repo, Request $request)
    {
        $commentaires = $repo->findCommentaires($request->query->getInt('page', 1));
        return $this->render('commentaire/adminCommentaire.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }

    #[Route('/admin/delete_commentaires_{id}', name: 'app_deleteAdmin_commentaire')]
    public function deleteAdmin(CommentaireRepository $repo, Commentaire $commentaires)
    {
        $commentaire = $repo->find($commentaires->getId());
        $repo->remove($commentaire, 1);

        return $this->redirectToRoute('app_allCommentaires');
    }
}
