<?php


namespace Gerpo\DmsCredits\Events;


use Spatie\EventProjector\ShouldBeStored;

class AccountDisabled implements ShouldBeStored
{
    /** @var string */
    public $accountUuid;

    public function __construct(string $accountUuid)
    {
        $this->accountUuid = $accountUuid;
    }
}