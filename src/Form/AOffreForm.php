<?php

namespace App\Form;

use App\Entity\AOffre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AOffreForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Ex: Coordinateur(trice) de Projet — Santé Communautaire'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['rows' => 5, 'placeholder' => 'Description détaillée du poste...'],
            ])
            ->add('resume', TextType::class, [
                'label' => 'Résumé',
                'required' => false,
                'attr' => ['placeholder' => 'Court résumé de l\'offre'],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de contrat',
                'choices' => AOffre::getTypesList(),
                'placeholder' => 'Sélectionnez un type',
                'attr' => ['class' => 'w-full'],
            ])
            ->add('lieu', ChoiceType::class, [
                'label' => 'Lieu',
                'choices' => AOffre::getLieuxList(),
                'placeholder' => 'Sélectionnez un lieu',
            ])
            ->add('dateLimite', DateTimeType::class, [
                'label' => 'Date limite',
                'widget' => 'single_text',
            ])
            ->add('experience', TextType::class, [
                'label' => 'Expérience requise',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: 3 ans minimum'],
            ])
            ->add('formation', TextType::class, [
                'label' => 'Formation requise',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: Bac+3 en Gestion de projets'],
            ])
            ->add('compentences', TextType::class, [
                'label' => 'Compétences',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Ex: Gestion de projets, Leadership, Reporting, Communication',
                    'data-role' => 'tagsinput'
                ],
                'help' => 'Séparez les compétences par des virgules',
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => AOffre::getStatutsList(),
                'placeholder' => 'Sélectionnez un statut',
            ])
            ->add('icon', TextType::class, [
                'label' => 'Icône',
                'required' => false,
                'attr' => ['placeholder' => '🏥, 📢, 🌾, ⚖️'],
                'help' => 'Utilisez un emoji représentant l\'offre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AOffre::class,
        ]);
    }
}
