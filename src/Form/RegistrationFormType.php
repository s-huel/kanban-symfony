<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

// Symfony form for registration user in controller
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Email input with basic "required" validation
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Email is required']),
                ],
            ])

            // Plaintext password, not mapped to User entity directly
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Password is required']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Password should be at least 6 characters long',
                    ]),
                ],
            ])

            // Submit button
            ->add('register', SubmitType::class, ['label' => 'Sign Up']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Links this form to the User entity
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}