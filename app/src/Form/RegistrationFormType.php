<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sex', TextType::class, [
                'label' => 'Sexe',
                'mapped' => true,
                'required' => true
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénoms',
                'mapped' => true,
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'mapped' => true,
                'required' => true
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Téléphone',
                'mapped' => true,
                'required' => true
            ])
            ->add('dateofBirth', DateType::class, [
                'label' => 'Date de naissance',
                'mapped' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'mapped' => true,
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'label' => 'Mot de passe',
                'mapped' => true,
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans('invalid_password'),
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('password_is_blank'),
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => $this->translator->trans('invalid_password_length'),
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('nationality', TextType::class, [
                'label' => 'Votre nationalité',
                'mapped' => true,
                'required' => true
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'mapped' => true,
                'required' => true
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => $this->translator->trans('general_term_gdpr'),
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => $this->translator->trans('invalid_password'),
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
