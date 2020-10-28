<?php

namespace Gerpo\DmsCredits\Traits;

use Gerpo\DmsCredits\Aggregates\AccountAggregate;
use Gerpo\DmsCredits\Models\CreditAccount;
use Ramsey\Uuid\Uuid;

trait HasCreditAccount
{
    protected static $creditAccountClass = CreditAccount::class;
    protected static $morphFieldName = 'owner';

    public function creditAccount()
    {
        $account = $this->morphOne(static::$creditAccountClass, static::$morphFieldName);

        if ($account->get()->isEmpty()) {
            $newUuid = (string) Uuid::uuid4();
            AccountAggregate::retrieve($newUuid)
                ->createAccount([
                    'owner_id'   => $this->id,
                    'owner_type' => __CLASS__,
                ])
                ->persist();
        }

        return $this->morphOne(static::$creditAccountClass, static::$morphFieldName);
    }
}
