<?php

namespace App\Entity;

use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HolidaysRepository;

/**
 * @ORM\Entity(repositoryClass=HolidaysRepository::class)
 */
class Holidays implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    function jsonSerialize()
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "date" => $this->date,
            "public" => $this->public,
        );
    }
}
