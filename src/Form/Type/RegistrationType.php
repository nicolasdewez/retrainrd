<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Validator\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'form.registration.first_name'])
            ->add('lastName', TextType::class, ['label' => 'form.registration.last_name'])
            ->add('email', EmailType::class, ['label' => 'form.registration.email'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => [Group::USER_REGISTRATION],
            'data_class' => User::class,
        ]);
    }
}
