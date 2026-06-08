<?php

namespace App\Form;

use App\Entity\AMembre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AMembreForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom complet',
                'attr' => ['placeholder' => 'Ex: MATU Christian'],
            ])
            ->add('poste', TextType::class, [
                'label' => 'Poste',
                'attr' => ['placeholder' => 'Ex: Directeur Exécutif'],
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Biographie',
                'attr' => ['rows' => 5, 'placeholder' => 'Description du membre...'],
            ])
            ->add('specialite', TextType::class, [
                'label' => 'Spécialité',
                'attr' => ['placeholder' => 'Ex: Gestion de projets, Santé publique'],
            ])
            ->add('anciennete', TextType::class, [
                'label' => 'Année d\'arrivée',
                'attr' => ['placeholder' => 'Ex: 2015'],
            ])
            ->add('initiales', TextType::class, [
                'label' => 'Initiales',
                'attr' => ['placeholder' => 'Ex: CM', 'maxlength' => 5],
            ])
            ->add('couleur', TextType::class, [
                'label' => 'Couleur',
                'attr' => ['placeholder' => '#F59E0B', 'type' => 'color'],
            ])
            ->add('linkedin', UrlType::class, [
                'label' => 'LinkedIn',
                'required' => false,
                'attr' => ['placeholder' => 'https://linkedin.com/in/...'],
            ])
            ->add('twitter', UrlType::class, [
                'label' => 'Twitter/X',
                'required' => false,
                'attr' => ['placeholder' => 'https://twitter.com/...'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'email@example.com'],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => ['placeholder' => '+243 XXX XXX XXX'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AMembre::class,
        ]);
    }
}
