<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $actualPrice;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lastIncrease ;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $steamLink;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $medianPrice ;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $volume;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $growthToMedian ;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score ;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getActualPrice(): ?float
    {
        return $this->actualPrice;
    }

    public function setActualPrice(?float $actualPrice): self
    {
        $this->actualPrice = $actualPrice;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastIncrease(): ?int
    {

        return $this->lastIncrease;
    }

    public function setLastIncrease(?int $lastIncrease): self
    {

        $this->lastIncrease = $lastIncrease;

        return $this;
    }

    public function getSteamLink(): ?string
    {
        return $this->steamLink;
    }

    public function setSteamLink(string $steamLink): self
    {
        $this->steamLink = $steamLink;

        return $this;
    }

    public function getMedianPrice(): ?float
    {
        return $this->medianPrice;
    }

    public function setMedianPrice(?float $medianPrice): self
    {
        $this->medianPrice = $medianPrice;

        return $this;
    }

    public function getVolume(): ?int
    {
        return $this->volume;
    }

    public function setVolume(?int $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getGrowthToMedian(): ?int
    {
        return $this->growthToMedian;
    }

    public function setGrowthToMedian(?int $growthToMedian): self
    {
        $this->growthToMedian = $growthToMedian;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param mixed $score
     */
    public function setScore($score): void
    {
        $this->score = $score;
    }

    public  function generateScore(){
    
    }
}
