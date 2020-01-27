<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Email",
                'attr' => [
                    'placeholder' => "Tapez votre adresse email",
                    'class' => "form-control mb-3",
                ],
            ])
            ->add('password', RepeatedType::class, [
                'label' => false,
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => "Mot de passe",
                    'required' => true,
                    'constraints' => [
                        new NotNull([
                            'message' => "Saisir votre mot de passe",
                        ]),
                        new NotBlank([
                            'message' => "Saisir votre mot de passe",
                        ]),
                        new Length([
                            'min' => "8",
                            'minMessage' => "Votre mot de passe doit contenir au moins 8 caractères.",
                        ]),
                        new Regex([
                            "pattern" => "/^\S+$/",
                            "message" => "N'utilisez pas d'espace dans votre mot de passe."
                        ]),
                    ],
                    'attr' => [
                        'placeholder' => "Tapez votre mot de passe ...",
                        'class' => "form-control mb-3",
                    ],
                ],
                'second_options' => [
                    'label' => "Confirmer votre mot de passe",
                    'constraints' => [
                        new NotBlank([
                            'message' => "Repéter le mot de passe",
                        ]),
                    ],
                    'attr' => [
                        'placeholder' => "Confirmer votre mot de passe ...",
                        'class' => "form-control mb-3",
                    ],
                ],
                'invalid_message' => "Les mots de passe doivent être identiques.",
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom",
                'attr' => [
                    'placeholder' => "Tapez votre nom",
                    'class' => "form-control mb-3",
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => "Prenom",
                'attr' => [
                    'placeholder' => "Tapez votre prénom",
                    'class' => "form-control mb-3",
                ],
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => "Date de naissance",
                'attr' => [
                    'class' => "form-control mb-3",
                ],
                'years' => $this->getYears(),
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
            ->add('newsletter', CheckboxType::class, [
                'label' => "Inscription à la newsletter",
                'attr' => [
                    'class' => "form-control mb-3",
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function getYears()
    {
        $years = [];
        $now = date('Y');
        $now18 = $now-12;
        $range = 100;

        for ($i=$now18; $i>($now18-$range); $i--)
        {
            $years[] = $i;
        }

        return $years;
    }
}
