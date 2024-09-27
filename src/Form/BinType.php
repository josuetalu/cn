<?php

namespace App\Form;

use App\Entity\Bin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('grantDate', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Date'
            ])
            ->add('serial', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Numero de serie'
            ])
            ->add('range1', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Début range'
            ])
            ->add('range2', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Limit range'
            ])
            ->add('lastEmittedPan', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Dernier PAN emmis'
            ])
            ->add('bank', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Banque'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bin::class,
        ]);
    }
}
