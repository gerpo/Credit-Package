<?php

namespace Gerpo\DmsCredits\Events;

use Spatie\EventProjector\ShouldBeStored;

class CreditsAdded implements ShouldBeStored
{
    /** @var int */
    public $amount;

    /** @var string */
    public $message;

    public function __construct(int $amount, string $message = null)
    {
        $this->amount = $amount;
        $this->message = $message ?? 'DmsCredits::account.credits_added';
    }
}
