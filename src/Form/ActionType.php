<?php

namespace App\Form;

use App\Entity\Action;
use App\Entity\User;
use App\Entity\Step;
use App\Entity\Establishment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder    
            ->add('comment', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 5],
                'label' => 'Commentaire',
            ]);
            //->add('document')
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Action::class,
            'user' => null, 
            'step' => null,
            'establishment' => null,
        ]);
    }
}