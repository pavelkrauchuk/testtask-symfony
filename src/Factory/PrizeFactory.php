<?php

namespace App\Factory;

use App\Entity\Prize;
use App\Repository\AvailableThingRepository;
use App\Repository\ParametersRepository;
use Doctrine\ORM\NonUniqueResultException;

class PrizeFactory
{
    /** @var ParametersRepository $parameters */
    private ParametersRepository $parameters;

    /** @var AvailableThingRepository $availableThings */
    private AvailableThingRepository $availableThings;
    public function __construct(ParametersRepository $parameters, AvailableThingRepository $availableThings)
    {
        $this->parameters = $parameters;
        $this->availableThings = $availableThings;
    }

    /**
     * @return string[]
     * @throws NonUniqueResultException
     */
    private function getAvailableTypes(): array
    {
        $availableTypes = ['bonus'];
        $availableMoney = $this->parameters->findByName('available_money');

        if ($availableMoney && $availableMoney->getValue() > 0) {
            $availableTypes[] = 'money';
        }

        if ($this->availableThings->count([]) > 0) {
            $availableTypes[] = 'thing';
        }

        return $availableTypes;
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     * @return Prize
     * @throws \Exception
     */
    public function create(): Prize
    {
        $availableTypes = $this->getAvailableTypes();

        $randomInt = random_int(0, count($availableTypes) - 1);
        $type = $availableTypes[$randomInt];
        $class = '\\App\\Entity\\' . ucfirst($type);

        /** @psalm-suppress LessSpecificReturnStatement */
        return new $class();
    }
}
