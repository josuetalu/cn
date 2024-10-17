<?php

namespace App\Form;

use App\Entity\Bin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('grantDate', DateType::class, [
                'widget' => 'single_text', // Utiliser un champ de texte unique
                'html5' => false, // Désactive le champ de date HTML5
                'attr' => [
                    'class' => 'form-control datepicker', // Classe pour le style et le script
                    'placeholder' => 'Sélectionnez une date', // Optionnel : texte d'espace réservé
                ],
                'label' => 'Date',
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
