<?php

namespace App\Entity;

use App\Repository\AGalleryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AGalleryRepository::class)]
#[Vich\Uploadable()]
class AGallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api', 'gallery'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api', 'gallery'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['api', 'gallery'])]
    private ?string $description = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['api', 'gallery'])]
    private ?array $imageNames = [];

    #[Vich\UploadableField(mapping: 'gallery_images', fileNameProperty: 'tempImageFile')]
    #[Assert\Image(maxSize: '5M')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $tempImageFile = null;

    #[ORM\Column]
    #[Groups(['api', 'gallery'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->imageNames = [];
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }
    
    public function getImageNames(): ?array { return $this->imageNames; }
    public function setImageNames(?array $imageNames): static { $this->imageNames = $imageNames; return $this; }
    public function addImageName(string $imageName): static { $this->imageNames[] = $imageName; return $this; }
    
    public function getImageFile(): ?File { return $this->imageFile; }
    public function setImageFile(?File $imageFile): static
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }
    
    public function getTempImageFile(): ?string { return $this->tempImageFile; }
    public function setTempImageFile(?string $tempImageFile): static { $this->tempImageFile = $tempImageFile; return $this; }
    
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }
    
    #[Groups(['api', 'gallery'])]
    public function getImageUrls(): array
    {
        $urls = [];
        foreach ($this->imageNames as $imageName) {
            $urls[] = '/images/gallery/' . $imageName;
        }
        return $urls;
    }
}
