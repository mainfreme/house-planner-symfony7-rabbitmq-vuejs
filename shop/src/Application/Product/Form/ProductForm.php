<?php

namespace App\Application\Product\Form;

use App\Domain\Product\Entity\Product;
use App\Domain\Product\Entity\ProductType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('image_id', IntegerType::class, [
                'label' => 'Image ID',
                'required' => true,
            ])
            ->add('price', TextType::class, [
                'label' => 'Price',
                'required' => true,
            ])
            ->add('type_id', EntityType::class, [
                'class' => ProductType::class,
                'choice_label' => 'name', // Pole, które będzie wyświetlane w select
                'label' => 'Product Type',
                'placeholder' => 'Choose a type', // Opcjonalnie: pusta wartość na początku
                'required' => true,
            ])
            ->add('is_active', CheckboxType::class, [
                'label' => 'Is Active?',
                'required' => false,
            ])
            ->add('parameters', CollectionType::class, [
                'label' => 'Parameters',
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Product',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
