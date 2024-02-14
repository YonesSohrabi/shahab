<?php

namespace Baham\Transaction\Models;

use Baham\Order\Models\Order;
use Baham\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const TYPE_COST = 0;
    const TYPE_REWARD = 1;

    protected $fillable = [
        'user_id',
        'order_id',
        'type',
        'count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
