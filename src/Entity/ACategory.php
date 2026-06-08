<?php

namespace App\Entity;

use App\Repository\ACategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ACategoryRepository::class)]
class ACategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['api'])]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups(['api'])]
    private ?string $icon = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api'])]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    #[Groups(['api'])]
    private ?string $couleur = 'blue';

    /**
     * @var Collection<int, AActivity>
     */
    #[ORM\OneToMany(targetEntity: AActivity::class, mappedBy: 'categories')]
    private Collection $aActivities;

    public function __construct()
    {
        $this->aActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

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

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * @return Collection<int, AActivity>
     */
    public function getAActivities(): Collection
    {
        return $this->aActivities;
    }

    public function addAActivity(AActivity $aActivity): static
    {
        if (!$this->aActivities->contains($aActivity)) {
            $this->aActivities->add($aActivity);
            $aActivity->setCategories($this);
        }

        return $this;
    }

    public function removeAActivity(AActivity $aActivity): static
    {
        if ($this->aActivities->removeElement($aActivity)) {
            // set the owning side to null (unless already changed)
            if ($aActivity->getCategories() === $this) {
                $aActivity->setCategories(null);
            }
        }

        return $this;
    }
}
