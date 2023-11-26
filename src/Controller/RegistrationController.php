<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Genre;
use App\Form\ProfilType;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/inscription', name: 'app_inscription')]
    public function register(Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $user=new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $file =$form->get('photo')->getData();

            if($file instanceof UploadedFile){
                
                $fileName=$slugger->slug($user->getPseudo()). uniqid() . "." . $file->guessExtension();
                
                    try{
                    /*on déplace l'image uploadé dans le dossier configuré dans les paramètres (voir service.yaml)
                    avec le nom $filename*/
                    $file->move($this->getParameter('photos_profil'), $fileName);
                    }catch(FileException $e){
                        //gestion des erreurs d'upload
                
                    }
                    // on affecte le nom de l'image '$filename' à la propriété photo du produit pour l'envoie en bdd
                $user->setPhoto($fileName);
            }
            else{
                $user->setPhoto('profil.png');
            }


            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $genresUser= $form->get('genres')->getData();
            foreach($genresUser as $genre){
                $user->addGenre($genre);
            };
          
            $em->persist($user);
            $em->flush();
        

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@v2vmusic.fr', 'No Reply'))
                    ->to($user->getEmail())
                    ->subject('Confirmation de votre email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            $this->addFlash('error', 'Pensez à verifier votre adresse mail');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_home');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre email a bien été vérifié.');

        return $this->redirectToRoute('app_home');
    }

//-------------------------------GESTION USERS EN ADMIN-------------------------------------

    #[Route('admin/allUsers', name: 'app_allUsers')]
    public function AllUser(UserRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        $users= $repo->findUsers($request->query->getInt('page', 1));

        return $this->render('user/UserAdmin.html.twig', [
            'users'=>$users,
          
        ]);
    }

    #[Route('admin/delete_user_{id}', name: 'app_deleteAdmin_user')]
    public function deleteAdmin(UserRepository $repoUser, User $users): Response
    {
        $user = $repoUser->find($users->getId());
        $repoUser ->remove($user , 1);
        
    return $this->redirectToRoute('app_allUsers');
    }

}