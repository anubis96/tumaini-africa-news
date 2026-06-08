<?php

namespace App\Form;

use App\Entity\AActivity;
use App\Entity\ACategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('status')
            ->add('imageIcon')
            ->add('participants')
            ->add('beneficiaires')
            ->add('categories', EntityType::class, [
                'class' => ACategory::class,
                'choice_label' => 'id',
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
