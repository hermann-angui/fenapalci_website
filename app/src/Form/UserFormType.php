<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $past = new \DateTime('- 80 years');
        $end = new \DateTime();
        $countries = array_combine(array_values(Countries::getNames()), array_values(Countries::getNames()));

        $builder
            ->add('email',EmailType::class,['required' => true])
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer votre mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstname',TextType::class)
            ->add('lastname',TextType::class)
            ->add('maiden_name',TextType::class)
            ->add('middlename',TextType::class)
            ->add('sex', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'monsieur' => 'Homme',
                    'madame' => 'Femme',
                ],
                'empty_data' => 'Homme',
                'data' => 'Homme',
            ])
            ->add('place_of_birth', TextType::class)
            ->add('date_of_birth', DateType::class, [
                'label' => 'Date de naissance',
                'mapped' => true,
                'years' => range($past->format('Y'), $end->format('Y')),
            ])
            ->add('country', ChoiceType::class, [
                'label' => 'Country of residence',
                'mapped' => true,
                'choices' => $countries,
                'choice_loader' => null
            ])
            ->add('address', TextType::class)
            ->add('phone_number', TelType::class)
            ->add('photo', FileType::class)
            ->add('isVerified', CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
