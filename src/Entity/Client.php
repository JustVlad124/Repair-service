<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: ClientOffer::class)]
    private Collection $clientOffers;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: SpecialistRating::class)]
    private Collection $specialistRatings;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->clientOffers = new ArrayCollection();
        $this->specialistRatings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setClient($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getClient() === $this) {
                $order->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClientOffer>
     */
    public function getClientOffers(): Collection
    {
        return $this->clientOffers;
    }

    public function addClientOffer(ClientOffer $clientOffer): self
    {
        if (!$this->clientOffers->contains($clientOffer)) {
            $this->clientOffers->add($clientOffer);
            $clientOffer->setClient($this);
        }

        return $this;
    }

    public function removeClientOffer(ClientOffer $clientOffer): self
    {
        if ($this->clientOffers->removeElement($clientOffer)) {
            // set the owning side to null (unless already changed)
            if ($clientOffer->getClient() === $this) {
                $clientOffer->setClient(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->user->getEmail();
    }

    /**
     * @return Collection<int, SpecialistRating>
     */
    public function getSpecialistRatings(): Collection
    {
        return $this->specialistRatings;
    }

    public function addSpecialistRating(SpecialistRating $specialistRating): self
    {
        if (!$this->specialistRatings->contains($specialistRating)) {
            $this->specialistRatings->add($specialistRating);
            $specialistRating->setClient($this);
        }

        return $this;
    }

    public function removeSpecialistRating(SpecialistRating $specialistRating): self
    {
        if ($this->specialistRatings->removeElement($specialistRating)) {
            // set the owning side to null (unless already changed)
            if ($specialistRating->getClient() === $this) {
                $specialistRating->setClient(null);
            }
        }

        return $this;
    }
}
