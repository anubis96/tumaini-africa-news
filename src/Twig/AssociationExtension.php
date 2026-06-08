<?php

namespace App\Twig;

use App\Entity\AActivity;
use App\Entity\ACategory;
use App\Entity\AMembre;
use App\Entity\AOffre;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssociationExtension extends AbstractExtension
{
    public function __construct(private EntityManagerInterface $em) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_association_categories_count', [$this, 'getCategoriesCount']),
            new TwigFunction('get_association_activites_count', [$this, 'getActivitesCount']),
            new TwigFunction('get_association_offres_actives_count', [$this, 'getOffresActivesCount']),
            new TwigFunction('get_association_membres_count', [$this, 'getMembresCount']),
        ];
    }

    public function getCategoriesCount(): int
    {
        return $this->em->getRepository(ACategory::class)->count([]);
    }

    public function getActivitesCount(): int
    {
        return $this->em->getRepository(AActivity::class)->count([]);
    }

    public function getOffresActivesCount(): int
    {
        return $this->em->getRepository(AOffre::class)->count(['statut' => 'ouvert']);
    }

    public function getMembresCount(): int
    {
        return $this->em->getRepository(AMembre::class)->count([]);
    }
}
