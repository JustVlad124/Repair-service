<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Имя',
                'required' => true,
                'trim' => true
            ])
            ->add('surname', TextType::class, [
                'label' => 'Фамилия',
                'required' => true,
                'trim' => true
            ])
            ->add('patronymic', TextType::class, [
                'label' => 'Отчество (если есть)',
                'trim' => true
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Номер телефона',
                'attr' => [
                    'class' => 'phone_input'
                ],
                'trim' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'email',
                'trim' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Пароль',
                'trim' => true,
                'required' => true,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Please enter a password',
//                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Выберите роль пользователя в системе',
                'choices' => [
                    'Клиент' => 'ROLE_USER',
                    'Специалист' => 'ROLE_SPECIALIST',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
