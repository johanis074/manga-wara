<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudonyme',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le pseudonyme est requis.']),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Le pseudonyme doit contenir au moins {{ limit }} caractères.',
                        'max' => 50,
                        'maxMessage' => 'Le pseudonyme ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('pictureUser', ChoiceType::class, [
                'label' => 'Avatar',
                'choices' => [
                    'anaconda' => 'anaconda.webp',
                    'armadillo' => 'armadillo.webp',
                    'bird' => 'bird.webp',
                    'blackbird' => 'blackbird.webp',
                    'cat' => 'cat.webp',
                    'cow' => 'cow.webp',
                    'deer' => 'deer.webp',
                    'ganesha' => 'ganesha.webp',
                    'jacutinga' => 'jacutinga.webp',
                    'jaguar' => 'jaguar.webp',
                    'macaw' => 'macaw.webp',
                    'parrot' => 'parrot.webp',
                    'pelican' => 'pelican.webp',
                    'turtle' => 'turtle.webp',
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner un avatar.']),
                ],
                'choice_attr' => function ($value, $key, $index) {
                    return ['data-img' => '/uploads/pictureUser/' . $value];
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

