<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 17/09/2018
 * Time: 02:10
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Email already taken")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=191, unique=false)
     * @Assert\NotBlank(groups={"registration, change_infos"})
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\NotBlank(groups={"registration, change_infos"})
     * @Assert\Email(groups={"registration, change_infos"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\NotBlank(groups={"registration, change_infos"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=191, unique=false)
     * @Assert\NotBlank(groups={"registration, change_infos"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=191, unique=false)
     * @Assert\NotBlank(groups={"registration, change_infos"})
     */
    private $lastName;

    /**
     * @Assert\Length(max=4096)
     * @Assert\NotBlank(groups={"registration, change_password"})
     * @Assert\Length(min=7, groups={"registration, change_password"})
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank(groups={"registration, change_infos"})
     */
    private $addressStreet;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank(groups={"registration, change_infos"})
     */
    private $addressCity;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank(groups={"registration, change_infos"})
     */
    private $addressCountry;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(groups={"registration, change_infos"})
     * @Assert\Type(type="integer", groups={"registration, change_infos"})
     */
    private $addressZipCode;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    private $passwordLink;

    /**
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="buyer", orphanRemoval=true)
     */
    private $orders;


    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\NotBlank(groups={"registration, change_infos"})
     */
    private $phoneNumber;

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }


    /**
     * @return mixed
     */
    public function getPasswordLink()
    {
        return $this->passwordLink;
    }

    /**
     * @param mixed $passwordLink
     */
    public function setPasswordLink($passwordLink): void
    {
        $this->passwordLink = $passwordLink;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAddressStreet()
    {
        return $this->addressStreet;
    }

    /**
     * @param mixed $addressStreet
     */
    public function setAddressStreet($addressStreet): void
    {
        $this->addressStreet = $addressStreet;
    }

    /**
     * @return mixed
     */
    public function getAddressCity()
    {
        return $this->addressCity;
    }

    /**
     * @param mixed $addressCity
     */
    public function setAddressCity($addressCity): void
    {
        $this->addressCity = $addressCity;
    }

    /**
     * @return mixed
     */
    public function getAddressCountry()
    {
        return $this->addressCountry;
    }

    /**
     * @param mixed $addressCountry
     */
    public function setAddressCountry($addressCountry): void
    {
        $this->addressCountry = $addressCountry;
    }

    /**
     * @return mixed
     */
    public function getAddressZipCode()
    {
        return $this->addressZipCode;
    }

    /**
     * @param mixed $addressZipCode
     */
    public function setAddressZipCode($addressZipCode): void
    {
        $this->addressZipCode = $addressZipCode;
    }


    public function __construct()
    {
        $this->roles = array('ROLE_USER');
        $this->passwordLink = null;
        $this->orders = new ArrayCollection();
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        $this->username = $email;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * @param mixed $civility
     */
    public function setCivility($civility): void
    {
        $this->civility = $civility;
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function promoteAdmin(){
        $this->roles = array('ROLE_USER', 'ROLE_ADMIN');
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Commande[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Commande $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setBuyer($this);
        }

        return $this;
    }

    public function removeOrder(Commande $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getBuyer() === $this) {
                $order->setBuyer(null);
            }
        }

        return $this;
    }

}