<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as PasswordInput;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('current_password', PasswordInput::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
            ])
            ->add('new_password', PasswordInput::class, [
                'label' => 'Nouveau mot de passe',
                'mapped' => false,
            ])
            ->add('repeat_password', PasswordInput::class, [
                'label' => 'Confirmer le nouveau mot de passe',
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
