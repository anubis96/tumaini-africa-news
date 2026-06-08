<?php

namespace App\Entity;

use App\Repository\AActivityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AActivityRepository::class)]
class AActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['api'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $resume = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['api'])]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    private ?string $lieu = null;

    #[ORM\Column(length: 50)]
    #[Groups(['api'])]
    private ?string $status = 'planifie';

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['api'])]
    private ?string $imageIcon = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['api'])]
    private ?int $participants = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $beneficiaires = null;

    #[ORM\ManyToOne(inversedBy: 'aActivities')]
    private ?ACategory $categories = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getImageIcon(): ?string
    {
        return $this->imageIcon;
    }

    public function setImageIcon(?string $imageIcon): static
    {
        $this->imageIcon = $imageIcon;

        return $this;
    }

    public function getParticipants(): ?int
    {
        return $this->participants;
    }

    public function setParticipants(?int $participants): static
    {
        $this->participants = $participants;

        return $this;
    }

    public function getBeneficiaires(): ?string
    {
        return $this->beneficiaires;
    }

    public function setBeneficiaires(?string $beneficiaires): static
    {
        $this->beneficiaires = $beneficiaires;

        return $this;
    }

    public function getCategories(): ?ACategory
    {
        return $this->categories;
    }

    public function setCategories(?ACategory $categories): static
    {
        $this->categories = $categories;

        return $this;
    }
}
