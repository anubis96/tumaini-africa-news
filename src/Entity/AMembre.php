<?php

namespace App\Entity;

use App\Repository\AMembreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AMembreRepository::class)]
class AMembre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('api')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['api'])]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['api'])]
    private ?string $poste = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['api'])]
    private ?string $bio = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['api'])]
    private ?string $specialite = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['api'])]
    private ?string $anciennete = null;

    #[ORM\Column(length: 5, nullable: true)]
    #[Groups(['api'])]
    private ?string $initiales = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['api'])]
    private ?string $couleur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $linkedin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $twitter = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['api'])]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['api'])]
    private ?string $telephone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(?string $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getAnciennete(): ?string
    {
        return $this->anciennete;
    }

    public function setAnciennete(?string $anciennete): static
    {
        $this->anciennete = $anciennete;

        return $this;
    }

    public function getInitiales(): ?string
    {
        return $this->initiales;
    }

    public function setInitiales(?string $initiales): static
    {
        $this->initiales = $initiales;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): static
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }
}
