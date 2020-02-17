<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "GET", "POST"
 *  },
 *  itemOperations={"GET", "PUT"},
 *  normalizationContext={
 *      "groups"={"bookings_read"}
 *  },
 *  subresourceOperations={
 *      "api_users_bookings_get_subresource"={
 *          "normalization_context"={
 *              "groups"={"bookings_subresource"}
 *          }
 *      },
 *  },
 *  attributes={
 *      "pagination_enabled"=true,
 *      "pagination_items_per_page"=5,
 *  }
 * )
 * @ApiFilter(SearchFilter::class)
 * @ApiFilter(OrderFilter::class, properties={"score"})
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"bookings_read", "bookings_subresource"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"bookings_subresource"})
     */
    private $reservationAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"bookings_read"})
     */
    private $score;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"bookings_read"})
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $serial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @Groups({"bookings_read"})
     */
    private $user;

    /**
     * @ORM\Column(type="time")
     * @Groups({"bookings_subresource"})
     */
    private $timeSlot;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservationAt(): ?\DateTimeInterface
    {
        return $this->reservationAt;
    }

    public function setReservationAt(\DateTimeInterface $reservationAt): self
    {
        $this->reservationAt = $reservationAt;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

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

    public function getTimeSlot(): ?\DateTimeInterface
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(\DateTimeInterface $timeSlot): self
    {
        $this->timeSlot = $timeSlot;

        return $this;
    }

}
