<?php
// src/Form/CommentType.php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre prénom',
                    'class' => 'w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre nom',
                    'class' => 'w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone *',
                'required' => true,
                'attr' => [
                    'placeholder' => '+243 XXX XXX XXX ou 0XXX XXX XXX',
                    'class' => 'w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all'
                ],
                'help' => 'Format: +243 XXX XXX XXX (RDC) ou international'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email (optionnel)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'votre@email.com',
                    'class' => 'w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Votre commentaire *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Partagez votre pensée...',
                    'rows' => 5,
                    'class' => 'w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all resize-none'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_protection' => false,  // ← DÉSACTIVER CSRF ICI !
            'allow_extra_fields' => true,
        ]);
    }
}