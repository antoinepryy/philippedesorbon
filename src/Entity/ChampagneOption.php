<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChampagneOptionRepository")
 */
class ChampagneOption
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
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $stepOrder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Champagne", inversedBy="champagneOptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $champagne;

    public function __construct()
    {
        $this->stepOrder = null;
    }

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getChampagne(): ?Champagne
    {
        return $this->champagne;
    }

    public function setChampagne(?Champagne $champagne): self
    {
        $this->champagne = $champagne;

        return $this;
    }

    public function getStepOrder()
    {
        return $this->stepOrder;
    }


    public function setStepOrder($stepOrder): void
    {
        $this->stepOrder = $stepOrder;
    }
}


