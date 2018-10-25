<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $addressStreetFact;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $addressCityFact;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $addressCountryFact;


    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    private $addressZipCodeFact;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $addressStreetDelivery;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $addressCityDelivery;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $addressCountryDelivery;


    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    private $addressZipCodeDelivery;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressStreetFact()
    {
        return $this->addressStreetFact;
    }

    /**
     * @param mixed $addressStreetFact
     */
    public function setAddressStreetFact($addressStreetFact): void
    {
        $this->addressStreetFact = $addressStreetFact;
    }

    /**
     * @return mixed
     */
    public function getAddressCityFact()
    {
        return $this->addressCityFact;
    }

    /**
     * @param mixed $addressCityFact
     */
    public function setAddressCityFact($addressCityFact): void
    {
        $this->addressCityFact = $addressCityFact;
    }

    /**
     * @return mixed
     */
    public function getAddressCountryFact()
    {
        return $this->addressCountryFact;
    }

    /**
     * @param mixed $addressCountryFact
     */
    public function setAddressCountryFact($addressCountryFact): void
    {
        $this->addressCountryFact = $addressCountryFact;
    }

    /**
     * @return mixed
     */
    public function getAddressZipCodeFact()
    {
        return $this->addressZipCodeFact;
    }

    /**
     * @param mixed $addressZipCodeFact
     */
    public function setAddressZipCodeFact($addressZipCodeFact): void
    {
        $this->addressZipCodeFact = $addressZipCodeFact;
    }

    /**
     * @return mixed
     */
    public function getAddressStreetDelivery()
    {
        return $this->addressStreetDelivery;
    }

    /**
     * @param mixed $addressStreetDelivery
     */
    public function setAddressStreetDelivery($addressStreetDelivery): void
    {
        $this->addressStreetDelivery = $addressStreetDelivery;
    }

    /**
     * @return mixed
     */
    public function getAddressCityDelivery()
    {
        return $this->addressCityDelivery;
    }

    /**
     * @param mixed $addressCityDelivery
     */
    public function setAddressCityDelivery($addressCityDelivery): void
    {
        $this->addressCityDelivery = $addressCityDelivery;
    }

    /**
     * @return mixed
     */
    public function getAddressCountryDelivery()
    {
        return $this->addressCountryDelivery;
    }

    /**
     * @param mixed $addressCountryDelivery
     */
    public function setAddressCountryDelivery($addressCountryDelivery): void
    {
        $this->addressCountryDelivery = $addressCountryDelivery;
    }

    /**
     * @return mixed
     */
    public function getAddressZipCodeDelivery()
    {
        return $this->addressZipCodeDelivery;
    }

    /**
     * @param mixed $addressZipCodeDelivery
     */
    public function setAddressZipCodeDelivery($addressZipCodeDelivery): void
    {
        $this->addressZipCodeDelivery = $addressZipCodeDelivery;
    }


    
    
}
