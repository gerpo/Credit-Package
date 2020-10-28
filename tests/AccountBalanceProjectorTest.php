<?php

namespace DmsCredits\Tests;

use Gerpo\DmsCredits\Aggregates\AccountAggregate;
use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsReceived;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Gerpo\DmsCredits\Events\CreditsTransferred;
use Gerpo\DmsCredits\Projectors\AccountBalanceProjector;
use Spatie\EventProjector\Models\StoredEvent;

class AccountBalanceProjectorTest extends TestCase
{
    /** @test */
    public function onCreditsAdded_adds_correct_amount_to_account_balance(): void
    {
        $account = createAccount();

        $this->assertEquals(0, $account->balance);

        (new AccountBalanceProjector())->onCreditsAdded(new CreditsAdded(200), $account->uuid);

        $this->assertEquals(200, $account->fresh()->balance);
    }

    /** @test */
    public function onCreditsSubtracted_subtracts_correct_amount_from_account_balance(): void
    {
        $account = createAccount(['balance' => 200]);

        $this->assertEquals(200, $account->balance);

        (new AccountBalanceProjector())->onCreditsSubtracted(new CreditsSubtracted(200), $account->uuid);

        $this->assertEquals(0, $account->fresh()->balance);
    }

    /** @test */
    public function onCreditsAdded_sets_correct_message(): void
    {
        $account = createAccount();
        $message = 'This is a generic test message.';

        AccountAggregate::retrieve($account->uuid)->addCredits(200, $message)->persist();

        $event = StoredEvent::where('event_class', CreditsAdded::class)->first();

        $this->assertEquals($message, $event->event_properties['message']);
    }

    /** @test */
    public function onCreditsSubtracted_sets_correct_message(): void
    {
        $account = createAccount();
        $account->addCredits(200);
        $message = 'This is a generic test message.';

        AccountAggregate::retrieve($account->uuid)->subtractCredits(200, $message)->persist();

        $event = StoredEvent::where('event_class', CreditsSubtracted::class)->first();

        $this->assertEquals($message, $event->event_properties['message']);
    }

    /** @test */
    public function onCreditsTransferred_subtracts_right_amount_on_source(): void
    {
        $source = createAccount();
        $source->addCredits(200);

        $target = createAccount();

        $this->assertEquals(200, $source->fresh()->balance);

        (new AccountBalanceProjector())->onCreditsTransferred(new CreditsTransferred($target->uuid, 200, 'referenceUuid'), $source->uuid);

        $this->assertEquals(0, $source->fresh()->balance);
    }

    /** @test */
    public function onCreditsReceived_adds_right_amount_to_target(): void
    {
        $source = createAccount();
        $source->addCredits(200);

        $target = createAccount();

        $this->assertEquals(0, $target->balance);

        (new AccountBalanceProjector())->onCreditsReceived(new CreditsReceived($source->uuid, 200, 'referenceUuid'), $target->uuid);

        $this->assertEquals(200, $target->fresh()->balance);
    }
}
