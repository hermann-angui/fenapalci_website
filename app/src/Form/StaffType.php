<?php

namespace App\Form;

use App\Entity\Staff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $past = new \DateTime('- 80 years');
        $end = new \DateTime();

        $countries = array_combine(array_values(Countries::getNames()), array_values(Countries::getNames()));

        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
                'mapped' => true,
                'required' => true
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'mapped' => true,
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'mapped' => true,
                'required' => true
            ])
            ->add('place_of_birth', TextType::class, [
                'label' => 'Lieu de naissance',
                'mapped' => true,
                'required' => true
            ])
            ->add('date_of_birth',DateType::class, [
                'label' => 'Date de naissance',
                'mapped' => true,
                'years' => range($past->format('Y'), $end->format('Y')),
            ])
            ->add('nationality', CountryType::class, [
                'label' => 'Votre nationalité',
                'mapped' => false,
                'required' => true,
                'choices' => $countries,
                'choice_loader' => null
            ])
            ->add('sex')
            ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
        ]);
    }
}
