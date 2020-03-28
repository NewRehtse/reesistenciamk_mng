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
     * @ORM\Column(type="string", length=191, unique=true)
     *
     * @var string
     */
    private $name = '';

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     *
     * @var string
     */
    private $address = '';

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     *
     * @var string
     */
    private $city = '';

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     *
     * @var string
     */
    private $postalCode = '';

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

    public function address(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function needs(): PersistentCollection
    {
        return $this->needs;
    }
}
