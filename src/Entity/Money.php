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
    private $id;

    #[ORM\Column(type: 'float')]
    private $amount;

    #[ORM\Column(type: 'boolean')]
    private $isConverted;

    #[ORM\Column(type: 'boolean')]
    private $isTransferred;

    protected $type = 'money';

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getIsConverted(): ?bool
    {
        return $this->isConverted;
    }

    public function setIsConverted(bool $isConverted): self
    {
        $this->isConverted = $isConverted;

        return $this;
    }

    public function getIsTransferred(): ?bool
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
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'amount' => $this->amount,
            'isConverted' => $this->isConverted,
            'isTransferred' => $this->isTransferred,
            'type' => $this->type,
        );
    }
}
