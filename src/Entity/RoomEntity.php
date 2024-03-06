<?php

namespace App\Entity;

use App\Repository\RoomEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomEntityRepository::class)]
class RoomEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $bathroom = null;

    #[ORM\Column]
    private ?int $size = null;

    #[ORM\Column]
    private ?int $persons = null;

    #[ORM\Column]
    private ?bool $balcony = null;

    #[ORM\Column]
    private ?bool $fridge = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isBathroom(): ?bool
    {
        return $this->bathroom;
    }

    public function setBathroom(bool $bathroom): static
    {
        $this->bathroom = $bathroom;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getPersons(): ?int
    {
        return $this->persons;
    }

    public function setPersons(int $persons): static
    {
        $this->persons = $persons;

        return $this;
    }

    public function isBalcony(): ?bool
    {
        return $this->balcony;
    }

    public function setBalcony(bool $balcony): static
    {
        $this->balcony = $balcony;

        return $this;
    }

    public function isFridge(): ?bool
    {
        return $this->fridge;
    }

    public function setFridge(bool $fridge): static
    {
        $this->fridge = $fridge;

        return $this;
    }
}
