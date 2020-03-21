<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="needs")
 */
class Needs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Place")
     * @ORM\JoinColumn(name="place", referencedColumnName="id")
     *
     * @var Place
     */
    private $place;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Thing")
     * @ORM\JoinColumn(name="thing", referencedColumnName="id")
     *
     * @var Thing
     */
    private $thing;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $amount = 0;

    public function place(): Place
    {
        return $this->place;
    }

    public function setPlace(Place $place): void
    {
        $this->place = $place;
    }

    public function thing(): Thing
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
}
