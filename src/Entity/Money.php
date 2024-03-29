<?php

namespace App\Entity;

use App\Repository\MoneyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoneyRepository::class)]
class Money extends Prize implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id; /** @phpstan-ignore-line */

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'boolean')]
    private bool $isConverted;

    #[ORM\Column(type: 'boolean')]
    private bool $isTransferred;

    protected string $type = 'money';

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getIsConverted(): bool
    {
        return $this->isConverted;
    }

    public function setIsConverted(bool $isConverted): self
    {
        $this->isConverted = $isConverted;

        return $this;
    }

    public function getIsTransferred(): bool
    {
        return $this->isTransferred;
    }

    public function setIsTransferred(bool $isTransferred): self
    {
        $this->isTransferred = $isTransferred;

        return $this;
    }

    public function setUser(?User $user): self
    {
        parent::setUser($user);

        return $this;
    }

    /**
     * @return array{id: int, amount: float, isConverted: bool, isTransferred: bool, type: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'isConverted' => $this->isConverted,
            'isTransferred' => $this->isTransferred,
            'type' => $this->type,
        ];
    }
}
