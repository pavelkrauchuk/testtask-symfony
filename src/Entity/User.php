<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id; /** @phpstan-ignore-line */

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $username;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'integer')]
    private int $bonusCount;

    /**
     * @var Collection<int, Prize>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Prize::class)]
    private Collection $receivedPrizes;

    public function __construct()
    {
        $this->receivedPrizes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
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
     * @param array<string> $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
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

    public function getBonusCount(): ?int
    {
        return $this->bonusCount;
    }

    public function setBonusCount(int $bonusCount): self
    {
        $this->bonusCount = $bonusCount;

        return $this;
    }

    /**
     * @return Collection<int, Prize>
     */
    public function getReceivedPrizes(): Collection
    {
        return $this->receivedPrizes;
    }

    public function addReceivedPrize(Prize $receivedPrize): self
    {
        if (!$this->receivedPrizes->contains($receivedPrize)) {
            $this->receivedPrizes[] = $receivedPrize;
            $receivedPrize->setUser($this);
        }

        return $this;
    }

    public function removeReceivedPrize(Prize $receivedPrize): self
    {
        if ($this->receivedPrizes->removeElement($receivedPrize)) {
            // set the owning side to null (unless already changed)
            if ($receivedPrize->getUser() === $this) {
                $receivedPrize->setUser(null);
            }
        }

        return $this;
    }
}
