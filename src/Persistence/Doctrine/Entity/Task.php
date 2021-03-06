<?php

namespace App\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Task
{
    use Audit;

    public const DELIVER_TYPE_UNDEFINED = -1; //Not defined
    public const DELIVER_TYPE_COLLECT = 0; //Collect at home
    public const DELIVER_TYPE_DELIVER = 1; //Deliver at place

    public const STATUS_COLLECTED = 1;
    public const STATUS_DELIVERED = 2;
    public const STATUS_DONE = 3;
    public const STATUS_COLLECT_REQUESTED = 4;
    private const STATUS_DEFAULT = self::STATUS_DONE;

    private const VALID_DELIVER = [
        self::DELIVER_TYPE_UNDEFINED,
        self::DELIVER_TYPE_COLLECT,
        self::DELIVER_TYPE_DELIVER,
    ];

    private const VALID_STATUS = [
        self::STATUS_DONE,
        self::STATUS_DELIVERED,
        self::STATUS_COLLECTED,
        self::STATUS_COLLECT_REQUESTED,
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
     * @ORM\ManyToOne(targetEntity="App\Persistence\Doctrine\Entity\Maker", inversedBy="tasks")
     * @ORM\JoinColumn(name="maker", referencedColumnName="id")
     *
     * @var Maker
     */
    private $maker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Persistence\Doctrine\Entity\Thing", inversedBy="tasks")
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
     * @ORM\ManyToOne(targetEntity="Place")
     *
     * @var Place|null
     */
    private $place;

    /**
     * @ORM\OneToOne(targetEntity="Address", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="collectAddress", referencedColumnName="id")
     *
     * @var Address|null
     */
    private $collectAddress;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $status = self::STATUS_DEFAULT;

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

    public function maker(): Maker
    {
        return $this->maker;
    }

    public function setMaker(Maker $maker): void
    {
        $this->maker = $maker;
    }

    public function thing(): ?Thing
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

    public function collectAddress(): ?Address
    {
        return $this->collectAddress;
    }

    public function setCollectAddress(?Address $collectAddress): void
    {
        $this->collectAddress = $collectAddress;
    }

    public static function GetStatusText(int $status): string
    {
        $statusMap = [
            self::STATUS_DELIVERED => 'Entregado',
            self::STATUS_COLLECTED => 'Recogido',
            self::STATUS_DONE => 'Hecho',
            self::STATUS_COLLECT_REQUESTED => 'Pendiente de recogida',
        ];

        return $statusMap[$status] ?? '';
    }

    public static function GetDeliveryTypeText(int $deliveryType): string
    {
        $deliveryTypeMap = [
            self::DELIVER_TYPE_UNDEFINED => 'Por definir',
            self::DELIVER_TYPE_COLLECT => 'Recogida',
            self::DELIVER_TYPE_DELIVER => 'Entrega',
        ];

        return $deliveryTypeMap[$deliveryType] ?? '';
    }
}
