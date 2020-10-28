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
    public $referenceUuid;

    /** @var string */
    public $message;

    public function __construct(string $targetUuid, int $amount, string $referenceUuid, string $message = null)
    {
        $this->targetUuid = $targetUuid;
        $this->amount = $amount;
        $this->referenceUuid = $referenceUuid;
        $this->message = $message ?? 'DmsCredits::account.credits_transferred';
    }
}
