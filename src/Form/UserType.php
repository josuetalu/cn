<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('fullname', TextType::class, [
            'label' => 'Nom complet',
            'required' => true,
            'attr' => ['class' => 'form-control'], // Ajout de la classe form-control
        ])
        ->add('username', TextType::class, [
            'label' => 'Nom d\'utilisateur',
            'required' => true,
            'attr' => ['class' => 'form-control'], // Ajout de la classe form-control
        ]);

    
        if (!empty($options['include_password']) && $options['include_password']) {
            $builder
            ->add('password', TextType::class, [
                'label' => 'Mot de passe (Par defaut 123456789)',
                'required' => true,
                'data' => '123456789',
                'attr' => [
                    'class' => 'form-control', // Ajout de la classe form-control
                    'readonly' => 'readonly', // Rendre le champ non modifiable
                ], // Ajout de la classe form-control
            ]);

        }

        $builder
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'Utilisateur' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN',
            ],
            'label' => 'Roles (choix multiples autorisés)',
            'multiple' => true, // Permet la sélection de plusieurs rôles
            'expanded' => false, // Utilise une combo box (liste déroulante)
            'attr' => ['class' => 'form-control'], // Ajout de la classe form-control
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'include_password' => true
        ]);
    }
}
