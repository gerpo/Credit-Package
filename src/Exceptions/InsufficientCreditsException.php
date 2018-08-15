<?php

namespace Gerpo\DmsCredits\Exceptions;

use Exception;
use Gerpo\DmsCredits\Models\CreditAccount;

class InsufficientCreditsException extends Exception
{
    public static function subtraction(CreditAccount $account, int $amount)
    {
        $min = config('dmscredit.minimum_balance');
        return new static("{$amount} can not be subtracted from CreditAccount with id: {$account->id}. Current balance {$account->balance}. Min amount is {$min}.");
    }
}