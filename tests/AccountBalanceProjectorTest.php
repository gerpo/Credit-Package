<?php

namespace DmsCredits\Tests;

use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Gerpo\DmsCredits\Exceptions\InsufficientCreditsException;
use Gerpo\DmsCredits\Models\CreditAccount;

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
    public function CreditsSubtracted_throws_exception_if_balance_is_insufficient(): void
    {
        $account = createAccount();

        $this->assertEquals(0, $account->balance);
        $this->expectException(InsufficientCreditsException::class);

        event(new CreditsSubtracted($account->uuid, 200));

        $this->assertEquals(0, $account->fresh()->balance);
    }
}