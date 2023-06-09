<?php

namespace App\Entity;

use App\Repository\SpecialistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialistRepository::class)]
class Specialist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'specialist', targetEntity: SpecialistRespond::class)]
    private Collection $responds;

    #[ORM\OneToMany(mappedBy: 'specialist', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'specialist', targetEntity: ClientOffer::class)]
    private Collection $clientOffers;

    #[ORM\OneToMany(mappedBy: 'specialist', targetEntity: SpecialistRating::class)]
    private Collection $specialistRatings;

    #[ORM\OneToMany(mappedBy: 'specialist', targetEntity: Portfolio::class)]
    private Collection $portfolios;

    public function __construct()
    {
        $this->responds = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->clientOffers = new ArrayCollection();
        $this->specialistRatings = new ArrayCollection();
        $this->portfolios = new ArrayCollection();
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
     * @return Collection<int, SpecialistRespond>
     */
    public function getResponds(): Collection
    {
        return $this->responds;
    }

    public function addRespond(SpecialistRespond $respond): self
    {
        if (!$this->responds->contains($respond)) {
            $this->responds->add($respond);
            $respond->setSpecialist($this);
        }

        return $this;
    }

    public function removeRespond(SpecialistRespond $respond): self
    {
        if ($this->responds->removeElement($respond)) {
            // set the owning side to null (unless already changed)
            if ($respond->getSpecialist() === $this) {
                $respond->setSpecialist(null);
            }
        }

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
            $order->setSpecialist($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getSpecialist() === $this) {
                $order->setSpecialist(null);
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
            $clientOffer->setSpecialist($this);
        }

        return $this;
    }

    public function removeClientOffer(ClientOffer $clientOffer): self
    {
        if ($this->clientOffers->removeElement($clientOffer)) {
            // set the owning side to null (unless already changed)
            if ($clientOffer->getSpecialist() === $this) {
                $clientOffer->setSpecialist(null);
            }
        }

        return $this;
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
            $specialistRating->setSpecialist($this);
        }

        return $this;
    }

    public function removeSpecialistRating(SpecialistRating $specialistRating): self
    {
        if ($this->specialistRatings->removeElement($specialistRating)) {
            // set the owning side to null (unless already changed)
            if ($specialistRating->getSpecialist() === $this) {
                $specialistRating->setSpecialist(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Portfolio>
     */
    public function getPortfolios(): Collection
    {
        return $this->portfolios;
    }

    public function addPortfolio(Portfolio $portfolio): self
    {
        if (!$this->portfolios->contains($portfolio)) {
            $this->portfolios->add($portfolio);
            $portfolio->setSpecialist($this);
        }

        return $this;
    }

    public function removePortfolio(Portfolio $portfolio): self
    {
        if ($this->portfolios->removeElement($portfolio)) {
            // set the owning side to null (unless already changed)
            if ($portfolio->getSpecialist() === $this) {
                $portfolio->setSpecialist(null);
            }
        }

        return $this;
    }
}
