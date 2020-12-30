<?php

namespace App\Entity;

use JsonSerializable;
use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagesRepository::class)
 */
class Images implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $photoId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageEarthDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rover;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $camera;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhotoId(): ?int
    {
        return $this->photoId;
    }

    public function setPhotoId(int $photoId): self
    {
        $this->photoId = $photoId;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getImageEarthDate(): ?string
    {
        return $this->imageEarthDate;
    }

    public function setImageEarthDate(string $imageEarthDate): self
    {
        $this->imageEarthDate = $imageEarthDate;

        return $this;
    }

    public function getRover(): ?string
    {
        return $this->rover;
    }

    public function setRover(string $rover): self
    {
        $this->rover = $rover;

        return $this;
    }

    public function getCamera(): ?string
    {
        return $this->camera;
    }

    public function setCamera(string $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    function jsonSerialize()
    {
        return array(
            "id" => $this->id,
            "photoId" => $this->photoId,
            "imageUrl" => $this->imageUrl,
            "imageEarthDate" => $this->imageEarthDate,
            "rover" => $this->rover,
            "camera" => $this->camera,
        );
    }
}
