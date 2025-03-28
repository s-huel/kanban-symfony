<?php

namespace App\Form;

use App\DTO\UserRegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Full Name',
            ])

            ->add('email', TextType::class, [
                'label' => 'Email Address',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm Password',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationDTO::class,
        ]);
    }
}
