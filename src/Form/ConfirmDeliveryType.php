<?php

namespace App\Form;

use App\Entity\Delivery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Validator\Constraints as Assert;

class ConfirmDeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('recipient')
            ->add('recipient', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Destinataire',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le destinataire est requis.']),
                ],
            ])
            ->add('supporting_doc', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Document justificatif',
                'mapped'=>false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le document justificatif est requis.']),
                    new Assert\File([
                        'maxSize' => '12M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/msword',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier valide (PDF ou DOC).',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
        ]);
    }


}
