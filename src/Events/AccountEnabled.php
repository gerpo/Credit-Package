<?php


namespace Gerpo\DmsCredits\Events;


use Spatie\EventProjector\ShouldBeStored;

class AccountEnabled implements ShouldBeStored
{
    /** @var string */
    public $message;

    public function __construct(string $message = null)
    {
        $this->message = $message ?? 'DmsCredits::account_enabled';
    }
}