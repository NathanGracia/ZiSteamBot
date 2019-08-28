<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InventoryItemRepository")
 */
class InventoryItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idItem;

    /**
     * @ORM\Column(type="float")
     */
    private $buyPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $soldPrice;

    /**
     * @ORM\Column(type="object")
     */
    private $item;

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    private $buyForm;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getidItem(): ?string
    {
        return $this->idItem;
    }

    public function setidItem(string $idItem): self
    {
        $this->idItem = $idItem;

        return $this;
    }

    public function getbuyPrice(): ?float
    {
        return $this->buyPrice;
    }

    public function setbuyPrice(float $buyPrice): self
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    public function getSoldPrice(): ?float
    {
        return $this->soldPrice;
    }

    public function setSoldPrice(?float $soldPrice): self
    {
        $this->soldPrice = $soldPrice;

        return $this;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function setItem($item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getBuyForm()
    {
        return $this->buyForm;
    }

    public function setBuyForm($buyForm): self
    {
        $this->buyForm = $buyForm;

        return $this;
    }
}
