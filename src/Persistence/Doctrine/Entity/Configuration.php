<?php

namespace App\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Persistence\Doctrine\Repository\ConfigurationRepository")
 */
class Configuration
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
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $usersCanCreatePrints = true;

    public function id(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function usersCanCreatePrints(): bool
    {
        return $this->usersCanCreatePrints;
    }

    public function setUsersCanCreatePrints(bool $usersCanCreatePrints): void
    {
        $this->usersCanCreatePrints = $usersCanCreatePrints;
    }
}
