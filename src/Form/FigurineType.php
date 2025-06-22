<?php

namespace App\Form;

use App\Enum\Brand;
use App\Entity\Figurine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class FigurineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom du manga'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image de couverture (jpeg, png, webp)',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'data_class' => null,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'image est obligatoire']),
                    new Assert\File([
                        'maxSize' => '2M',
                        'maxSizeMessage' => 'L\'image ne peut pas dépasser 2Mo',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image au format jpeg, png ou webp',
                    ]),
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix (€)',
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Prix en euros'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prix est obligatoire']),
                    new Assert\Type([
                        'type' => 'numeric',
                        'message' => 'Le prix doit être un nombre',
                    ]),
                    new Assert\PositiveOrZero(['message' => 'Le prix doit être positif ou nul']),
                ],
            ])
            ->add('brand', TextType::class, [
                'label' => 'Marque',
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Saisir la marque'],
                'constraints' => [
                    new Assert\Length([
                        'max' => 100,
                        'maxMessage' => 'La marque ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'rows' => 10,
                    'style' => 'width: 100%; resize: none;',
                    'class' => 'form-control',
                    'placeholder' => 'Description du manga',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La description est obligatoire']),
                ],
            ])
            ->add('reference', TextType::class, [
                'label' => 'Référence',
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Référence interne'],
                'constraints' => [
                    new Assert\Length([
                        'max' => 100,
                        'maxMessage' => 'La référence ne peut pas dépasser {{ limit }} caractères',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'La référence doit contenir uniquement des lettres et chiffres (sans espaces ni caractères spéciaux)',
                    ]),
                ],
            ])
            ->add('height', NumberType::class, [
                'label' => 'Hauteur (cm)',
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Hauteur en cm'],
                'constraints' => [
                    new Assert\Type([
                        'type' => 'numeric',
                        'message' => 'La hauteur doit être un nombre',
                    ]),
                    new Assert\PositiveOrZero(['message' => 'La hauteur doit être positive ou nulle']),
                ],
            ])
        ;
    }





    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figurine::class,
        ]);
    }
}
