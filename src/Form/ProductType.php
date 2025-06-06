<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Figurine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $product = $options['data'];

        $builder
            ->add('name', TextType::class)
            ->add('price', MoneyType::class)
            ->add('picture', TextType::class);

        if ($product instanceof Book) {
            $builder->add('synopsis', TextareaType::class);
        } elseif ($product instanceof Figurine) {
            $builder->add('description', TextareaType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Défini dynamiquement dans le contrôleur
        ]);
    }
}

