<?php


namespace Gerpo\DmsCredits\Models;


use Gerpo\DmsCredits\Events\AccountCreated;
use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Illuminate\Database\Eloquent\Model;

class CreditAccount extends Model
{
    protected $guarded = [];

    public static function createWithAttributes(array $attributes): CreditAccount
    {
        event(new AccountCreated($attributes));

        return static::find($attributes['id']);
    }

    public function addCredits(int $amount): void
    {
        event(new CreditsAdded($this->id, $amount));
    }

    public function subtractCredits(int $amount): void
    {
        event(new CreditsSubtracted($this->id, $amount));
    }

    public function disableAccount() :void
    {
        event();
    }

    public function owner()
    {
        return $this->morphTo();
    }
}