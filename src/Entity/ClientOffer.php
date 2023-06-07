<?php

namespace App\Entity;

use App\Repository\ClientOfferRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientOfferRepository::class)]
class ClientOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'clientOffers')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'clientOffers')]
    private ?Order $order = null;

    #[ORM\ManyToOne(inversedBy: 'clientOffers')]
    private ?Specialist $specialist = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order_id): self
    {
        $this->order = $order_id;

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
