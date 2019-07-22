<?php

namespace Gerpo\DmsCredits\Projectors;

use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Gerpo\DmsCredits\Events\CreditsTransferred;
use Gerpo\DmsCredits\Models\CreditAccount;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class AccountBalanceProjector implements Projector
{
    use ProjectsEvents;

    public $handlesEvents = [
        CreditsAdded::class => 'onCreditsAdded',
        CreditsSubtracted::class => 'onCreditsSubtracted',
        CreditsTransferred::class => 'onCreditsTransferred',
    ];

    public function onCreditsAdded(CreditsAdded $event): void
    {
        $account = CreditAccount::uuid($event->accountUuid);

        $account->balance += $event->amount;
        $account->save();
    }

    public function onCreditsSubtracted(CreditsSubtracted $event): void
    {
        $account = CreditAccount::uuid($event->accountUuid);

        $account->balance -= $event->amount;
        $account->save();
    }

    public function onCreditsTransferred(CreditsTransferred $event): void
    {
        $source = CreditAccount::uuid($event->sourceUuid);
        $target = CreditAccount::uuid($event->targetUuid);

        $source->balance -= $event->amount;
        $target->balance += $event->amount;

        $source->save();
        $target->save();
    }
}