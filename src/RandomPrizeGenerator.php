<?php

namespace App;

use App\Entity\Prize;

class RandomPrizeGenerator
{
    public static function generate(array $availableTypes) : Prize
    {
        $randomInt = random_int(0, count($availableTypes) - 1);
        $type = $availableTypes[$randomInt];
        $class = '\\App\\Entity\\' . ucfirst($type);

        return new $class();
    }
}