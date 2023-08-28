<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationFormType extends AbstractType
{ 
    public function __construct(RequestStack $requestStack) //construct : fonction qui d'excéute auto lors de l'instanciation
        {
            $this->requestStack = $requestStack;
           
          
        }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    
            $request=$this->requestStack->getCurrentRequest();
            $isRequired=false;
    
            if($request->getPathInfo()=='/app_user_update')
            {
                $isRequired= true;
            }
    
            $builder
                ->add('nom', TextType::class,[
                    'label'=> 'Nom *'
                ])
                ->add('prenom', TextType::class, [
                    'label'=> 'Prénom *'
                ])
                ->add('date_de_naissance', BirthdayType::class, [
                    'format'=> 'dMy',
                    'placeholder'=> ['year'=>'Année' , 'month'=> 'Mois', 'day'=>'Jour' ],
                    'label'=> 'Date de Naissance *'
                ])
                ->add('photo', FileType::class, [
                    'label'=>'Photo de profil * ',
                    'constraints'=> [
                        new File([
                            'maxSize'=>'1024k',
                            'mimeTypes'=>[
                                'image/jpeg',
                                'image/png',
                            ]
                        ])
                    ],
                    'mapped'=>false,
                    'required'=>$isRequired
                ]) 
                ->add('pseudo', TextType::class,[
                    'label'=> 'Pseudo *'
                ])
                ->add('email', EmailType::class,[
                    'label'=> 'Adresse Mail * '
                ])
                ->add('profil', ChoiceType::class, [
                    'choices'=> [
                        'Artiste'=>true,
                        'Auditeur'=>false,
                    ],
                    'label'=>'Choisissez votre profil *'
                ])
                ->add('agreeTerms', CheckboxType::class, [
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue([
                            'message' => 'You should agree to our terms.',
                        ]),
                    ],
                ])
                ->add('plainPassword', PasswordType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ])
              
            ;
        }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

// 