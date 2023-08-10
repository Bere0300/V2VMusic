<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Musique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MusiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class, [
                'label'=>'Photo de couverture',
                'constraints'=> [
                    new File([
                        'maxSize'=>'1024k',
                        'mimeTypes'=>[
                            'image/jpeg',
                            'image/png',
                        ]
                    ])
                ]
            ])
            ->add('fichier', FileType::class, [
                'label'=> 'Choisir la musique',
                'constraints'=> [
                    new File([
                        'maxSize'=>'5000k',
                        'mimeTypes'=>[
                            'audio/*'
                        ]
                    ])
                ]
            ])
            ->add('titre', TextType::class)
            ->add('nom',TextType::class, [
                'label'=> 'Nom de l\'artiste'
            ])
            ->add('genre', EntityType::class, [
                'class'=>Genre::class,
                'choice_label'=>'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musique::class,
        ]);
    }
}
