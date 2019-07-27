<?php


namespace Gerpo\DmsCredits\Models;


use Gerpo\DmsCredits\Aggregates\AccountAggregate;
use Gerpo\DmsCredits\Resources\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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

    public static function uuid(string $uuid): ?CreditAccount
    {
        return static::where('uuid', $uuid)->first();
    }

    public function addCredits(int $amount, string $message = null): void
    {
        AccountAggregate::retrieve($this->uuid)->addCredits($amount, $message)->persist();
    }

    public function subtractCredits(string $amount, string $message = null): void
    {
        AccountAggregate::retrieve($this->uuid)->subtractCredits($amount, $message)->persist();
    }

    public function enableAccount(): void
    {
        AccountAggregate::retrieve($this->uuid)->enableAccount()->persist();
    }

    public function disableAccount(): void
    {
        AccountAggregate::retrieve($this->uuid)->disableAccount()->persist();
    }

    public function transferCredits($targetUuid, $amount): void
    {
        AccountAggregate::retrieve($this->uuid)->transferCredits($targetUuid, $amount)->persist();
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTransactionsAttribute(): AnonymousResourceCollection
    {
        return Transaction::collection(StoredEvent::where('aggregate_uuid', $this->uuid)->get());
    }
}