<?php

namespace App\Form;

use App\Entity\Bank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('bankCode', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Nom de la banque'
        ])
        ->add('address', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Adresse'
        ])
        ->add('contact', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Contact'
        ])
        /*->add('date', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Date'
        ])*/
        ->add('defaultEmail', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Email par défaut'
        ])
        ->add('website', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Site Web'
        ])
        ->add('defaultNumber', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Numéro par défaut'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bank::class,
        ]);
    }
}
