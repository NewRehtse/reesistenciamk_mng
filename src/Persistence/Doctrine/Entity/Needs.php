<?php

namespace App\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="needs")
 * @ORM\Entity(repositoryClass="App\Persistence\Doctrine\Repository\NeedsRepository")
 */
class Needs
{
    use Audit;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="needs")
     * @ORM\JoinColumn(name="place", referencedColumnName="id")
     *
     * @var Place|null
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity="App\Persistence\Doctrine\Entity\Thing", inversedBy="needs")
     *
     * @var Thing|null
     */
    private $thing;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $amount = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    private $covered = 0;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $valid = false;

    public function place(): ?Place
    {
        return $this->place;
    }

    public function setPlace(Place $place): void
    {
        $this->place = $place;
    }

    public function thing(): ?Thing
    {
        return $this->thing;
    }

    public function setThing(Thing $thing): void
    {
        $this->thing = $thing;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        if ($amount > 0) {
            $this->amount = $amount;
        }
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function covered(): int
    {
        return $this->covered;
    }

    public function setCovered(int $covered): void
    {
        $this->covered = $covered;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function validate(): void
    {
        $this->valid = true;
    }
}
