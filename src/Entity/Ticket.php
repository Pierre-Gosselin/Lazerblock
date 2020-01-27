<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $used = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $buyAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $serial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Booking", mappedBy="ticket", cascade={"persist", "remove"})
     */
    private $booking;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsed(): ?bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): self
    {
        $this->used = $used;

        return $this;
    }

    public function getBuyAt(): ?\DateTimeInterface
    {
        return $this->buyAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setBuyAt(): self
    {
        $this->buyAt = new \Datetime();

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): self
    {
        $this->booking = $booking;

        // set (or unset) the owning side of the relation if necessary
        $newTicket = null === $booking ? null : $this;
        if ($booking->getTicket() !== $newTicket) {
            $booking->setTicket($newTicket);
        }

        return $this;
    }
}
