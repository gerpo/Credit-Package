<?php


namespace DmsCredits\Tests;


use Gerpo\DmsCredits\Events\AccountDisabled;
use Gerpo\DmsCredits\Events\AccountEnabled;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Gerpo\DmsCredits\Events\CreditsTransferred;
use Gerpo\DmsCredits\Exceptions\CouldNotSubtractCredits;
use Gerpo\DmsCredits\Exceptions\CouldNotTransferCredits;
use Illuminate\Support\Facades\Event;

class CreditAccountTest extends TestCase
{
    /** @test */
    public function addCredits_adds_correct_amount(): void
    {
        $account = createAccount();

        $this->assertEquals(0, $account->fresh()->balance);

        $account->addCredits(200);

        $this->assertEquals(200, $account->fresh()->balance);
    }

    /** @test */
    public function subtractCredits_subtracts_correct_amount(): void
    {
        $account = createAccount();
        $account->addCredits(200);

        $this->assertEquals(200, $account->fresh()->balance);

        $account->subtractCredits(200);

        $this->assertEquals(0, $account->fresh()->balance);
    }

    /** @test */
    public function subtractCredits_throws_exception_if_balance_is_insufficient(): void
    {
        $account = createAccount();

        $this->assertEquals(0, $account->balance);
        $this->expectException(CouldNotSubtractCredits::class);

        $account->subtractCredits(200);

        $this->assertEquals(0, $account->fresh()->balance);
    }

    /** @test */
    public function enableAccount_enables_account(): void
    {
        $account = createAccount(['is_active' => false]);

        $this->assertFalse($account->is_active);

        $account->enableAccount();

        $this->assertTrue($account->fresh()->is_active);
    }

    /** @test */
    public function disableAccount_disables_account(): void
    {
        $account = createAccount();

        $this->assertTrue($account->is_active);

        $account->disableAccount();

        $this->assertFalse($account->fresh()->is_active);
    }

    /** @test */
    public function transferCredits_subtracts_correct_amount_from_source(): void
    {
        $account = createAccount();
        $account->addCredits(200);

        $target = createAccount();

        $account->transferCredits($target->uuid, 200);

        $this->assertEquals(0, $account->fresh()->balance);
    }

    /** @test */
    public function transferCredits_adds_correct_amount_to_target(): void
    {
        $account = createAccount();
        $account->addCredits(200);

        $target = createAccount();

        $account->transferCredits($target->uuid, 200);

        $this->assertEquals(200, $target->fresh()->balance);
    }

    /** @test */
    public function transferCredits_throws_exception_if_balance_is_insufficient(): void
    {
        $account = createAccount();
        $target = createAccount();

        $this->expectException(CouldNotTransferCredits::class);
        $this->expectExceptionMessage(CouldNotTransferCredits::notEnoughCredits(200)->getMessage());

        $account->transferCredits($target->uuid, 200);

        $this->assertEquals(0, $account->fresh()->balance);
        $this->assertEquals(0, $target->fresh()->balance);
    }

    /** @test */
    public function transferCredits_throws_exception_if_target_account_does_not_exists(): void
    {
        $account = createAccount();
        $account->addCredits(200);

        $this->expectException(CouldNotTransferCredits::class);
        $this->expectExceptionMessage(CouldNotTransferCredits::targetDoesNotExist()->getMessage());

        $account->transferCredits('INVALID_UUID', 200);

        $this->assertEquals(0, $account->fresh()->balance);
    }
}