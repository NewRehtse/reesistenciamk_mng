<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="thing")
     *
     * @var PersistentCollection
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
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

    public function tasks(): PersistentCollection
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
}
