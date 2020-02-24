<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet', TextType::class,[
                'label' => "Votre sujet",
                'attr' => [
                    'placeholder' => "Tapez votre sujet",
                    'class' => "form-control",
                ],
                'constraints' => [
                    new NotNull([
                        'message' => "Saisir votre mot de passe",
                    ]),
                    new NotBlank([
                        'message' => "Saisir votre mot de passe",
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Votre email",
                'attr' => [
                    'placeholder' => "Tapez votre adresse email",
                    'class' => "form-control",
                ],
                'constraints' => [
                    new NotNull([
                        'message' => "Saisir votre mot de passe",
                    ]),
                    new NotBlank([
                        'message' => "Saisir votre mot de passe",
                    ]),
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => "Votre message",
                'attr' => [
                    'placeholder' => "Tapez votre message",
                    'class' => "form-control",
                ],
                'constraints' => [
                    new NotNull([
                        'message' => "Saisir votre mot de passe",
                    ]),
                    new NotBlank([
                        'message' => "Saisir votre mot de passe",
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
