<?php

namespace App\Entity;

use App\Repository\RespondRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RespondRepository::class)]
class Respond
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'responds')]
    private ?Order $order_id = null;

    #[ORM\ManyToOne(inversedBy: 'responds')]
    private ?Specialist $specialist = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?Order
    {
        return $this->order_id;
    }

    public function setOrderId(?Order $order_id): self
    {
        $this->order_id = $order_id;

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
