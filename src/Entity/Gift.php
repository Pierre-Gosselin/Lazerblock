<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GiftRepository")
 */
class Gift
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Vous devez renseigner un titre pour le cadeau.")
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Le titre doit faire au moins 5 caractères.",
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(
     *      value="199",
     *      message = "Le prix doit faire minimun 200 crédits.",
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardGift", mappedBy="gifts", orphanRemoval=true)
     */
    private $cardGifts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @Assert\Expression(
     *      "this.getPicture() or this.getPictureFile()",
     *      message="Vous devez renseigner une image pour le cadeau"
     * )
     */
    private $pictureFile;

    const CATEGORY = ["Friandises", "Costumes"];

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Friandises', 'Costumes')")
     * @Assert\Choice(choices=Gift::CATEGORY, message="La catégorie doit être Friandises ou Costumes")
     */
    private $category;

    public function getPictureFile(){
        return $this->pictureFile;
    }

    public function setPictureFile( $file ){
        $this->pictureFile = $file;
        return $this;
    }

    public function __construct()
    {
        $this->cardGifts = new ArrayCollection();
    }

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|CardGift[]
     */
    public function getCardGifts(): Collection
    {
        return $this->cardGifts;
    }

    public function addCardGift(CardGift $cardGift): self
    {
        if (!$this->cardGifts->contains($cardGift)) {
            $this->cardGifts[] = $cardGift;
            $cardGift->setGifts($this);
        }

        return $this;
    }

    public function removeCardGift(CardGift $cardGift): self
    {
        if ($this->cardGifts->contains($cardGift)) {
            $this->cardGifts->removeElement($cardGift);
            // set the owning side to null (unless already changed)
            if ($cardGift->getGifts() === $this) {
                $cardGift->setGifts(null);
            }
        }

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }
    
}
