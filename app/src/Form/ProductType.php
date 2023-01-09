<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product_sku')
            ->add('product_name')
            ->add('product_supplier')
            ->add('product_description')
            ->add('product_unit_price')
            ->add('product_unit_in_stock')
            ->add('product_sell_price')
            ->add('product_supplier_price')
            ->add('created_at')
            ->add('modified_at')
            ->add('product_pictures')
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
