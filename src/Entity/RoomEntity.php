<?php

namespace App\Entity;

use App\Repository\RoomEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomEntityRepository::class)]
#[ORM\HasLifecycleCallbacks]
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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column]
    private ?int $price_weekday = null;

    #[ORM\Column]
    private ?int $price_weekend = null;

    #[ORM\OneToMany(targetEntity: ReservationEntity::class, mappedBy: 'roomEntity', orphanRemoval: true)]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

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

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    #[ORM\PrePersist]
    public function setDateCreated(): void
    {
        $this->date_created = new \DateTime();
    }

    public function getPriceWeekday(): ?int
    {
        return $this->price_weekday;
    }

    public function setPriceWeekday(int $price_weekday): static
    {
        $this->price_weekday = $price_weekday;

        return $this;
    }

    public function getPriceWeekend(): ?int
    {
        return $this->price_weekend;
    }

    public function setPriceWeekend(int $price_weekend): static
    {
        $this->price_weekend = $price_weekend;

        return $this;
    }

    /**
     * @return Collection<int, ReservationEntity>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(ReservationEntity $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setRoomEntity($this);
        }

        return $this;
    }

    public function removeReservation(ReservationEntity $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getRoomEntity() === $this) {
                $reservation->setRoomEntity(null);
            }
        }

        return $this;
    }
}
