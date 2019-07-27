<?php


namespace Gerpo\DmsCredits\Events;


use Spatie\EventProjector\ShouldBeStored;

class CreditsTransferred implements ShouldBeStored
{
    /** @var string */
    public $targetUuid;

    /** @var int */
    public $amount;

    /** @var string */
    public $message;

    public function __construct(string $targetUuid, int $amount, string $message = null)
    {
        $this->targetUuid = $targetUuid;
        $this->amount = $amount;
        $this->message = $message;
    }
}