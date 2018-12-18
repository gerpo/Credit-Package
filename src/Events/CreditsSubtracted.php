<?php


namespace Gerpo\DmsCredits\Events;


use Spatie\EventProjector\ShouldBeStored;

class CreditsSubtracted implements ShouldBeStored
{
    /** @var string */
    public $accountUuid;

    /** @var int */
    public $amount;

    /** @var string */
    public $message;

    public function __construct(string $accountUuid, int $amount, string $message = null)
    {
        $this->accountUuid = $accountUuid;
        $this->amount = $amount;
        $this->message = $message;
    }
}