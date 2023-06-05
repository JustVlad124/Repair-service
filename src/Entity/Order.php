<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $orderName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $cost = null;

//    #[ORM\ManyToOne(inversedBy: 'orders')]
//    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Specialist $specialist = null;

    #[ORM\OneToMany(mappedBy: 'order_id', targetEntity: Respond::class)]
    private Collection $responds;
    public function __construct()
    {
        $this->responds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderName(): ?string
    {
        return $this->orderName;
    }

    public function setOrderName(string $orderName): self
    {
        $this->orderName = $orderName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

//    public function getUser(): ?User
//    {
//        return $this->user;
//    }
//
//    public function setUser(?User $user): self
//    {
//        $this->user = $user;
//
//        return $this;
//    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
            $respond->setOrderId($this);
        }

        return $this;
    }

    public function removeRespond(Respond $respond): self
    {
        if ($this->responds->removeElement($respond)) {
            // set the owning side to null (unless already changed)
            if ($respond->getOrderId() === $this) {
                $respond->setOrderId(null);
            }
        }

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
