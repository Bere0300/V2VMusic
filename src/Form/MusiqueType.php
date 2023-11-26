<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Musique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class MusiqueType extends AbstractType
{
    public function __construct(RequestStack $requestStack) //construct : fonction qui d'excéute auto lors de l'instanciation
    {
        $this->requestStack = $requestStack;
       
      
    }
  
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $request=$this->requestStack->getCurrentRequest();
        $isRequired=false;

        if($request->getPathInfo()=='/musique')
        {
            $isRequired= true;
        }

        $builder
            ->add('photo', FileType::class, [
                'label'=>'Photo de couverture',
                'constraints'=> [
                    new File([
                        'maxSize'=>'2M',
                        'extensions'=>[
                            'jpeg',
                            'png',
                        ]
                    ])
                ],
                'required'=>$isRequired,
                'mapped'=>false
            ])
            ->add('fichier', FileType::class, [
                'label'=> 'Choisir la musique',
                'constraints'=> [
                    new File([
                        'maxSize'=>'50M',
                        'extensions'=>[
                            'mp3',
                            'mp4'
                        ]
                    ])
                ],
                'required'=>$isRequired,
                'mapped'=>false
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
