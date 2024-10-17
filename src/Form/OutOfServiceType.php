<?php

namespace App\Form;

use App\Entity\OutOfService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OutOfServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reason', TextType::class, [
                'label' => 'Motif de mise hors service',
                'attr' => [
                    'class' => 'form-control', // Ajout de la classe form-control pour Bootstrap
                ],
                'required' => true, // Indique si le champ est requis
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OutOfService::class,
        ]);
    }
}
