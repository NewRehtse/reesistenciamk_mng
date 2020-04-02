<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    use Audit;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @var string|null
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     * @var array
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $nick;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $nickTelegram;

    /**
     * @ORM\OneToOne(targetEntity="Maker", inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="maker", referencedColumnName="id")
     *
     * @var Maker|null
     */
    private $maker;

    /**
     * @ORM\OneToOne(targetEntity="Delivery", inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="delivery", referencedColumnName="id")
     *
     * @var Delivery|null
     */
    private $delivery;

    /**
     * @ORM\OneToOne(targetEntity="Place", inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="place", referencedColumnName="id")
     *
     * @var Place|null
     */
    private $place;

    /**
     * @ORM\OneToOne(targetEntity="Address", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="address", referencedColumnName="id")
     *
     * @var Address|null
     */
    private $address;

    private $userType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return \array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNick(): ?string
    {
        return $this->nick;
    }

    public function setNick(string $nick): self
    {
        $this->nick = $nick;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function nickTelegram(): ?string
    {
        return $this->nickTelegram;
    }

    public function setNickTelegram(?string $nickTelegram): void
    {
        $this->nickTelegram = $nickTelegram;
    }

    public function address(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function maker(): ?Maker
    {
        return $this->maker;
    }

    public function setMaker(?Maker $maker): void
    {
        $this->roles[] = 'ROLE_MAKER';
        $this->maker = $maker;
    }

    public function delivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(?Delivery $delivery): void
    {
        $this->roles[] = 'ROLE_DELIVERY';
        $this->delivery = $delivery;
    }

    public function place(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): void
    {
        $this->roles[] = 'ROLE_PLACE';
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function userType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType): void
    {
        $this->userType = $userType;
    }
}
