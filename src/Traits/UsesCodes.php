<?php


namespace Gerpo\DmsCredits\Traits;


use Gerpo\DmsCredits\Aggregates\AccountAggregate;
use Gerpo\DmsCredits\Models\Code;

trait UsesCodes
{
    public function createdCodes()
    {
        return $this->hasMany(Code::class, 'created_by');
    }

    public function redeemCode(Code $code): void
    {
        if ($this->usedCodes()->save($code)) {
            AccountAggregate::retrieve($this->creditAccount->uuid)
                ->addCredits($code->value, 'DmsCredits::code.redeem_message')
                ->persist();
        }
    }

    public function usedCodes()
    {
        return $this->hasMany(Code::class, 'used_by');
    }
}