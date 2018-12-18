<?php


namespace Gerpo\DmsCredits\Events;


use Spatie\EventProjector\ShouldBeStored;

class AccountCreated implements ShouldBeStored
{
    /** @var array */
    public $accountAttributes;

    /** @var null|string */
    public $message;

    public function __construct(array $accountAttributes, $message = null)
    {
        $this->accountAttributes = $accountAttributes;
        $this->message = $message ?? 'DmsCredits::account.account_created';
    }
}