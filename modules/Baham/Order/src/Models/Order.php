<?php

namespace Baham\Order\Models;

use Baham\Transaction\Models\Transaction;
use Baham\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_DE_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'user_id',
        'count',
        'number_remaining',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeNotFollowedByUser($query, $userId)
    {
        return $query->whereDoesntHave('transactions', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }

    public function scopeActiveOrder($query)
    {
        return $query->where('status', Order::STATUS_ACTIVE);
    }

    public function scopeNotUserOrder($query, $userId)
    {
        return $query->where('user_id', '!=', $userId);
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($order) {
            if ($order->number_remaining === 0) {
                $order->status = self::STATUS_DE_ACTIVE;
                $order->saveQuietly();            }
        });
    }
}
