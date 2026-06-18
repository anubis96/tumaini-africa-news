<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[

            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'required'=>false,
                'attr' =>[
                    'placeholder' => "Contenu de l'article",
                    'rows'=> 15
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez les contenus de l\'article',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Le contenu doit faire au moins {{ limit }} caractères',
                        'max' => 20000,
                    ]),
                ]
            ])
            ->add('imageFile', VichFileType::class, [
                "required" => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPEG, PNG, GIF ou SVG)',
                    ])
                ]
            ])
            ->add('emissionFile', VichFileType::class,  [
                "required" => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'audio/mpeg',
                            'audio/ogg',
                            'audio/wav',
                            'audio/x-wav',
                            'audio/webm',
                            'audio/aac',
                            'audio/mp3'
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier audio valide (MP3, OGG, WAV, AAC ou WEBM)',
                    ])
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500']
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'expanded' => false,
                'attr' => [
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500',
                    'multiple' => 'multiple',
                    'data-placeholder' => 'Sélectionnez des tags',
                ],
                'help' => 'Sélectionnez ou recherchez des tags pour cet article. Maintenez Ctrl (Cmd sur Mac) pour en sélectionner plusieurs.'
            ])
            ->add('isPublished', CheckboxType::class,[
                'required' => false
            ])
            ->add('isUrgent', CheckboxType::class,[
                'required' => false
            ])
            ->add('geoScope', ChoiceType::class, [
                'label' => 'Portée géographique',
                'required' => false,
                'placeholder' => 'Sélectionnez la portée',
                'choices' => [
                    '🌍 International' => 'international',
                    '🌎 Continental' => 'continental',
                    '🇨🇩 National' => 'national',
                    '🏙️ Local' => 'local',
                ],
                'attr' => ['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500'],
                'help' => 'Détermine la zone géographique couverte par l\'article'
            ])
            
            ->add('geoContinent', ChoiceType::class, [
                'label' => 'Continent',
                'required' => false,
                'placeholder' => 'Sélectionnez un continent',
                'choices' => [
                    '🌍 Afrique' => 'Afrique',
                    '🌍 Europe' => 'Europe',
                    '🌎 Amérique du Nord' => 'Amérique du Nord',
                    '🌎 Amérique du Sud' => 'Amérique du Sud',
                    '🌏 Asie' => 'Asie',
                    '🌏 Océanie' => 'Océanie',
                ],
                'attr' => ['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500']
            ])
            
            ->add('geoCountry', ChoiceType::class, [
                'label' => 'Pays',
                'required' => false,
                'placeholder' => 'Sélectionnez un pays',
                'choices' => $this->getCountryChoices(),
                'attr' => ['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500']
            ])
            
            ->add('geoRegion', TextType::class, [
                'label' => 'Région / Province / État',
                'required' => false,
                'attr' => [
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500',
                    'placeholder' => 'Région / Province / État, Ex: Sud-Kivu, Île-de-France, Californie'
                ]
            ])
            
            ->add('geoCity', TextType::class, [
                'label' => 'Ville / Localité',
                'required' => false,
                'attr' => [
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500',
                    'placeholder' => 'Ville / Localite, Ex: Uvira, Paris, New York, Kinshasa'
                ]
            ])
        ;
    }

    private function getCountryChoices(): array
    {
        return [
            'République Démocratique du Congo' => 'RDC',
            'France' => 'France',
            'États-Unis' => 'USA',
            'Royaume-Uni' => 'UK',
            'Canada' => 'Canada',
            'Belgique' => 'Belgique',
            'Suisse' => 'Suisse',
            'Allemagne' => 'Allemagne',
            'Italie' => 'Italie',
            'Espagne' => 'Espagne',
            'Portugal' => 'Portugal',
            'Brésil' => 'Brésil',
            'Afrique du Sud' => 'Afrique du Sud',
            'Nigeria' => 'Nigeria',
            'Kenya' => 'Kenya',
            'Tanzanie' => 'Tanzanie',
            'Rwanda' => 'Rwanda',
            'Burundi' => 'Burundi',
            'Ouganda' => 'Ouganda',
            'Chine' => 'Chine',
            'Japon' => 'Japon',
            'Inde' => 'Inde',
            'Australie' => 'Australie',
            'Russie' => 'Russie',
            'Égypte' => 'Égypte',
            'Maroc' => 'Maroc',
            'Tunisie' => 'Tunisie',
            'Sénégal' => 'Sénégal',
            'Côte d\'Ivoire' => 'Côte d\'Ivoire',
            'Ghana' => 'Ghana',
            'Cameroun' => 'Cameroun',
            'Zambie' => 'Zambie',
            'Zimbabwe' => 'Zimbabwe',
            'Mozambique' => 'Mozambique',
            'Angola' => 'Angola',
        ];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
