<?php

namespace App\Form;

use App\Entity\MyPassword;
use App\Form\MyPasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class RenewPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old_password', PasswordType::class, [
                'label' => "Renseignez votre ancien mot de passe",
                'attr' => [
                    'class' => "form-control mb-3",
                    'placeholder' => "Tapez votre ancien mot de passe ...",
                ],
                'mapped' => false,
                "constraints" => [
                    new SecurityAssert\UserPassword([
                        "message" => "Votre ancien mot de passe est incorrect",
                    ]),
                ],
            ])
            ->add("password", MyPasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'label' => false,
            'attr' => [
                "novalidate" => "novalidate",
            ]
        ]);
    }
}