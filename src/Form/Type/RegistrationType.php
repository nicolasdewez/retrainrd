<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Security\Role;
use App\Translation\Locale;
use App\Validator\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'form.registration.first_name'])
            ->add('lastName', TextType::class, ['label' => 'form.registration.last_name'])
            ->add('email', EmailType::class, ['label' => 'form.registration.email'])
            ->add('username', TextType::class, ['label' => 'form.registration.username'])

        ;

        if (!$options['with_roles']) {
            return;
        }

        $builder->add('roles', ChoiceType::class, [
            'label' => 'form.admin.users.edit.roles',
            'choices' => [
                Role::TITLE_USER => Role::USER,
                Role::TITLE_ADMIN => Role::ADMIN,
            ],
            'required' => true,
            'multiple' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => [Group::USER_REGISTRATION],
            'data_class' => User::class,
            'with_roles' => false,
        ]);
    }
}
