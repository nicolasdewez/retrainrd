<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', null, ['label' => 'form.login.email'])
            ->add('_password', PasswordType::class, ['label' => 'form.login.password'])
        ;
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
