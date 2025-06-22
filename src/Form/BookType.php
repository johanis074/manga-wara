<?php

namespace App\Form;

use App\Entity\Book;
use App\Enum\CategoryManga;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis.']),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('editor', TextType::class, [
                'label' => 'Éditeur',
                'constraints' => [
                    new NotBlank(['message' => 'L’éditeur est requis.']),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => CategoryManga::cases(),
                'choice_label' => fn(CategoryManga $cat) => $cat->name,
                'choice_value' => fn(?CategoryManga $cat) => $cat?->value,
                'placeholder' => 'Choisir une catégorie',
                'constraints' => [
                    new NotBlank(['message' => 'La catégorie est requise.']),
                ],
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'EUR',
                'label' => 'Prix',
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est requis.']),
                ],
            ])
            ->add('ean', TextType::class, [
                'label' => 'Code EAN',
                'attr' => [
                    'maxlength' => 13,
                    'placeholder' => 'ex : 9781234567890',
                    'inputmode' => 'numeric',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le code EAN est requis.']),
                    new Length([
                        'min' => 13,
                        'max' => 13,
                        'exactMessage' => 'Le code EAN doit contenir exactement {{ limit }} chiffres.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{13}$/',
                        'message' => 'Le code EAN doit contenir uniquement 13 chiffres.',
                    ]),
                ],
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
                'attr' => [
                    'maxlength' => 13,
                    'placeholder' => 'ex : 9781234567897',
                    'inputmode' => 'numeric',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le champ ISBN est requis.']),
                    new Length([
                        'min' => 13,
                        'max' => 13,
                        'exactMessage' => 'L\'ISBN doit contenir exactement {{ limit }} chiffres.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{13}$/',
                        'message' => 'L\'ISBN doit contenir uniquement 13 chiffres.',
                    ]),
                ],
            ])
            ->add('reference', TextType::class, [
                'label' => 'Référence',
                'constraints' => [
                    new NotBlank(['message' => 'La référence est requise.']),
                    new Length(['max' => 100]),
                ],
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image de couverture',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('synopsis', TextareaType::class, [
                'label' => 'Synopsis',
                'attr' => ['rows' => 10],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
