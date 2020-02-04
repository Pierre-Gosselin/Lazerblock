<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardGift", mappedBy="cards")
     */
    private $giftCards;

    public function __construct()
    {
        $this->giftCards = new ArrayCollection();
    }

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

    /**
     * @return Collection|CardGift[]
     */
    public function getGiftCards(): Collection
    {
        return $this->giftCards;
    }

    public function addGiftCard(CardGift $giftCard): self
    {
        if (!$this->giftCards->contains($giftCard)) {
            $this->giftCards[] = $giftCard;
            $giftCard->setCards($this);
        }

        return $this;
    }

    public function removeGiftCard(CardGift $giftCard): self
    {
        if ($this->giftCards->contains($giftCard)) {
            $this->giftCards->removeElement($giftCard);
            // set the owning side to null (unless already changed)
            if ($giftCard->getCards() === $this) {
                $giftCard->setCards(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->serial;
    }
}
