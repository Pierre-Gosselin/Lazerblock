<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UpdateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class,[
                'label' => "Nom",
                'attr' => [
                    'placeholder' => "Tapez votre nom",
                    'class' => "form-control mb-3",
                ],
            ])
            ->add('firstname', TextType::class,[
                'label' => "Prenom",
                'attr' => [
                    'placeholder' => "Tapez votre prénom",
                    'class' => "form-control mb-3",
                ],
            ])
            ->add('newsletter', CheckboxType::class, [
                'label' => "Inscription à la newsletter",
                'attr' => [
                    'class' => "form-control mb-3",
                ],
                'required' => false,
            ])
            ->add('side', ChoiceType::class, [
                'label' => "De quel côté de la Force êtes-vous ?",
                'attr' => [
                    'class' => "form-control mb-3",
                ],
                'choices' => [
                    'Jedi' => true,
                    'Sith' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
