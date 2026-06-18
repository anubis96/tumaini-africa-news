<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Vich\Uploadable()]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[Vich\UploadableField(mapping: 'articles', fileNameProperty: 'imageUrl')]
    #[Assert\Image()]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $slug;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $excerpt = null;

    #[ORM\ManyToOne(inversedBy: 'category')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Category $category = null;

    /**
     * @var Collection<int, Comments>
     */
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'article')]
    private Collection $comments;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $isPublished = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isUrgent = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?int $viewCount = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emissionUrl = null;

    #[Vich\UploadableField(mapping: 'emission', fileNameProperty: 'emissionUrl')]
    #[Assert\File()]
    private ?File $emissionFile = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'Article')]
    private Collection $commentss;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'articles')]
    #[ORM\JoinTable(name: 'article_tag')]
    private Collection $tags;
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $geoScope = null; // 'local', 'national', 'international', 'continental'

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $geoCountry = null; // RDC, France, USA, etc.

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $geoCountryCode = null; // CD, FR, US, etc.

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $geoRegion = null; // Sud-Kivu, Île-de-France, etc.

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $geoCity = null; // Uvira, Paris, New York

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $geoContinent = null; // Afrique, Europe, Asie, etc.
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->commentss = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): static
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): static
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

        public function getIsPublished(): bool {
        return $this->isPublished;
    }

    // Setter : modifier la valeur
    public function setIsPublished(bool $isPublished): void {
        $this->isPublished = $isPublished;
    }

    public function isUrgent(): ?bool
    {
        return $this->isUrgent;
    }

    public function setIsUrgent(?bool $isUrgent): static
    {
        $this->isUrgent = $isUrgent;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function incrementCount(): self
    {
        $this->viewCount++;
        return $this;
    }

    public function setViewCount(int $viewCount): static
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function getEmissionUrl(): ?string
    {
        return $this->emissionUrl;
    }

    public function setEmissionUrl(?string $emissionUrl): static
    {
        $this->emissionUrl = $emissionUrl;

        return $this;
    }

    public function getEmissionFile(): ?File
    {
        return $this->emissionFile;
    }

    public function setEmissionFile(?File $emissionFile): static
    {
        $this->emissionFile = $emissionFile;

        if (null !== $emissionFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentss(): Collection
    {
        return $this->commentss;
    }

    public function addCommentss(Comment $commentss): static
    {
        if (!$this->commentss->contains($commentss)) {
            $this->commentss->add($commentss);
            $commentss->setArticle($this);
        }

        return $this;
    }

    public function removeCommentss(Comment $commentss): static
    {
        if ($this->commentss->removeElement($commentss)) {
            // set the owning side to null (unless already changed)
            if ($commentss->getArticle() === $this) {
                $commentss->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);
        return $this;
    }

        // Getters et Setters
    public function getGeoScope(): ?string { return $this->geoScope; }
    public function setGeoScope(?string $geoScope): static { $this->geoScope = $geoScope; return $this; }
    
    public function getGeoCountry(): ?string { return $this->geoCountry; }
    public function setGeoCountry(?string $geoCountry): static { $this->geoCountry = $geoCountry; return $this; }
    
    public function getGeoCountryCode(): ?string { return $this->geoCountryCode; }
    public function setGeoCountryCode(?string $geoCountryCode): static { $this->geoCountryCode = $geoCountryCode; return $this; }
    
    public function getGeoRegion(): ?string { return $this->geoRegion; }
    public function setGeoRegion(?string $geoRegion): static { $this->geoRegion = $geoRegion; return $this; }
    
    public function getGeoCity(): ?string { return $this->geoCity; }
    public function setGeoCity(?string $geoCity): static { $this->geoCity = $geoCity; return $this; }
    
    public function getGeoContinent(): ?string { return $this->geoContinent; }
    public function setGeoContinent(?string $geoContinent): static { $this->geoContinent = $geoContinent; return $this; }

    /**
     * Récupère la région complète pour les métadonnées
     */
    public function getGeoFullName(): string
    {
        $parts = [];
        if ($this->geoCity) {
            $parts[] = $this->geoCity;
        }
        if ($this->geoRegion) {
            $parts[] = $this->geoRegion;
        }
        if ($this->geoCountry) {
            $parts[] = $this->geoCountry;
        }
        return implode(', ', $parts) ?: 'Monde';
    }

    /**
     * Récupère le code pays/région pour les métadonnées
     */
    public function getGeoRegionCode(): string
    {
        return $this->geoCountryCode ?: 'XX';
    }
    
}
