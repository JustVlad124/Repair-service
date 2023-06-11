<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class OrderFormType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $addresses = $this->entityManager->getRepository(Address::class)->findAll();

        $builder
            ->add('orderName', TextType::class)
            ->add('description', TextType::class)
            ->add('cost', NumberType::class)
            ->add('address', EntityType::class, [
                'choice_label' => 'city',
                'class' => Address::class,
            ]);

//        foreach ($addresses as $address) {
//            $args['label']         = $address->getCity();
//            $args['expanded']      = true;
//            $args['multiple']      = true;
//            $args['mapped']        = false;
//            $args['class']         = Address::class;
//            $args['choice_label']  = 'city';
//            $args['choice_value']  = 'id';
//            $args['query_builder'] = call_user_func(function ($value) {
//                return $this->entityManager->createQueryBuilder('s')
//                    ->where('s.requirement = :requirement')
//                    ->setParameter('requirement', $requirement)
//                },$em->getRepository($args['class']), $requirement);
//        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
