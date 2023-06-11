<?php

namespace App\Form;

use App\Entity\Specialist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialistFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('aboutMyself', TextareaType::class, [
                'label' => 'Информация о себе',
                'required' => false,
                'attr' => [
                    'style' => 'width: 100%; height: 150px; padding: .6rem'
                ],
            ])
            ->add('education', TextType::class, [
                'label' => 'Информация об образовании',
                'required' => false,
            ])
            ->add('workExperience', TextType::class, [
                'label' => 'Стаж работы',
                'required' => false,
            ])
            ->add('avatarImage', FileType::class, [
                'label' => 'Изображение профиля',
                'required' => false,
            ])
//            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Specialist::class,
        ]);
    }
}
