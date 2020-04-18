<?php

namespace App\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="thing")
 */
class Thing
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
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @var string
     */
    private $type; //filtro, mascarilla, pantalla

    /**
     * @ORM\Column(type="string", length=180)
     *
     * @var string
     */
    private $model; //Modelo grande, pequeño

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     *
     * @var string|null
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     *
     * @var string|null
     */
    private $urlThingiverse;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     *
     * @var string|null
     */
    private $otherUrl;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     *
     * @var string|null
     */
    private $photoUrl;

    /**
     * @ORM\OneToMany(targetEntity="App\Persistence\Doctrine\Entity\Task", mappedBy="thing")
     *
     * @var Collection<int, Task>
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="App\Persistence\Doctrine\Entity\Needs", mappedBy="thing", orphanRemoval=true)
     *
     * @var ArrayCollection<int, Needs>
     */
    private $needs;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $valid = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Persistence\Doctrine\Entity\User")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id")
     */
    private $owner;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->needs = new ArrayCollection();
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

    public function model(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Collection<int, Task>
     */
    public function tasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): void
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
        }
    }

    public function removeTask(Task $task): void
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
        }
    }

    public function urlThingiverse(): ?string
    {
        return $this->urlThingiverse;
    }

    public function setUrlThingiverse(?string $urlThingiverse): void
    {
        $this->urlThingiverse = $urlThingiverse;
    }

    public function otherUrl(): ?string
    {
        return $this->otherUrl;
    }

    public function setOtherUrl(?string $otherUrl): void
    {
        $this->otherUrl = $otherUrl;
    }

    public function photoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(?string $photoUrl): void
    {
        $this->photoUrl = $photoUrl;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return User
     */
    public function owner()
    {
        return $this->owner;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    public function valid(): bool
    {
        return $this->valid;
    }

    public function validate(): void
    {
        $this->valid = true;
    }

    public function invalidate(): void
    {
        $this->valid = false;
    }

    /**
     * @return Collection<int, Task>
     */
    public function needs(): Collection
    {
        return $this->needs;
    }

    public function addNeed(Needs $need): void
    {
        if (!$this->needs->contains($need)) {
            $this->needs[] = $need;
        }
    }

    public function removeNeed(Needs $need): void
    {
        if ($this->needs->contains($need)) {
            $this->needs->removeElement($need);
        }
    }
}
