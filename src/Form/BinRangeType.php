<?php

namespace App\Form;

use App\Entity\Range;
use App\Entity\Bin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BinRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $bin = $options['bin'];
        $startOfNextRange = $options['startOfNextRange'];

        
        $builder
        ->add('start', TextType::class, [
            'attr' => ['class' => 'form-control','readonly' => true], // Applique form-control
            'data' => $startOfNextRange
        ])
        ->add('end', TextType::class, [
            'attr' => ['class' => 'form-control'], // Applique form-control
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Range::class,
            'bin' => null,
            'startOfNextRange' => null
        ]);
    }
}
