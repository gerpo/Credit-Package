<?php

namespace Gerpo\DmsCredits\Traits;

use Gerpo\DmsCredits\Models\CreditAccount;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasCreditAccount
{

    protected static $creditAccountClass = CreditAccount::class;
    protected static $morphFieldName = 'owner';

    public function creditAccount()
    {
        $account = $this->morphOne(static::$creditAccountClass, static::$morphFieldName);

        if ($account->get()->isEmpty()) {
            static::$creditAccountClass::createWithAttributes([
                'owner_id' => $this->id,
                'owner_type' => __CLASS__,
            ]);
        }

        return $this->morphOne(static::$creditAccountClass, static::$morphFieldName);
    }
}

