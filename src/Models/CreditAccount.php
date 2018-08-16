<?php


namespace Gerpo\DmsCredits\Models;


use Gerpo\DmsCredits\Events\AccountCreated;
use Gerpo\DmsCredits\Events\AccountDisabled;
use Gerpo\DmsCredits\Events\AccountEnabled;
use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

class CreditAccount extends Model
{
    protected $guarded = [];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function createWithAttributes(array $attributes = []): CreditAccount
    {
        $attributes['uuid'] = (string)Uuid::uuid4();

        event(new AccountCreated($attributes));

        return static::uuid($attributes['uuid']);
    }

    public static function uuid(string $uuid): ?CreditAccount
    {
        return static::where('uuid', $uuid)->first();
    }

    public function addCredits(int $amount): void
    {
        event(new CreditsAdded($this->uuid, $amount));
    }

    public function subtractCredits(string $amount): void
    {
        event(new CreditsSubtracted($this->uuid, $amount));
    }

    public function enableAccount(): void
    {
        event(new AccountEnabled($this->uuid));
    }

    public function disableAccount(): void
    {
        event(new AccountDisabled($this->uuid));
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}