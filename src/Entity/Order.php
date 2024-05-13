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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne]
    private ?User $user_id = null;
    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OrderDetails::class, orphanRemoval: true, cascade: ['persist'])]
    private $orderDetails;
    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }
    public function addOrdersDetail(OrderDetails $ordersDetail): self
    {
        if (!$this->orderDetails->contains($ordersDetail)) {
            $this->orderDetails[] = $ordersDetail;
            $ordersDetail->setOrder($this);
        }

        return $this;
    }
    
}
