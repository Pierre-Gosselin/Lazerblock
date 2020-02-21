<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AvatarRepository")
 */
class Avatar
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message="Vous devez renseigner un titre l'avatar.")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Le titre de l'avatar doit faire au moins 3 caractères.",
     * )
     * @Groups({"users_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"users_read"})
     */
    private $picture;

    private $pictureFile;

    public function getPictureFile(){
        return $this->pictureFile;
    }

    public function setPictureFile( $file ){
        $this->pictureFile = $file;
        return $this;
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

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
