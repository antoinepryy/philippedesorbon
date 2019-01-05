<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $paymentMethod;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank()
     */
    private $addressNameFact;

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
    private $addressNameDelivery;

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

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $buyerComment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Range(
     *      min = "+7 days",
     *      minMessage="délai de livraison spécifié trop court"
     * )
     */
    private $dateDelivery;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $telDelivery;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $telFact;

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

    /**
     * @return mixed
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param mixed $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return mixed
     */
    public function getAddressNameFact()
    {
        return $this->addressNameFact;
    }

    /**
     * @param mixed $addressNameFact
     */
    public function setAddressNameFact($addressNameFact): void
    {
        $this->addressNameFact = $addressNameFact;
    }

    /**
     * @return mixed
     */
    public function getAddressNameDelivery()
    {
        return $this->addressNameDelivery;
    }

    /**
     * @param mixed $addressNameDelivery
     */
    public function setAddressNameDelivery($addressNameDelivery): void
    {
        $this->addressNameDelivery = $addressNameDelivery;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function __toString(){
        return 'Commande#'.$this->getId() ;
    }

    public function getDateDelivery(): ?\DateTimeInterface
    {
        return $this->dateDelivery;
    }

    public function setDateDelivery(?\DateTimeInterface $dateDelivery): self
    {
        $this->dateDelivery = $dateDelivery;

        return $this;
    }

    public function getTelDelivery(): ?string
    {
        return $this->telDelivery;
    }

    public function setTelDelivery(string $telDelivery): self
    {
        $this->telDelivery = $telDelivery;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTelFact()
    {
        return $this->telFact;
    }

    /**
     * @param mixed $telFact
     */
    public function setTelFact($telFact): void
    {
        $this->telFact = $telFact;
    }

    /**
     * @return mixed
     */
    public function getBuyerComment()
    {
        return $this->buyerComment;
    }

    /**
     * @param mixed $buyerComment
     */
    public function setBuyerComment($buyerComment): void
    {
        $this->buyerComment = $buyerComment;
    }

}


