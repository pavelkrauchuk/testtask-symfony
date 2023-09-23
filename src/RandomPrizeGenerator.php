<?php

namespace App;

use App\Entity\AvailableThing;
use App\Entity\Parameters;
use App\Entity\Prize;
use App\Repository\AvailableThingRepository;
use Doctrine\ORM\EntityManagerInterface;

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
        $maxMoney = $entityManager->getRepository(Parameters::class)->findByName('max_money_for_prize');
        $availableMoney = $entityManager->getRepository(Parameters::class)->findByName('available_money');

        if (!$maxMoney || !$availableMoney) {
            throw new \LogicException('This parameter must not be empty');
        }

        $randomMoney = random_int(1, (int) $maxMoney->getValue());
        return min($randomMoney, (int) $availableMoney->getValue());
    }

    public static function getRandomBonusValue(EntityManagerInterface $entityManager): int
    {
        $maxBonus = $entityManager->getRepository(Parameters::class)->findByName('max_bonus_for_prize');

        if (!$maxBonus) {
            throw new \LogicException('This parameter must not be empty');
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
