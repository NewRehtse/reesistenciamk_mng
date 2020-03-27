<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="needs")
 * @ORM\Entity(repositoryClass="App\Repository\NeedsRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="needs")
     * @ORM\JoinColumn(name="need", referencedColumnName="id")
     *
     * @var Place|null
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Thing")
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
}
