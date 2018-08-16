<?php


namespace Gerpo\DmsCredits\Events;


use Spatie\EventProjector\ShouldBeStored;

class CreditsAdded implements ShouldBeStored
{
    /** @var string */
    public $accountUuid;

    /** @var int */
    public $amount;

    public function __construct(string $accountUuid, int $amount)
    {
        $this->accountUuid = $accountUuid;
        $this->amount = $amount;
    }
}