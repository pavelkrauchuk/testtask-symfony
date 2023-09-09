<?php

namespace App\Entity;

use App\Repository\ThingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThingRepository::class)]
class Thing extends Prize
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id; /** @phpstan-ignore-line */

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'boolean')]
    private bool $isShipped;

    protected string $type = 'thing';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsShipped(): ?bool
    {
        return $this->isShipped;
    }

    public function setIsShipped(bool $isShipped): self
    {
        $this->isShipped = $isShipped;

        return $this;
    }

    public function setUser(?User $user): self
    {
        parent::setUser($user);

        return $this;
    }
}
