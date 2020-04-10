<?php

namespace App\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="place")
 * @ORM\Entity(repositoryClass="App\Persistence\Doctrine\Repository\PlaceRepository")
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
     * @ORM\Column(type="boolean")
     *
     * @var string
     */
    private $valid = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Persistence\Doctrine\Entity\Needs", mappedBy="place")
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

    public function address(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function validate(): void
    {
        $this->valid = true;
    }

    public function invalidate(): void
    {
        $this->valid = false;
    }
}
