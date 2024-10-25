<?php

namespace App\Form;

use App\Entity\Step;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('label', null, [
            'label' => 'Label',
            'attr' => ['class' => 'form-control']
        ])
        ->add('position', null, [
            'label' => 'Position',
            'attr' => ['class' => 'form-control']
        ])
        ->add('establishment', null, [
            'label' => 'Establishment',
            'attr' => ['class' => 'form-control']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Step::class,
        ]);
    }
}
