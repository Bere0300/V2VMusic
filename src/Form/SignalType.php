<?php

namespace App\Form;

use App\Entity\Signalement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SignalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label'=> 'Votre email',
            'constraints'=>[
                new Email()
            ]
        ])
        ->add('sujet',ChoiceType::class, [
            'label'=>'Sujet du signalement',
            'placeholder' => 'Sélectionner un sujet',
            'choices'=>[
                'Propos inapproprié'=> 'Propos inapproprié',
                'Titre déjà exitant'=> 'Titre déjà exitant',
                'Discours haineux'=>'Discours haineux',
                'Discrimination'=>'Discrimination',
                'Autres'=>'Autres',
            ]
        ])
        ->add('contenu', TextType::class, [
            'label'=>'Commentaire(si besoin)',
            'required'=>false 
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Signalement::class,
        ]);
    }
}
