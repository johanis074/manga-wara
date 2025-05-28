<?php

namespace App\Form;
use App\Enum\Brand;
use App\Entity\Figurine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FigurineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('picture', FileType::class, [
                'label' => 'Image du manga (fichier)',
                'attr' => ['class' => 'form-control'],
                'required' => false,])
                
            ->add('price')
            ->add('brand',ChoiceType::class, [
                'label' => 'Statut de la commande',
                'choices' => Brand::cases(),
                'choice_label' => fn(Brand $brand) => $brand->name,
                'choice_value' => fn(Brand $brand) => $brand->value
            ])
            ->add('description')
            ->add('reference')
            ->add('height')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figurine::class,
        ]);
    }
}
