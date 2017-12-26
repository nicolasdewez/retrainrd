<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Translation\Locale;
use App\Validator\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'form.my_account.first_name'])
            ->add('lastName', TextType::class, ['label' => 'form.my_account.last_name'])
            ->add('emailNotification', CheckboxType::class, [
                'label' => 'form.my_account.email_notification',
                'required' => false,
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => 'form.my_account.current_password',
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'form.my_account.new_password_1'],
                'second_options' => ['label' => 'form.my_account.new_password_2'],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => [Group::USER_MY_ACCOUNT],
            'data_class' => User::class,
        ]);
    }
}
