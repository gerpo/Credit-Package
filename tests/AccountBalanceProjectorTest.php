<?php

namespace DmsCredits\Tests;

use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Gerpo\DmsCredits\Events\CreditsTransferred;
use Spatie\EventProjector\Models\StoredEvent;

class AccountBalanceProjectorTest extends TestCase
{
    /** @test */
    public function CreditsAdded_add_correct_amount_to_account_balance(): void
    {
        $account = createAccount();

        $this->assertEquals(0, $account->balance);

        event(new CreditsAdded($account->uuid, 200));

        $this->assertEquals(200, $account->fresh()->balance);
    }

    /** @test */
    public function CreditsSubtracted_subtracts_correct_amount_from_account_balance(): void
    {
        $account = createAccount([
            'balance' => 200,
        ]);

        $this->assertEquals(200, $account->balance);

        event(new CreditsSubtracted($account->uuid, 200));

        $this->assertEquals(0, $account->fresh()->balance);
    }

    /** @test */
    public function CreditsAdded_sets_correct_message(): void
    {
        $account = createAccount();
        $message = 'This is a generic test message.';

        event(new CreditsAdded($account->uuid, 200, $message));

        $event = StoredEvent::where('event_class', CreditsAdded::class)->first();

        $this->assertEquals($message, $event->event_properties['message']);
    }

    /** @test */
    public function CreditsSubtracted_sets_correct_message(): void
    {
        $account = createAccount();
        $message = 'This is a generic test message.';

        event(new CreditsSubtracted($account->uuid, 200, $message));

        $event = StoredEvent::where('event_class', CreditsSubtracted::class)->first();

        $this->assertEquals($message, $event->event_properties['message']);
    }

    /** @test */
    public function CreditsTransferred_subtracts_right_amount_on_source(): void
    {
        $source = createAccount([
            'balance' => 200,
        ]);

        $target = createAccount();

        $this->assertEquals(200, $source->balance);

        event(new CreditsTransferred($source->uuid, $target->uuid, 200));

        $this->assertEquals(0, $source->fresh()->balance);
    }

    /** @test */
    public function CreditsTransferred_adds_right_amount_to_target(): void
    {
        $source = createAccount([
            'balance' => 200,
        ]);

        $target = createAccount();

        $this->assertEquals(0, $target->balance);

        event(new CreditsTransferred($source->uuid, $target->uuid, 200));

        $this->assertEquals(200, $target->fresh()->balance);
    }
}