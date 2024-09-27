<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Bin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('cardTotal', IntegerType::class, [
            'label' => 'Nombre de carte',
            'required' => false,
            'attr' => ['class' => 'form-control'], // Add form-control class
        ])
        ->add('cardType', TextType::class, [
            'label' => 'Type de carte',
            'required' => false,
            'attr' => ['class' => 'form-control'], // Add form-control class
        ])
        ->add('bin', EntityType::class, [
            'class' => Bin::class,
            'choice_label' => function (Bin $bin) {
                return $bin->getSerial() . ' - ' . $bin->getBank()->getBankCode(); // Concatène deux champs ici
            },
            'label' => 'BIN - BANQUE',
            'placeholder' => 'Choix du BIN',
            'required' => true,
            'attr' => ['class' => 'form-control'], // Add form-control class
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
