<?php

namespace App;

use App\Entity\AvailableThing;
use App\Entity\Parameters;
use App\Entity\Prize;
use App\Repository\AvailableThingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;

class RandomPrizeGenerator
{
    /**
     * @psalm-suppress MoreSpecificReturnType
     * @param array<string> $availableTypes
     * @return Prize
     * @throws \Exception
     */
    public static function generate(array $availableTypes): Prize
    {
        $randomInt = random_int(0, count($availableTypes) - 1);
        $type = $availableTypes[$randomInt];
        $class = '\\App\\Entity\\' . ucfirst($type);

        /** @psalm-suppress LessSpecificReturnStatement */
        return new $class();
    }

    public static function getRandomMoneyValue(EntityManagerInterface $entityManager): int
    {
        $maxMoney = $entityManager->getRepository(Parameters::class)->findOneBy([
            'paramName' => 'max_money_for_prize'
        ]);

        $availableMoney = $entityManager->getRepository(Parameters::class)->findOneBy([
            'paramName' => 'available_money'
        ]);

        if (!$maxMoney || !$availableMoney) {
            throw new \LogicException();
        }

        $randomMoney = random_int(1, (int) $maxMoney->getValue());
        return min($randomMoney, (int) $availableMoney->getValue());
    }

    public static function getRandomBonusValue(EntityManagerInterface $entityManager): int
    {
        $maxBonus = $entityManager->getRepository(Parameters::class)->findOneBy([
            'paramName' => 'max_bonus_for_prize'
        ]);

        if (!$maxBonus) {
            throw new \LogicException();
        }

        return random_int(1, (int) $maxBonus->getValue());
    }

    public static function getRandomAvailableThing(EntityManagerInterface $entityManager): AvailableThing
    {
        /** @var AvailableThingRepository $availableThingRepository */
        $availableThingRepository = $entityManager->getRepository(AvailableThing::class);

        return $availableThingRepository->getRandomThing();
    }
}
