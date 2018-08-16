<?php


namespace DmsCredits\Tests;


use Gerpo\DmsCredits\Events\AccountCreated;
use Gerpo\DmsCredits\Events\AccountDisabled;
use Gerpo\DmsCredits\Events\AccountEnabled;
use Ramsey\Uuid\Uuid;

class AccountProjectorTest extends TestCase
{
    /** @test */
    public function AccountCreated_creates_model_entity_with_uuid(): void
    {
        $user = User::create();

        $attr = [
            'uuid' => (string)Uuid::uuid4(),
            'owner_id' => $user->id,
            'owner_type' => User::class
        ];

        event(new AccountCreated($attr));

        $this->assertDatabaseHas('credit_accounts', ['uuid' => $attr['uuid']]);
    }

    /** @test */
    public function AccountEnabled_enables_the_account(): void
    {
        $account = createAccount(['is_active' => false]);

        $this->assertFalse($account->is_active);

        event(new AccountEnabled($account->uuid));

        $this->assertTrue($account->fresh()->is_active);
    }

    /** @test */
    public function AccountDisabled_disables_the_account(): void
    {
        $account = createAccount();

        $this->assertTrue($account->is_active);

        event(new AccountDisabled($account->uuid));

        $this->assertFalse($account->fresh()->is_active);
    }
}