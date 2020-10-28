<?php

namespace DmsCredits\Tests;

use Gerpo\DmsCredits\Aggregates\AccountAggregate;
use Gerpo\DmsCredits\Events\AccountCreated;
use Gerpo\DmsCredits\Events\AccountDisabled;
use Gerpo\DmsCredits\Events\AccountEnabled;
use Gerpo\DmsCredits\Projectors\AccountProjector;
use Ramsey\Uuid\Uuid;

class AccountProjectorTest extends TestCase
{
    /** @test */
    public function onAccountCreated_creates_model_entity_with_uuid(): void
    {
        $user = User::create();
        $uuid = (string) Uuid::uuid4();

        $attr = [
            'owner_id'   => $user->id,
            'owner_type' => User::class,
        ];

        (new AccountProjector())->onAccountCreated(new AccountCreated($attr), $uuid);

        $this->assertDatabaseHas('credit_accounts', ['uuid' => $uuid, 'owner_id' => $attr['owner_id']]);
    }

    /** @test */
    public function onAccountEnabled_enables_the_account(): void
    {
        $account = createAccount(['is_active' => false]);

        $this->assertFalse($account->is_active);

        (new AccountProjector())->onAccountEnabled(new AccountEnabled(), $account->uuid);
        //AccountAggregate::retrieve($account->uuid)->enableAccount()->persist();

        $this->assertTrue($account->fresh()->is_active);
    }

    /** @test */
    public function onAccountDisabled_disables_the_account(): void
    {
        $account = createAccount();

        $this->assertTrue($account->is_active);

        (new AccountProjector())->onAccountDisabled(new AccountDisabled(), $account->uuid);
        //AccountAggregate::retrieve($account->uuid)->disableAccount()->persist();

        $this->assertFalse($account->fresh()->is_active);
    }
}
