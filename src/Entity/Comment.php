<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'Le nom doit contenir au moins 2 caractères')]
    #[Assert\Regex(pattern: '/^[a-zA-ZÀ-ÿ\s\-]+$/', message: 'Le nom ne doit contenir que des lettres, espaces et tirets')]
    private ?string $lastname = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'Le prénom doit contenir au moins 2 caractères')]
    #[Assert\Regex(pattern: '/^[a-zA-ZÀ-ÿ\s\-]+$/', message: 'Le prénom ne doit contenir que des lettres, espaces et tirets')]
    private ?string $firstname = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Le numéro de téléphone est obligatoire')]
    #[Assert\Regex(
        pattern: '/^(?:(?:\+243|0)[1-9]\d{7,8}|(?:\+|00)[1-9]\d{7,14})$/',
        message: 'Veuillez entrer un numéro de téléphone valide (RDC: +243XXX... ou international)'
    )]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email(message: 'Veuillez entrer une adresse email valide')]
    #[Assert\Length(max: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le commentaire est obligatoire')]
    #[Assert\Length(min: 3, max: 2000, minMessage: 'Le commentaire doit contenir au moins 3 caractères', maxMessage: 'Le commentaire ne peut pas dépasser 2000 caractères')]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $isApproved = false;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $likes = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $relation = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $isRejected = false;

    #[ORM\ManyToOne(inversedBy: 'commentss')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Article $article = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAgent = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->isApproved = false;
        $this->likes = 0;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        // Nettoyer et formater
        $firstname = trim($firstname);
        $firstname = strip_tags($firstname);
        $firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
        $this->firstname = ucfirst(strtolower($firstname));

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        // Nettoyer et formater
        $lastname = trim($lastname);
        $lastname = strip_tags($lastname);
        $lastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
        $this->lastname = ucfirst(strtolower($lastname));

        return $this;
    }

    public function getFullname(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        // Nettoyer et formater le numéro
        $phone = trim($phone);
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Convertir les numéros locaux RDC en format international
        if (preg_match('/^0([1-9]\d{7,8})$/', $phone, $matches)) {
            $phone = '+243' . $matches[1];
        }
        
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        if ($email) {
            $email = trim($email);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $email = strtolower($email);
        }
        $this->email = $email;

        return $this;
    }

    public function isRejected(): ?bool
    {
        return $this->isRejected;
    }
    
    public function setIsRejected(bool $isRejected): static
    {
        $this->isRejected = $isRejected;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        // Nettoyer le contenu
        $content = trim($content);
        $content = strip_tags($content, '<p><br><strong><em><u>');
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        
        // Convertir les URLs en liens cliquables
        $content = preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" rel="nofollow noopener">$1</a>',
            $content
        );
        
        $this->content = $content;
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

    public function isApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): static
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(?int $likes): static
    {
        $this->likes = $likes;

        return $this;
    }

    public function getRelation(): ?string
    {
        return $this->relation;
    }

    public function setRelation(?string $relation): static
    {
        $this->relation = $relation;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }
}
