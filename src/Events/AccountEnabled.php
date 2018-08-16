<?php


namespace Gerpo\DmsCredits\Events;


use Spatie\EventProjector\ShouldBeStored;

class AccountEnabled implements ShouldBeStored
{
    /** @var string */
    public $accountUuid;

    public function __construct(string $accountUuid)
    {
        $this->accountUuid = $accountUuid;
    }
}