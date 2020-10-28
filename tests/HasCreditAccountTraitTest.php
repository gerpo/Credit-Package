<?php

namespace DmsCredits\Tests;

class HasCreditAccountTraitTest extends TestCase
{
    /** @test */
    public function an_account_is_created_if_entity_has_none(): void
    {
        $user = User::create();

        $this->assertDatabaseMissing('credit_accounts', ['owner_id' => $user->id]);

        $this->assertNotEmpty($user->creditAccount);

        $this->assertDatabaseHas('credit_accounts', ['owner_id' => $user->id]);
    }
}
