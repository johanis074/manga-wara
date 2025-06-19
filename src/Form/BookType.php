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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;


class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('picture', FileType::class, [
        'label' => 'Image du manga (fichier)',
        'required' => true,
        'attr' => ['class' => 'form-control'],
        'constraints' => [
            new Assert\NotBlank(['message' => 'Veuillez ajouter une image.']),
            new Assert\File([
                'maxSize' => '2M',
                'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                'mimeTypesMessage' => 'Formats acceptés : jpg, png, webp.',
            ]),
        ],
    ])
    ->add('name', TextType::class, [
        'label' => 'Nom du manga',
        'required' => true,
        'constraints' => [
            new Assert\NotBlank(['message' => 'Le nom est requis.']),
            new Assert\Length([
                'min' => 2,
                'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
            ]),
        ],
    ])
    ->add('editor', TextType::class, [
        'label' => 'Éditeur',
        'required' => true,
        'constraints' => [
            new Assert\NotBlank(['message' => 'L’éditeur est requis.']),
        ],
    ])
    ->add('category', ChoiceType::class, [
        'label' => 'Catégorie',
        'choices' => CategoryManga::cases(),
        'choice_label' => fn(CategoryManga $category) => $category->name,
        'choice_value' => fn(CategoryManga $category) => $category->value,
        'required' => true,
        'constraints' => [
            new Assert\NotBlank(['message' => 'La catégorie est obligatoire.']),
        ],
    ])
    ->add('price', TextType::class, [
        'label' => 'Prix',
        'required' => true,
        'constraints' => [
            new Assert\NotBlank(['message' => 'Le prix est requis.']),
            new Assert\Regex([
                'pattern' => '/^\d+(\.\d{1,2})?$/',
                'message' => 'Le prix doit être un nombre valide.',
            ]),
        ],
    ])
    ->add('synopsis', TextType::class, [
        'label' => 'Synopsis',
        'required' => true,
        'constraints' => [
            new Assert\NotBlank(['message' => 'Le synopsis est requis.']),
            new Assert\Length([
                'min' => 10,
                'minMessage' => 'Le synopsis doit contenir au moins {{ limit }} caractères.',
            ]),
        ],
    ])
    ->add('reference', TextType::class, [
        'label' => 'Référence',
        'required' => true,
        'constraints' => [
            new Assert\NotBlank(['message' => 'La référence est obligatoire.']),
        ],
    ])
    ->add('isbn', TextType::class, [
        'label' => 'ISBN',
        'required' => true,
        'constraints' => [
            new Assert\NotBlank(['message' => 'L’ISBN est requis.']),
            new Assert\Regex([
                'pattern' => '/^\d{10}(\d{3})?$/',
                'message' => 'L’ISBN doit contenir 10 ou 13 chiffres.',
            ]),
        ],
    ])
    ->add('ean', TextType::class, [
        'label' => 'EAN',
        'required' => true,
        'constraints' => [
            new Assert\NotBlank(['message' => 'L’EAN est requis.']),
            new Assert\Regex([
                'pattern' => '/^\d{13}$/',
                'message' => 'L’EAN doit contenir 13 chiffres.',
            ]),
        ],
    ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
