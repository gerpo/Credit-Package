<?php


namespace Gerpo\DmsCredits\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class Code extends Model
{
    protected $guarded = [];
    protected $dates = [
        'created_at',
        'updated_at',
        'used_at'
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::updating(function ($code) {
            if ($code->used_by !== null) {
                $code->used_at = $code->freshTimestamp();
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function export(): void
    {
        $this->exported = true;
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->where('used_by', null);
    }

    public function scopeNotExported($query)
    {
        return $query->where('used_by', null)->where('exported', false);
    }
}