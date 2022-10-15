<?php

namespace App\Form;

use App\Entity\Staff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstname')
            ->add('lastname')
            ->add('place_of_birth')
            ->add('date_of_birth')
            ->add('nationality')
            ->add('sex')
            ->add('phone_number')
            ->add('address')
            ->add('photo')
            ->add('type')
            ->add('status')
            ->add('created_at')
            ->add('modified_at')
            ->add('company')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
        ]);
    }
}
