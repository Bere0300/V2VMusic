<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Genre;
use Symfony\Component\Mime\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
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
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom *'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom *'
            ])
            ->add('date_de_naissance', BirthdayType::class, [
                'format' => 'dMy',
                'placeholder' => ['year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'],
                'label' => 'Date de Naissance *'
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de profil ',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ]
                    ])
                ],
                'mapped' => false,
                'required' => false
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo *'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse Mail * ',
                'constraints' => [
                    new Email()
                ]
            ])
            ->add('profil', ChoiceType::class, [
                'choices' => [
                    'Artiste' => true,
                    'Auditeur' => false,
                ],
                'label' => 'Choisissez votre profil *'
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label'=> "J'accepte les conditions",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez acceptés les conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe', 
                'constraints'=> [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir {{ limit }} caratères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex('#.*[a-z].*#'),
                    new Regex('#.*[A-Z].*#'),
                    new Regex('#.*[0-9].*#')
                ] ],
                'second_options' => ['label' => 'Confirmation de mot de passe'],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
            ])
            ->add('genres', EntityType::class, [
                'label'=>'Quel(s) est votre genre de musique?',
                'class' => Genre::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

// 