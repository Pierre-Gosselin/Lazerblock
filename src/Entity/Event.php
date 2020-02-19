<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=90)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startEvent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endEvent;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $multiplicator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStartEvent(): ?\DateTimeInterface
    {
        return $this->startEvent;
    }

    public function setStartEvent(\DateTimeInterface $startEvent): self
    {
        $this->startEvent = $startEvent;

        return $this;
    }

    public function getEndEvent(): ?\DateTimeInterface
    {
        return $this->endEvent;
    }

    public function setEndEvent(\DateTimeInterface $endEvent): self
    {
        $this->endEvent = $endEvent;

        return $this;
    }

    public function getMultiplicator(): ?float
    {
        return $this->multiplicator;
    }

    public function setMultiplicator(?float $multiplicator): self
    {
        $this->multiplicator = $multiplicator;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}