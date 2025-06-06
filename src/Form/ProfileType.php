<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudonyme'
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
                'expanded' => true, // radio boutons
                'multiple' => false,
                'choice_attr' => function($value, $key, $index) {
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

