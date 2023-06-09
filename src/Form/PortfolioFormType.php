<?php

namespace App\Form;

use App\Entity\Portfolio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('projectName', TextType::class, [
                'label' => 'Подпись',
                'attr' => [
                    'style' => 'display: block; width: 30%;'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание проекта',
                'attr' => [
                    'style' => 'display: block; width: 80%; height: 150px;'
                ]
            ])
            ->add('imagePath', FileType::class, [
                'label' => 'Файл изображения',
                'attr' => [
                    'style' => 'display: block'
                ]
            ])
//            ->add('specialist')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}
