<?php


namespace Gerpo\DmsCredits\Events;


use Spatie\EventProjector\ShouldBeStored;

class CreditsTransferred implements ShouldBeStored
{
    /** @var string */
    public $sourceUuid;

    /** @var string */
    public $targetUuid;

    /** @var int */
    public $amount;

    /** @var string */
    public $message;

    public function __construct(string $sourceUuid, string $targetUuid, int $amount, string $message = null)
    {
        $this->sourceUuid = $sourceUuid;
        $this->targetUuid = $targetUuid;
        $this->amount = $amount;
        $this->message = $message;
    }
}