<?php

namespace Gerpo\DmsCredits\Events;

use Spatie\EventProjector\ShouldBeStored;

class CreditsReceived implements ShouldBeStored
{
    /** @var string */
    public $sourceUuid;

    /** @var int */
    public $amount;

    /** @var string */
    public $referenceUuid;

    /** @var string */
    public $message;

    public function __construct(string $sourceUuid, int $amount, string $referenceUuid, string $message = null)
    {
        $this->sourceUuid = $sourceUuid;
        $this->amount = $amount;
        $this->referenceUuid = $referenceUuid;
        $this->message = $message ?? 'DmsCredits::account.credits_received';
    }
}
