<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardGiftRepository")
 */
class CardGift
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
     * @ORM\Column(type="boolean")
     */
    private $used;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiredAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gift", inversedBy="cardGifts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gifts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Card", inversedBy="giftCards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cards;

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

    public function getUsed(): ?bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): self
    {
        $this->used = $used;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getGifts(): ?Gift
    {
        return $this->gifts;
    }

    public function setGifts(?Gift $gifts): self
    {
        $this->gifts = $gifts;

        return $this;
    }

    public function getCards(): ?Card
    {
        return $this->cards;
    }

    public function setCards(?Card $cards): self
    {
        $this->cards = $cards;

        return $this;
    }

}
