<?php

namespace App\Service;

use App\Entity\AvailableThing;
use App\Repository\AvailableThingRepository;
use App\Repository\ParametersRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\ORMException;

class RandomPrizeValueGenerator
{
    /** @var AvailableThingRepository $availableThings */
    private AvailableThingRepository $availableThings;

    /** @var ParametersRepository $parameters */
    private ParametersRepository $parameters;

    public function __construct(AvailableThingRepository $availableThings, ParametersRepository $parameters)
    {
        $this->availableThings = $availableThings;
        $this->parameters = $parameters;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getRandomMoneyValue(): int
    {
        $maxMoney = $this->parameters->findByName('max_money_for_prize');
        $availableMoney = $this->parameters->findByName('available_money');

        if (!$maxMoney || !$availableMoney) {
            throw new \LogicException('This parameter must not be empty');
        }

        $randomMoney = random_int(1, (int) $maxMoney->getValue());

        return min($randomMoney, (int) $availableMoney->getValue());
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getRandomBonusValue(): int
    {
        $maxBonus = $this->parameters->findByName('max_bonus_for_prize');

        if (!$maxBonus) {
            throw new \LogicException('This parameter must not be empty');
        }

        return random_int(1, (int) $maxBonus->getValue());
    }

    /**
     * @throws \ReflectionException
     * @throws Exception
     * @throws ORMException
     */
    public function getRandomAvailableThing(): AvailableThing
    {
        return $this->availableThings->getRandomThing();
    }
}
