<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="place")
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 */
class Place
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
     * @ORM\OneToOne(targetEntity="User", mappedBy="place")
     *
     * @var User|null
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Address", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="address", referencedColumnName="id")
     *
     * @var Address|null
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     *
     * @var string
     */
    private $name = '';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Needs", mappedBy="place")
     *
     * @var PersistentCollection
     */
    private $needs;

    public function __construct()
    {
        $this->needs = new ArrayCollection();
    }

    public function id(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function needs(): PersistentCollection
    {
        return $this->needs;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function address(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }
}
