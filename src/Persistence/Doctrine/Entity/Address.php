<?php

namespace App\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="address")
 */
class Address
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
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $address1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $address2;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $postalCode;

    public function id(): int
    {
        return $this->id;
    }

    public function address1(): ?string
    {
        return $this->address1;
    }

    public function address2(): ?string
    {
        return $this->address2;
    }

    /**
     * @return string
     */
    public function city(): ?string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function postalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setAddress1(?string $address1): void
    {
        $this->address1 = $address1;
    }

    public function setAddress2(?string $address2): void
    {
        $this->address2 = $address2;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }
}
