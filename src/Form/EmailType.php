<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType as EmailField;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailField::class, [
            'label' => 'Nouvelle adresse e-mail',
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(['message' => 'L\'adresse e-mail est requise.']),
                new Assert\Email(['message' => 'L\'adresse e-mail est invalide.']),
                new Assert\Length([
                    'max' => 180,
                    'maxMessage' => 'L\'adresse e-mail ne doit pas dépasser {{ limit }} caractères.'
                ]),
            ],
            'attr' => ['class' => 'form-control'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}


