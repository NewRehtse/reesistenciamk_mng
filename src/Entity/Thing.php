<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="thing")
 */
class Thing
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
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @var string
     */
    private $model;

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
     * @var Task[]
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

    /**
     * @return mixed
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model): void
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

    /**
     * @return string|null
     */
    public function urlThingiverse(): ?string
    {
        return $this->urlThingiverse;
    }

    /**
     * @param string|null $urlThingiverse
     */
    public function setUrlThingiverse(?string $urlThingiverse): void
    {
        $this->urlThingiverse = $urlThingiverse;
    }

    /**
     * @return string|null
     */
    public function otherUrl(): ?string
    {
        return $this->otherUrl;
    }

    /**
     * @param string|null $otherUrl
     */
    public function setOtherUrl(?string $otherUrl): void
    {
        $this->otherUrl = $otherUrl;
    }

    /**
     * @return string|null
     */
    public function photoUrl(): ?string
    {
        return $this->photoUrl;
    }

    /**
     * @param string|null $photoUrl
     */
    public function setPhotoUrl(?string $photoUrl): void
    {
        $this->photoUrl = $photoUrl;
    }
}
