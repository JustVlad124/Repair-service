<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('countrySubject', TextType::class, [
                'label' => 'Субъект РФ',
                'trim' => true,
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => 'Населенный пункт',
                'trim' => true,
                'required' => true,
            ])
            ->add('street', TextType::class, [
                'label' => 'Улица',
                'trim' => true,
                'required' => true,
            ])
            ->add('number', TextType::class, [
                'label' => 'Номер дома',
                'trim' => true,
                'required' => true,
            ])
//            ->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
