<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\AdvertiseRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/{_locale}', name: 'app_home', requirements: ['_locale' => 'fr|en|sw'])]
    public function index(ArticleRepository $articleRepo, 
        CategoryRepository $categoryRepository,
        AdvertiseRepository $advertiseRepository,
        TagRepository $tagRepository,
        Request $request
        ): Response
    {
        $locale = $request->getLocale();
        $GLOBALS['locale'] = $locale;

        $excludedSlugs = ['sport-international', 'divertissement', 'etranger'];

        $last_urgent_articles = $articleRepo->findThreeLatestUrgent($excludedSlugs);
        $categoriesResult = $categoryRepository->findTopCategoriesWithMostArticles(7);
        $categories = array_map(function($item){
            return $item[0];
        }, $categoriesResult);
        $selectedCategorySlug = $request->query->get('category');
        $selectedCategory = $selectedCategorySlug 
            ? $categoryRepository->findOneBy(['slug' => $selectedCategorySlug])
            : ($categories[0] ?? null);
        
        $categoryArticles = [];
        if($selectedCategory){
            $categoryArticles = $articleRepo->findBy(
                ['category' => $selectedCategory, 'isPublished' => true],
                ['publishedAt' => 'DESC'],
                4
            );
        }

        $mostViewed = $articleRepo->findMostViewedArticles(5, $excludedSlugs);
        $firstViewed = $mostViewed[0];
        $trending = $articleRepo->findTrendingArticles(10, $excludedSlugs);
        $last_advertise = $advertiseRepository->findLastThreeWhereIsMiddleFalseOrNull();
        $last_advertise_middle = $advertiseRepository->findLastMiddleAdvertise();
        $nonUrgentLastThree = $articleRepo->findThreeLatestNonUrgentArticles($excludedSlugs);

        // --- 🔥 TAGS POPULAIRES ---
        // Tags populaires des 3 derniers jours
        $popularTags3Days = $tagRepository->findPopularTagsLast3Days(12);
        
        // Tags populaires de la semaine
        $popularTagsWeek = $tagRepository->findPopularTagsLastWeek(12);
        
        // Tags populaires du mois
        $popularTagsMonth = $tagRepository->findPopularTagsLastMonth(12);
        
        // Tags les plus utilisés (tous temps)
        $mostUsedTags = $tagRepository->findMostUsedTags(12);

        // Statistiques des tags
        $tagsStats = $tagRepository->getTagsStats();

        // Par défaut, afficher les tags de la semaine
        $popularTags = $popularTagsWeek;

        // Récupérer la période choisie (optionnelle)
        $period = $request->query->get('period', 'week');
        $popularTags = match($period) {
            '3days' => $popularTags3Days,
            'week' => $popularTagsWeek,
            'month' => $popularTagsMonth,
            'all' => $mostUsedTags,
            default => $popularTagsWeek,
        };

        $categoryInternational = $categoryRepository->findOneBy(['slug' => 'etranger']);
        $categoryFaitDivers = $categoryRepository->findOneBy(['slug' => 'fait-divers']);
        $categoryDivertissement = $categoryRepository->findOneBy(['slug' => 'divertissement']);
        $categorySport = $categoryRepository->findOneBy(['slug' => 'sport-international']);

        // Récupérer les articles de chaque catégorie (5 derniers)
        $internationalArticles = [];
        $faitDiversArticles = [];
        $divertissementArticles = [];
        $sportArticles = [];

        if ($categoryInternational) {
            $internationalArticles = $articleRepo->findBy(
                ['category' => $categoryInternational, 'isPublished' => true],
                ['publishedAt' => 'DESC'],
                5
            );
        }

        if ($categoryFaitDivers) {
            $faitDiversArticles = $articleRepo->findBy(
                ['category' => $categoryFaitDivers, 'isPublished' => true],
                ['publishedAt' => 'DESC'],
                5
            );
        }

        if ($categoryDivertissement) {
            $divertissementArticles = $articleRepo->findBy(
                ['category' => $categoryDivertissement, 'isPublished' => true],
                ['publishedAt' => 'DESC'],
                5
            );
        }

        if ($categorySport) {
            $sportArticles = $articleRepo->findBy(
                ['category' => $categorySport, 'isPublished' => true],
                ['publishedAt' => 'DESC'],
                5
            );
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Acceuil !',
            'lastFirst' => $nonUrgentLastThree[0] ?? null,
            'lastSecond' => $nonUrgentLastThree[1] ?? null,
            'lastThird' => $nonUrgentLastThree[2] ?? null,
            'urgents' => $last_urgent_articles,
            'categories' => $categories,
            'selected_category' => $selectedCategory,
            'category_articles' => $categoryArticles,
            'first_viewed' => $firstViewed,
            'most_viewed' => $mostViewed,
            'trending' => $trending,
            'last_advertise' => $last_advertise,
            'advertises_count'=> count($last_advertise),
            'middle_advertise' => $last_advertise_middle,
            'current_locale' => $locale,
        
            'popular_tags' => $popularTags,
            'popular_tags_3days' => $popularTags3Days,
            'popular_tags_week' => $popularTagsWeek,
            'popular_tags_month' => $popularTagsMonth,
            'most_used_tags' => $mostUsedTags,
            'tags_stats' => $tagsStats,
            'selected_period' => $period,

            'category_international' => [
                'name' => $categoryInternational ? $categoryInternational->getName() : 'International',
                'slug' => $categoryInternational ? $categoryInternational->getSlug() : 'international',
                'articles' => $internationalArticles,
            ],
            'category_fait_divers' => [
                'name' => $categoryFaitDivers ? $categoryFaitDivers->getName() : 'Faits divers',
                'slug' => $categoryFaitDivers ? $categoryFaitDivers->getSlug() : 'faits-divers',
                'articles' => $faitDiversArticles,
            ],
            'category_divertissement' => [
                'name' => $categoryDivertissement ? $categoryDivertissement->getName() : 'Divertissement',
                'slug' => $categoryDivertissement ? $categoryDivertissement->getSlug() : 'divertissement',
                'articles' => $divertissementArticles,
            ],
            'category_sport' => [
                'name' => $categorySport ? $categorySport->getName() : 'Sport',
                'slug' => $categorySport ? $categorySport->getSlug() : 'sport',
                'articles' => $sportArticles,
            ]
        ]);
    }

}
