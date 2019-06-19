<?php


namespace Gerpo\DmsCredits\Models;


use Gerpo\DmsCredits\Events\AccountCreated;
use Gerpo\DmsCredits\Events\AccountDisabled;
use Gerpo\DmsCredits\Events\AccountEnabled;
use Gerpo\DmsCredits\Events\CreditsAdded;
use Gerpo\DmsCredits\Events\CreditsSubtracted;
use Gerpo\DmsCredits\Exceptions\InsufficientCreditsException;
use Gerpo\DmsCredits\Resources\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;
use Spatie\EventProjector\Models\StoredEvent;

class CreditAccount extends Model
{
    protected $guarded = [];
    protected $hidden = [
        'id',
        'owner_id',
        'owner_type'
    ];
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

    public function addCredits(int $amount, string $message = null): void
    {
        event(new CreditsAdded($this->uuid, $amount, $message));
    }

    /**
     * @param string $amount
     * @throws InsufficientCreditsException
     */
    public function subtractCredits(string $amount): void
    {

        if ($this->balance - $amount < config('DmsCredit.minimum_balance')) {
            throw InsufficientCreditsException::subtraction($this, $amount);
        }

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

    public function getTransactionsAttribute()
    {
        return Transaction::collection(StoredEvent::where('event_properties->accountAttributes->uuid',
            $this->uuid)->orWhere('event_properties->uuid', $this->accountUuid)->get());
    }
}