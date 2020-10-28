<?php

namespace Gerpo\DmsCredits\Exceptions;

use Exception;

class CouldNotSubtractCredits extends Exception
{
    public static function notEnoughCredits(int $amount)
    {
        $min = config('dmscredit.minimum_balance', 0);

        return new static("{$amount} can not be subtracted from CreditAccount. Min account balance is {$min}.");
    }
}
