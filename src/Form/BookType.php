<?php

namespace App\Form;

use Editor;
use CategoryManga;
use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', FileType::class, [
                'label' => 'Image du manga (fichier)',
                'attr' => ['class' => 'form-control'],
                'required' => false,])

            ->add('name')
            ->add('editor',ChoiceType::class, [
                'label' => 'Statut de la commande',
                'choices' => Editor::cases(),
                'choice_label' => fn(Editor $editor) => $editor->name,
                'choice_value' => fn(Editor $editor) => $editor->value
            ])
            ->add('category',ChoiceType::class, [
                'label' => 'Statut de la commande',
                'choices' => CategoryManga::cases(),
                'choice_label' => fn(CategoryManga $category) => $category->name,
                'choice_value' => fn(CategoryManga $category) => $category->value,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('price')
            ->add('synopsis')
            ->add('reference')
            ->add('isbn')
            ->add('ean')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
