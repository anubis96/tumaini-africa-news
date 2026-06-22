<?php

namespace App\Form;

use App\Entity\AActivity;
use App\Entity\ACategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class AActivityForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('resume')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('lieu')
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => AActivity::getStatutsList(),
                'placeholder' => 'Sélectionnez un statut',
            ])
            ->add('imageIcon')
            ->add('participants')
            ->add('beneficiaires')
            ->add('imageFile', FileType::class, [
                "required" => false,
                'constraints' => [
                    new Image(['maxSize' => '5M'])
                ],
                'attr' => [
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                    'accept' => 'image/jpeg,image/png,image/webp',
                    'multiple' => 'multiple'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => ACategory::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AActivity::class,
        ]);
    }
}
