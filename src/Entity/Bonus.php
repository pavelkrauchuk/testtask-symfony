<?php

namespace App\Entity;

use App\Repository\BonusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BonusRepository::class)]
class Bonus extends Prize
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id; /** @phpstan-ignore-line */

    #[ORM\Column(type: 'integer')]
    private int $amount;

    #[ORM\Column(type: 'boolean')]
    private bool $isAdmissed;

    protected string $type = 'bonus';

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getIsAdmissed(): ?bool
    {
        return $this->isAdmissed;
    }

    public function setIsAdmissed(bool $isAdmissed): self
    {
        $this->isAdmissed = $isAdmissed;

        return $this;
    }

    public function setUser(?User $user): self
    {
        parent::setUser($user);

        return $this;
    }
}
