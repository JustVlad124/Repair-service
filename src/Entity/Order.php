<?php

namespace App\Entity;

use App\Entity\OrderState\ArchiveState;
use App\Entity\OrderState\InPendingState;
use App\Entity\OrderState\InProgressState;
use App\Entity\OrderState\OrderStateInterface;
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

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Specialist $specialist = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: SpecialistRespond::class)]
    private Collection $responds;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: ClientOffer::class)]
    private Collection $clientOffers;

    #[ORM\OneToMany(mappedBy: 'orderAddress', targetEntity: Address::class)]
    private Collection $address;

    #[ORM\ManyToOne(inversedBy: 'order')]
    private ?OrderState $orderState = null;



    public function __construct()
    {
        $this->responds = new ArrayCollection();
        $this->clientOffers = new ArrayCollection();
        $this->address = new ArrayCollection();
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
            $respond->setOrderId($this);
        }

        return $this;
    }

    public function removeRespond(SpecialistRespond $respond): self
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
            $clientOffer->setOrder($this);
        }

        return $this;
    }

    public function removeClientOffer(ClientOffer $clientOffer): self
    {
        if ($this->clientOffers->removeElement($clientOffer)) {
            // set the owning side to null (unless already changed)
            if ($clientOffer->getOrder() === $this) {
                $clientOffer->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address->add($address);
            $address->setOrderAddress($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->address->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getOrderAddress() === $this) {
                $address->setOrderAddress(null);
            }
        }

        return $this;
    }

    public function getOrderState(): ?OrderState
    {
        return $this->orderState;
    }

    public function setOrderState(?OrderState $orderState): self
    {
        $this->orderState = $orderState;

        return $this;
    }
}
