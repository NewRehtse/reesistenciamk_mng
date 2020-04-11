<?php

namespace App\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class RequestCollect
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
     * @ORM\ManyToMany(targetEntity="App\Persistence\Doctrine\Entity\Task")
     * @ORM\JoinTable(name="requestcollect_tasks",
     *      joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="requestcollect_id", referencedColumnName="id")}
     *      )
     *
     * @var array
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = [];
    }

    /**
     * @ORM\Column(type="date")
     *
     * @var \DateTime
     */
    private $date;

    public function id(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Task[]
     */
    public function tasks(): array
    {
        return $this->tasks;
    }

    /**
     * @param Task[] $tasks
     */
    public function setTasks(array $tasks): void
    {
        $this->tasks = $tasks;
    }

    public function date(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }
}
