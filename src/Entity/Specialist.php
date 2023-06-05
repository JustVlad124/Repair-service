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

    #[ORM\OneToMany(mappedBy: 'specialist', targetEntity: Respond::class)]
    private Collection $responds;

    #[ORM\OneToMany(mappedBy: 'specialist', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->responds = new ArrayCollection();
        $this->orders = new ArrayCollection();
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
     * @return Collection<int, Respond>
     */
    public function getResponds(): Collection
    {
        return $this->responds;
    }

    public function addRespond(Respond $respond): self
    {
        if (!$this->responds->contains($respond)) {
            $this->responds->add($respond);
            $respond->setSpecialist($this);
        }

        return $this;
    }

    public function removeRespond(Respond $respond): self
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
}
