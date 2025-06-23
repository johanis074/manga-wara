<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        

// ...

$builder
    ->add('email', EmailType::class, [
        'constraints' => [
            new Assert\NotBlank(['message' => 'L\'email est obligatoire']),
            new Assert\Email(['message' => 'Format email invalide']),
        ],
    ])
    ->add('pseudo', TextType::class, [
        'constraints' => [
            new Assert\NotBlank(['message' => 'Le pseudo est obligatoire']),
            new Assert\Length([
                'min' => 3,
                'minMessage' => 'Le pseudo doit contenir au moins {{ limit }} caractères',
            ]),
        ],
    ]);
    }
}