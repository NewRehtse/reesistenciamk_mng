<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Task
{
    public const DELIVER_TYPE_UNDEFINED = -1; //Not defined
    public const DELIVER_TYPE_COLLECT = 0; //Collect at home
    public const DELIVER_TYPE_DELIVER = 1; //Deliver at place

    public const STATUS_PENDING = 0;
    public const STATUS_PROCESSING = 1;
    public const STATUS_DELIVERING = 2;
    public const STATUS_DONE = 3;

    private const VALID_DELIVER = [
        self::DELIVER_TYPE_UNDEFINED,
        self::DELIVER_TYPE_COLLECT,
        self::DELIVER_TYPE_DELIVER,
    ];

    private const VALID_STATUS = [
        self::STATUS_PENDING,
        self::STATUS_DONE,
        self::STATUS_DELIVERING,
        self::STATUS_PROCESSING,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="maker", referencedColumnName="id")
     *
     * @var User
     */
    private $maker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Thing", inversedBy="tasks")
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

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $deliveryType = self::DELIVER_TYPE_UNDEFINED;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $extra;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Place")
     *
     * @var Place|null
     */
    private $place;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @var \DateTime|null
     */
    private $deliveryDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $collectAddress;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $status = self::STATUS_PENDING;

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function maker(): User
    {
        return $this->maker;
    }

    public function setMaker(User $maker): void
    {
        $this->maker = $maker;
    }

    public function thing(): Thing
    {
        return $this->thing;
    }

    public function setThing(Thing $thing): void
    {
        $this->thing = $thing;
    }

    /**
     * @return int
     */
    public function amount()
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        if ($amount > 0) {
            $this->amount = $amount;
        }
    }

    public function deliveryType(): int
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(int $deliveryType): void
    {
        if (\in_array($deliveryType, static::VALID_DELIVER, true)) {
            $this->deliveryType = $deliveryType;
        }
    }

    public function extra(): ?string
    {
        return $this->extra;
    }

    public function setExtra(?string $extra): void
    {
        $this->extra = $extra;
    }

    public function place(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): void
    {
        $this->place = $place;
    }

    public function deliveryDate(): ?\DateTime
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(?\DateTime $deliveryDate): void
    {
        $this->deliveryDate = $deliveryDate;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        if (\in_array($status, static::VALID_STATUS, true)) {
            $this->status = $status;
        }
    }

    public function collectAddress(): ?string
    {
        return $this->collectAddress;
    }

    public function setCollectAddress(?string $collectAddress): void
    {
        $this->collectAddress = $collectAddress;
    }
}
