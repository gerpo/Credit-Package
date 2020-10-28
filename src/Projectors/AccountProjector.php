<?php

namespace Gerpo\DmsCredits\Projectors;

use Gerpo\DmsCredits\Events\AccountCreated;
use Gerpo\DmsCredits\Events\AccountDisabled;
use Gerpo\DmsCredits\Events\AccountEnabled;
use Gerpo\DmsCredits\Models\CreditAccount;
use Spatie\EventProjector\Projectors\ProjectsEvents;
use Spatie\EventProjector\Projectors\QueuedProjector;

class AccountProjector implements QueuedProjector
{
    use ProjectsEvents;

    protected $handlesEvents = [
        AccountCreated::class  => 'onAccountCreated',
        AccountEnabled::class  => 'onAccountEnabled',
        AccountDisabled::class => 'onAccountDisabled',
    ];

    public function onStartingEventReplay(): void
    {
        CreditAccount::truncate();
    }

    public function onAccountCreated(AccountCreated $event, string $aggregateUuid): void
    {
        CreditAccount::create([
            'uuid'       => $aggregateUuid,
            'owner_id'   => $event->accountAttributes['owner_id'],
            'owner_type' => $event->accountAttributes['owner_type'],
        ]);
    }

    public function onAccountEnabled(AccountEnabled $event, string $aggregateUuid): void
    {
        $account = CreditAccount::uuid($aggregateUuid);

        $account->is_active = true;
        $account->save();
    }

    public function onAccountDisabled(AccountDisabled $event, string $aggregateUuid): void
    {
        $account = CreditAccount::uuid($aggregateUuid);

        $account->is_active = false;
        $account->save();
    }
}
