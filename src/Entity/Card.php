<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Card
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $serial;

    /**
     * @ORM\Column(type="integer")
     */
    private $credits = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expireCreditsAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="card", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerial(): ?string
    {
        return $this->serial;
    }

    public function setSerial(string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(int $credits): self
    {
        $this->credits = $credits;

        return $this;
    }

    public function getExpireCreditsAt(): ?\DateTimeInterface
    {
        return $this->expireCreditsAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setExpireCreditsAt(): self
    {
        $this->expireCreditsAt = new \DateTime('6 months');

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
