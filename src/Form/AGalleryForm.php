<?php

namespace App\Form;

use App\Entity\AGallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

class AGalleryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'w-full px-4 py-2 border rounded-lg']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'w-full px-4 py-2 border rounded-lg', 'rows' => 4]
            ])
            ->add('imageFiles', FileType::class, [
                'label' => 'Images',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new Image(['maxSize' => '5M'])
                        ]
                    ])
                ],
                'attr' => [
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                    'accept' => 'image/jpeg,image/png,image/webp',
                    'multiple' => 'multiple'
                ]
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AGallery::class,
        ]);
    }
}
