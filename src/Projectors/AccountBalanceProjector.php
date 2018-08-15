<?php

namespace Gerpo\DmsCredits\Projectors;

use Gerpo\DmsCredits\Events\AccountCreated;
use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Gerpo\DmsCredits\Exceptions\InsufficientCreditsException;
use Gerpo\DmsCredits\Models\CreditAccount;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class AccountBalanceProjector implements Projector
{
    use ProjectsEvents;

    public $handlesEvents = [
        CreditsAdded::class => 'onCreditsAdded',
        CreditsSubtracted::class => 'onCreditsSubtracted',
    ];

    /**
     * @param AccountCreated $event
     */
    public function onAccountCreated(AccountCreated $event): void
    {
        CreditAccount::create($event->accountAttributes);
    }

    /**
     * @param CreditsAdded $event
     */
    public function onCreditsAdded(CreditsAdded $event): void
    {
        $account = CreditAccount::find($event->accountId);

        $account->balance += $event->amount;

        $account->save();
    }

    /**
     * @param CreditsAdded $event
     * @throws InsufficientCreditsException
     */
    public function onCreditsSubtracted(CreditsAdded $event): void
    {
        $account = CreditAccount::find($event->accountId);

        $account->balance -= $event->amount;

        if ($account->balance < config('dmscredit.minimum-balance')) {
            throw InsufficientCreditsException::subtraction($account, $event->amount);
        }

        $account->save();
    }
}