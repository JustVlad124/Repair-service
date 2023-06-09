<?php

namespace App\Form;

use App\Entity\SpecialistRating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ratingText', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'style' => 'width: 100%; height: 200px; padding: .6rem 1rem;',
                    'placeholder' => 'Что понравилось? Что могло бы быть лучше?',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpecialistRating::class,
        ]);
    }
}
