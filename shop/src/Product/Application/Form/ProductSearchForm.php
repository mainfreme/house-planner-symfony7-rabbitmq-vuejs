<?php

declare(strict_types=1);

namespace App\Product\Application\Form;

use App\Product\Domain\Entity\ProductType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'required' => false,
            ])
            ->add('type', EntityType::class, [
                'class' => ProductType::class,
                'choice_label' => 'name',
                'label' => 'Product Type',
                'placeholder' => 'Choose a type',
                'required' => false,
            ])
            ->add('priceMin', TextType::class, [
                'label' => 'Min Price',
                'required' => false,
            ])
            ->add('priceMax', TextType::class, [
                'label' => 'Max Price',
                'required' => false,
            ])
            ->add('isActive', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'All' => null,
                    'Active' => true,
                    'Inactive' => false,
                ],
                'required' => false,
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Search',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'csrf_protection' => true,
        ]);
    }
}
