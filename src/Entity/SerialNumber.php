<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="serialNumbers")
 */
class SerialNumber
{
    private const SERIAL_NUMBER_PREFIX = 'RMK';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $serialNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Thing")
     * @ORM\JoinColumn(name="thing", referencedColumnName="id")
     *
     * @var Thing
     */
    private $thing;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Task")
     * @ORM\JoinColumn(name="task", referencedColumnName="id")
     *
     * @var Task
     */
    private $task;

    public function serialNumber(): int
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(int $serialNumber): void
    {
        $this->serialNumber = $serialNumber;
    }

    public function thing(): Thing
    {
        return $this->thing;
    }

    public function setThing(Thing $thing): void
    {
        $this->thing = $thing;
    }

    public function task(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    public function __toString(): string
    {
        return \sprintf('%s%09d', static::SERIAL_NUMBER_PREFIX, $this->serialNumber());
    }
}
