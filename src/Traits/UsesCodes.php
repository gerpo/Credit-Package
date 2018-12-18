<?php


namespace Gerpo\DmsCredits\Traits;


use Gerpo\DmsCredits\Models\Code;

trait UsesCodes
{
    public function usedCodes()
    {
        return $this->hasMany(Code::class, 'used_by');
    }

    public function createdCodes()
    {
        return $this->hasMany(Code::class, 'created_by');
    }

    public function redeemCode(Code $code): void
    {
        if($this->usedCodes()->save($code))
        {
            $this->creditAccount->addCredits($code->value, 'DmsCredits::code.redeem_message');
        }
    }
}