<?php

use Gerpo\DmsCredits\Models\CreditAccount;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasCreditAccount
{
    public function creditAccount(): MorphMany
    {
        return $this->morphMany(CreditAccount::class, 'owner');
    }
}

