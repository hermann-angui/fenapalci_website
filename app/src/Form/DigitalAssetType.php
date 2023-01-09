<?php

namespace App\Form;

use App\Entity\DigitalAsset;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DigitalAssetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('path')
            ->add('description')
            ->add('supplier')
            ->add('created_at')
            ->add('modified_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DigitalAsset::class,
        ]);
    }
}
