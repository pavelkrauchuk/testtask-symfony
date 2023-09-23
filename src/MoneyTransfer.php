<?php

namespace App;

use App\Entity\Money;

class MoneyTransfer
{
    private static string $url = 'http://test/api.php';

    /**
     * @param Money|array<int, array{
     *     id: int,
     *     amount: float,
     *     isConverted: bool,
     *     isTransferred: bool,
     *     type: string
     * }> $money
     * @return void
     */
    public static function transfer(Money|array $money): void
    {
        $dataEncoded = json_encode($money);
        $curl = curl_init(self::$url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataEncoded);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($dataEncoded),
        ]);

        $result = curl_exec($curl);
    }
}
