<?php


namespace DmsCredits\Tests;


use Gerpo\DmsCredits\Events\AccountDisabled;
use Gerpo\DmsCredits\Events\AccountEnabled;
use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Illuminate\Support\Facades\Event;

class CreditAccountTest extends TestCase
{
    /** @test */
    public function addCredits_dispatches_correct_CreditsAdded_Event(): void
    {
        $account = User::create()->creditAccount;

        Event::fakeFor(function () use ($account) {
            $account->addCredits(200);

            Event::assertDispatched(CreditsAdded::class, function ($event) use ($account) {
                return $event->accountUuid === $account->uuid && $event->amount === 200;
            });
        });
    }

    /** @test */
    public function subtractCredits_dispatches_correct_CreditsSubtracted_Event(): void
    {
        $account = User::create()->creditAccount;

        Event::fakeFor(function () use ($account) {
            $account->subtractCredits(200);

            Event::assertDispatched(CreditsSubtracted::class, function ($event) use ($account) {
                return $event->accountUuid === $account->uuid && $event->amount === 200;
            });
        });
    }

    /** @test */
    public function enableAccount_dispatches_correct_AccountEnabled_Event(): void
    {
        $account = User::create()->creditAccount;

        Event::fakeFor(function () use ($account) {
            $account->enableAccount();

            Event::assertDispatched(AccountEnabled::class, function ($event) use ($account) {
                return $event->accountUuid === $account->uuid;
            });
        });
    }

    /** @test */
    public function disableAccount_dispatches_correct_AccountDisabled_Event(): void
    {
        $account = User::create()->creditAccount;

        Event::fakeFor(function () use ($account) {
            $account->disableAccount();

            Event::assertDispatched(AccountDisabled::class, function ($event) use ($account) {
                return $event->accountUuid === $account->uuid;
            });
        });
    }
}