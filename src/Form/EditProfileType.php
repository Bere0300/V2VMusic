<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditProfileType extends AbstractType
{
    public function __construct(RequestStack $requestStack) //construct : fonction qui d'excéute auto lors de l'instanciation
    {
        $this->requestStack = $requestStack;
       
      
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $request=$this->requestStack->getCurrentRequest();
        $isRequired=false;

        if($request->getPathInfo()=='/profil')
        {
            $isRequired= true;
        }

        $builder
            ->add('nom')
            ->add('prenom')
            ->add('photo', FileType::class, [
                'mapped'=>false,
                'required'=>$isRequired,
            ])
            ->add('pseudo')
            ->add('biographie', TextareaType::class )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
