<?php

namespace App\Entity;

use App\Repository\EmergencyAlertRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmergencyAlertRepository::class)]
class EmergencyAlert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Tytuł jest wymagany')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Tytuł musi mieć co najmniej {{ limit }} znaków',
        maxMessage: 'Tytuł nie może mieć więcej niż {{ limit }} znaków'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Opis jest wymagany')]
    #[Assert\Length(
        min: 10,
        minMessage: 'Opis musi mieć co najmniej {{ limit }} znaków'
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Poziom zagrożenia jest wymagany')]
    private ?string $alertLevel = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Szerokość geograficzna jest wymagana')]
    #[Assert\Range(
        min: -90,
        max: 90,
        notInRangeMessage: 'Szerokość geograficzna musi być między {{ min }} a {{ max }}'
    )]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Długość geograficzna jest wymagana')]
    #[Assert\Range(
        min: -180,
        max: 180,
        notInRangeMessage: 'Długość geograficzna musi być między {{ min }} a {{ max }}'
    )]
    private ?float $longitude = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Promień jest wymagany')]
    #[Assert\Positive(message: 'Promień musi być liczbą dodatnią')]
    #[Assert\LessThanOrEqual(
        value: 1000,
        message: 'Promień nie może być większy niż {{ compared_value }} km'
    )]
    private ?float $radiusKm = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Rodzaj zagrożenia jest wymagany')]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAlertLevel(): ?string
    {
        return $this->alertLevel;
    }

    public function setAlertLevel(string $alertLevel): static
    {
        $this->alertLevel = $alertLevel;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getRadiusKm(): ?float
    {
        return $this->radiusKm;
    }

    public function setRadiusKm(float $radiusKm): static
    {
        $this->radiusKm = $radiusKm;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
