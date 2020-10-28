<?php

namespace Gerpo\DmsCredits\Exceptions;

use Exception;

class CouldNotTransferCredits extends Exception
{
    public static function notEnoughCredits(int $amount)
    {
        $min = config('dmscredit.minimum_balance', 0);

        return new static("{$amount} can not be subtracted from source account. Min account balance is {$min}.");
    }

    public static function targetDoesNotExist()
    {
        return new static('Credits can not be transferred. Target account does not exist.');
    }
}
