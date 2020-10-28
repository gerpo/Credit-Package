<?php

namespace Gerpo\DmsCredits\Projectors;

use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsReceived;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Gerpo\DmsCredits\Events\CreditsTransferred;
use Gerpo\DmsCredits\Models\CreditAccount;
use Spatie\EventProjector\Projectors\ProjectsEvents;
use Spatie\EventProjector\Projectors\QueuedProjector;

class AccountBalanceProjector implements QueuedProjector
{
    use ProjectsEvents;

    public $handlesEvents = [
        CreditsAdded::class       => 'onCreditsAdded',
        CreditsSubtracted::class  => 'onCreditsSubtracted',
        CreditsTransferred::class => 'onCreditsTransferred',
        CreditsReceived::class    => 'onCreditsReceived',
    ];

    public function onCreditsAdded(CreditsAdded $event, string $aggregateUuid): void
    {
        $account = CreditAccount::uuid($aggregateUuid);

        $account->balance += $event->amount;
        $account->save();
    }

    public function onCreditsSubtracted(CreditsSubtracted $event, string $aggregateUuid): void
    {
        $account = CreditAccount::uuid($aggregateUuid);

        $account->balance -= $event->amount;
        $account->save();
    }

    public function onCreditsTransferred(CreditsTransferred $event, string $aggregateUuid): void
    {
        $account = CreditAccount::uuid($aggregateUuid);

        $account->balance -= $event->amount;

        $account->save();
    }

    public function onCreditsReceived(CreditsReceived $event, string $aggregateUuid): void
    {
        $account = CreditAccount::uuid($aggregateUuid);

        $account->balance += $event->amount;
        $account->save();
    }
}
