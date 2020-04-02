<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="maker")
 */
class Maker
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
     * @ORM\OneToOne(targetEntity="User", mappedBy="maker")
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    private $printer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="maker")
     *
     * @var Task[]
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function printer(): ?string
    {
        return $this->printer;
    }

    public function setPrinter(?string $printer): void
    {
        $this->printer = $printer;
    }

    /**
     * @return Task[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @param Task[] $tasks
     *
     * @return $this
     */
    public function setTasks(array $tasks)
    {
        $this->tasks = $tasks;

        return $this;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
