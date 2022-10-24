<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $past = new \DateTime('- 80 years');
        $end = new \DateTime();

        $builder
            ->add('name', TextType::class, [
                'mapped' => true,
            ])
            ->add('ville', TextType::class, [
                'mapped' => true,
            ])
            ->add('commune', TextType::class, [
                'mapped' => true,
            ])
            ->add('registre_commerce', TextType::class, [
                'mapped' => true,
            ])
            ->add('quartier', TextType::class, [
                'mapped' => true,
            ])
            ->add('phone_number', TelType::class, [
                'mapped' => true,
            ])
            ->add('address', TextType::class, [
                'mapped' => true,
            ])
            ->add('legal_status', ChoiceType::class, [
                'label' => "Status juridique",
                'mapped' => true,
                'required' => false,
                'choices' => [
                    "" => "Sélectionnez le status juridique",
                    "SARL" => "Société à responsabilité limitée",
                    "SA" => "Société anonyme",
                    "EI" => "Entreprise individuelle",
                    "EURL" => "Entreprise unipersonnelle à responsabilité limitée"
                ],
                'empty_data' => '',
                'data' => '',
            ])
            ->add('date_created', DateType::class, [
                'mapped' => true ,
                'years' => range($past->format('Y'), $end->format('Y')),
            ])
            ->add('category', ChoiceType::class, [
                'mapped' => true,
                'choices' => Company::getCategories()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
