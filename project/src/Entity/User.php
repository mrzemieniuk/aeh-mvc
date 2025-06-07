<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PHONE', fields: ['phone'])]
#[UniqueEntity(fields: ['phone'], message: 'Ten numer telefonu jest już używany')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Numer telefonu jest wymagany')]
    #[Assert\Length(
        min: 9,
        max: 20,
        minMessage: 'Numer telefonu musi mieć co najmniej {{ limit }} znaków',
        maxMessage: 'Numer telefonu nie może mieć więcej niż {{ limit }} znaków'
    )]
    #[Assert\Regex(
        pattern: '/^\+?[0-9]+$/',
        message: 'Numer telefonu może zawierać tylko cyfry i opcjonalnie znak "+" na początku'
    )]
    private ?string $phone = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email(message: 'Podany adres email {{ value }} jest nieprawidłowy')]
    private ?string $email = null;

    /**
     * @var Collection<int, UserReport>
     */
    #[ORM\OneToMany(targetEntity: UserReport::class, mappedBy: 'createdBy')]
    private Collection $userReports;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Imię jest wymagane')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Imię musi mieć co najmniej {{ limit }} znaki',
        maxMessage: 'Imię nie może mieć więcej niż {{ limit }} znaków'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Nazwisko jest wymagane')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Nazwisko musi mieć co najmniej {{ limit }} znaki',
        maxMessage: 'Nazwisko nie może mieć więcej niż {{ limit }} znaków'
    )]
    private ?string $surname = null;

    public function __construct()
    {
        $this->userReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->phone;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, UserReport>
     */
    public function getUserReports(): Collection
    {
        return $this->userReports;
    }

    public function addUserReport(UserReport $userReport): static
    {
        if (!$this->userReports->contains($userReport)) {
            $this->userReports->add($userReport);
            $userReport->setCreatedBy($this);
        }

        return $this;
    }

    public function removeUserReport(UserReport $userReport): static
    {
        if ($this->userReports->removeElement($userReport)) {
            // set the owning side to null (unless already changed)
            if ($userReport->getCreatedBy() === $this) {
                $userReport->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }
}
