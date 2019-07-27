<?php

namespace Gerpo\DmsCredits\Aggregates;

use Gerpo\DmsCredits\Events\AccountCreated;
use Gerpo\DmsCredits\Events\AccountDisabled;
use Gerpo\DmsCredits\Events\AccountEnabled;
use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Gerpo\DmsCredits\Events\CreditsTransferred;
use Gerpo\DmsCredits\Exceptions\CouldNotSubtractCredits;
use Gerpo\DmsCredits\Exceptions\CouldNotTransferCredits;
use Gerpo\DmsCredits\Models\CreditAccount;
use Illuminate\Foundation\Bus\Dispatchable;
use Spatie\EventProjector\AggregateRoot;

class AccountAggregate extends AggregateRoot
{
    private $balance = 0;
    private $is_active = 0;

    public function applyCreditsAdded(CreditsAdded $event): void
    {
        $this->balance += $event->amount;
    }

    public function applyCreditsSubtracted(CreditsSubtracted $event): void
    {
        $this->balance -= $event->amount;
    }

    public function applyAccountEnabled(AccountEnabled $event): void
    {
        $this->is_active = true;
    }

    public function applyAccountDisabled(AccountDisabled $event): void
    {
        $this->is_active = false;
    }

    public function createAccount(array $accountAttributes, $message = null): AccountAggregate
    {
        $this->recordThat(new AccountCreated($accountAttributes, $message));

        return $this;
    }

    public function enableAccount($message = null): AccountAggregate
    {
        $this->recordThat(new AccountEnabled($message));

        return $this;
    }

    public function disableAccount($message = null): AccountAggregate
    {
        $this->recordThat(new AccountDisabled($message));

        return $this;
    }

    public function addCredits(int $amount, $message = null): AccountAggregate
    {
        $this->recordThat(new CreditsAdded($amount, $message));

        return $this;
    }

    public function subtractCredits(int $amount, $message = null): AccountAggregate
    {
        if (!$this->hasSufficientFundsToSubtractAmount($amount)) {
            throw CouldNotSubtractCredits::notEnoughCredits($amount);
        }

        $this->recordThat(new CreditsSubtracted($amount, $message));

        return $this;
    }

    public function transferCredits(string $targetUuid, int $amount, $message = null): AccountAggregate
    {
        if (!$this->hasSufficientFundsToSubtractAmount($amount)) {
            throw CouldNotTransferCredits::notEnoughCredits($amount);
        }

        if (CreditAccount::uuid($targetUuid) === null) {
            throw CouldNotTransferCredits::targetDoesNotExist();
        }

        $this->recordThat(new CreditsTransferred($targetUuid, $amount, $message));

        return $this;
    }

    private function hasSufficientFundsToSubtractAmount(int $amount): bool
    {
        return ($this->balance - $amount) >= config('dmscredit.minimum_balance', 0);
    }
}