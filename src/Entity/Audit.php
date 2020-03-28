<?php

/**
 * Trato que se añade en cada entidad con los datos de audición.
 */

namespace App\Entity;

trait Audit
{
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $crtUser;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $crtProcess;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $crtDate;

    /**
     * @ORM\Column(type="string",  length=30, nullable=true)
     */
    private $updUser;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $updProcess;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updDate;

    public function getCrtUser(): ?string
    {
        return $this->crtUser;
    }

    public function setCrtUser(?string $crtUser): self
    {
        $this->crtUser = $crtUser;

        return $this;
    }

    public function getCrtProcess(): ?string
    {
        return $this->crtProcess;
    }

    public function setCrtProcess(?string $crtProcess): self
    {
        $this->crtProcess = $crtProcess;

        return $this;
    }

    public function getCrtDate(): ?\DateTimeInterface
    {
        return $this->crtDate;
    }

    public function setCrtDate($crtDate): self
    {
        if ($crtDate instanceof \DateTime) {
            $this->crtDate = $crtDate;
        } else {
            $this->crtDate = \DateTime::createFromFormat('d/m/Y', $crtDate);
        }

        return $this;
    }

    public function getUpdUser(): ?string
    {
        return $this->updUser;
    }

    public function setUpdUser(?string $updUser): self
    {
        $this->updUser = $updUser;

        return $this;
    }

    public function getUpdProcess(): ?string
    {
        return $this->updProcess;
    }

    public function setUpdProcess(?string $updProcess): self
    {
        $this->updProcess = $updProcess;

        return $this;
    }

    public function getUpdDate(): ?\DateTimeInterface
    {
        return $this->updDate;
    }

    public function setUpdDate($updDate): self
    {
        if ($updDate instanceof \DateTime) {
            $this->updDate = $updDate;
        } elseif (!$updDate) {
            $this->date = null;
        } else {
            $this->updDate = \DateTime::createFromFormat('d/m/Y', $updDate);
        }

        return $this;
    }
}
