<?php

namespace App\Entity;

use App\Repository\AOffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AOffreRepository::class)]
class AOffre
{
        // ========== CONSTANTES POUR LES TYPES DE CONTRAT ==========
    public const TYPE_CDI = 'cdi';
    public const TYPE_CDD = 'cdd';
    public const TYPE_STAGE = 'stage';
    public const TYPE_CONSULTANT = 'consultant';
    public const TYPE_TEMPS_PLEIN = 'temps_plein';
    public const TYPE_TEMPS_PARTIEL = 'temps_partiel';
    public const TYPE_FREELANCE = 'freelance';

    // ========== CONSTANTES POUR LES STATUTS ==========
    public const STATUT_OUVERT = 'ouvert';
    public const STATUT_FERME = 'ferme';
    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_POURVU = 'pourvu';

    // ========== CONSTANTES POUR LES LIEUX (optionnel) ==========
    public const LIEU_UVIRA = 'uvira';
    public const LIEU_BUKAVU = 'bukavu';
    public const LIEU_BARAKA = 'baraka';
    public const LIEU_GOMA = 'goma';    
    public const LIEU_LUBUMBASHI = 'lubumbashi';
    public const LIEU_KIN = 'kinshasa';
    public const LIEU_SUD_KIVU = 'sud_kivu';
    public const LIEU_TELE_TRAVAIL = 'tele_travail';
    public const LIEU_TERRAIN = 'terrain';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('api')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['api'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $resume = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['api'])]
    private ?string $type = self::TYPE_CDD;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $lieu = self::LIEU_UVIRA;

    #[ORM\Column()]
    #[Groups(['api'])]
    private ?\DateTimeImmutable $dateLimite = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $experience = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $formation = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['api'])]
    private ?array $compentences = null;

    #[ORM\Column(length: 50)]
    #[Groups(['api'])]
    private ?string $statut = self::STATUT_OUVERT;
    
    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['api'])]
    private ?string $icon = null;

    #[ORM\Column(length: 255)]
    #[Groups('api')]
    private ?string $slug = null;

    // ========== MÉTHODES STATIQUES POUR LES CHOIX ==========
    
    /**
     * Retourne tous les types de contrat disponibles
     */
    public static function getTypesList(): array
    {
        return [
            self::TYPE_CDI => 'CDI',
            self::TYPE_CDD => 'CDD',
            self::TYPE_STAGE => 'Stage',
            self::TYPE_CONSULTANT => 'Consultant',
            self::TYPE_TEMPS_PLEIN => 'Temps plein',
            self::TYPE_TEMPS_PARTIEL => 'Temps partiel',
            self::TYPE_FREELANCE => 'Freelance',
        ];
    }

    /**
     * Retourne tous les statuts disponibles
     */
    public static function getStatutsList(): array
    {
        return [
            self::STATUT_OUVERT => 'Ouvert',
            self::STATUT_FERME => 'Fermé',
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_POURVU => 'Pourvu',
        ];
    }

    /**
     * Retourne tous les lieux disponibles
     */
    public static function getLieuxList(): array
    {
        return [
            self::LIEU_UVIRA => 'Uvira',
            self::LIEU_BUKAVU => 'Bukavu',
            self::LIEU_GOMA => 'Goma',
            self::LIEU_BARAKA => 'Baraka',
            self::LIEU_LUBUMBASHI => 'Lubumbashi',
            self::LIEU_KIN => 'Kinshasa',
            self::LIEU_SUD_KIVU => 'Sud-Kivu',
            self::LIEU_TELE_TRAVAIL => 'Télétravail',
            self::LIEU_TERRAIN => 'Terrain',
        ];
    }

    /**
     * Vérifie si le type est valide
     */
    public static function isValidType(string $type): bool
    {
        return in_array($type, array_keys(self::getTypesList()));
    }

    /**
     * Vérifie si le statut est valide
     */
    public static function isValidStatut(string $statut): bool
    {
        return in_array($statut, array_keys(self::getStatutsList()));
    }

    /**
     * Retourne le libellé du type
     */
    public function getTypeLabel(): string
    {
        return self::getTypesList()[$this->type] ?? $this->type;
    }

    /**
     * Retourne le libellé du statut
     */
    public function getStatutLabel(): string
    {
        return self::getStatutsList()[$this->statut] ?? $this->statut;
    }

    /**
     * Retourne le libellé du lieu
     */
    public function getLieuLabel(): string
    {
        return self::getLieuxList()[$this->lieu] ?? $this->lieu;
    }

    /**
     * Retourne la classe CSS pour le statut
     */
    public function getStatutBadgeClass(): string
    {
        return match($this->statut) {
            self::STATUT_OUVERT => 'bg-green-100 text-green-700',
            self::STATUT_FERME => 'bg-red-100 text-red-700',
            self::STATUT_EN_ATTENTE => 'bg-yellow-100 text-yellow-700',
            self::STATUT_POURVU => 'bg-gray-100 text-gray-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateLimite(): ?\DateTimeImmutable
    {
        return $this->dateLimite;
    }

    public function setDateLimite(\DateTimeImmutable $dateLimite): static
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getFormation(): ?string
    {
        return $this->formation;
    }

    public function setFormation(?string $formation): static
    {
        $this->formation = $formation;

        return $this;
    }

    public function getCompentences(): ?array
    {
        return $this->compentences;
    }

    public function setCompentences(?array $compentences): static
    {
        $this->compentences = $compentences;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
