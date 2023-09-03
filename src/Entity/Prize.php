<?php

namespace App\Entity;

use App\Repository\PrizeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrizeRepository::class)]
#[ORM\Table(name: 'prizes')]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
class Prize
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id; /** @phpstan-ignore-line */

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'receivedPrizes')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    protected string $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return array<string>
     */
    public static function getAvailableTypes(EntityManagerInterface $entityManager): array
    {
        $availableTypes[] = 'bonus';

        $availableMoney = $entityManager->getRepository(Parameters::class)->findOneBy(array(
            'paramName' => 'available_money'
        ));

        if ($availableMoney->getValue() > 0) {
            $availableTypes[] = 'money';
        }

        $count = $entityManager->getRepository(AvailableThing::class)->count(array());
        if ($count > 0) {
            $availableTypes[] = 'thing';
        }

        return $availableTypes;
    }
}
