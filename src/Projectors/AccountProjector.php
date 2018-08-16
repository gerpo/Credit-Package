<?php

namespace Gerpo\DmsCredits\Projectors;

use Gerpo\DmsCredits\Events\AccountCreated;
use Gerpo\DmsCredits\Events\AccountDisabled;
use Gerpo\DmsCredits\Events\AccountEnabled;
use Gerpo\DmsCredits\Models\CreditAccount;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class AccountProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        AccountCreated::class => 'onAccountCreated',
        AccountEnabled::class => 'onAccountEnabled',
        AccountDisabled::class => 'onAccountDisabled',
    ];

    /**
     * @param AccountCreated $event
     */
    public function onAccountCreated(AccountCreated $event): void
    {
        CreditAccount::create($event->accountAttributes);
    }

    public function onAccountEnabled(AccountEnabled $event): void
    {
        $account = CreditAccount::uuid($event->accountUuid);
        if ($account === null) {
            return;
        }

        $account->is_active = true;
        $account->save();
    }

    public function onAccountDisabled(AccountDisabled $event): void
    {
        $account = CreditAccount::uuid($event->accountUuid);
        if ($account === null) {
            return;
        }

        $account->is_active = false;
        $account->save();
    }
}