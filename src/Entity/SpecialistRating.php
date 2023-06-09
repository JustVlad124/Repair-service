<?php

namespace App\Entity;

use App\Repository\SpecialistRatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialistRatingRepository::class)]
class SpecialistRating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000)]
    private ?string $ratingText = null;

    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'specialistRatings')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'specialistRatings')]
    private ?Specialist $specialist = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRatingText(): ?string
    {
        return $this->ratingText;
    }

    public function setRatingText(string $ratingText): self
    {
        $this->ratingText = $ratingText;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getSpecialist(): ?Specialist
    {
        return $this->specialist;
    }

    public function setSpecialist(?Specialist $specialist): self
    {
        $this->specialist = $specialist;

        return $this;
    }
}
