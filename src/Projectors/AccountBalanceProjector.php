<?php

namespace Gerpo\DmsCredits\Projectors;

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
     * @param CreditsAdded $event
     */
    public function onCreditsAdded(CreditsAdded $event): void
    {
        $account = CreditAccount::uuid($event->accountUuid);

        if ($account === null) {
            return;
        }

        $account->balance += $event->amount;
        $account->save();
    }

    /**
     * @param CreditsSubtracted $event
     */
    public function onCreditsSubtracted(CreditsSubtracted $event): void
    {
        $account = CreditAccount::uuid($event->accountUuid);

        if ($account === null) {
            return;
        }

        $account->balance -= $event->amount;
        $account->save();
    }
}