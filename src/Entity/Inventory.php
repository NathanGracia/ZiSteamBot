<?php


namespace App\Entity;


class Inventory
{

/** @var array $items */
    protected $items = [];

    /** @var float $totalValue */
    private $totalValue;

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return float
     */
    public function getTotalValue(): float
    {
        return $this->totalValue;
    }

    /**
     * @param float $totalValue
     */
    public function setTotalValue(float $totalValue): void
    {
        $this->totalValue = $totalValue;
    }

    public function addItem(Item $item){
        array_push($this->items,  $item);
    }


}