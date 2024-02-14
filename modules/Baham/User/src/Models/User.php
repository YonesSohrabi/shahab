<?php

namespace Baham\User\Models;

use Baham\Order\Models\Order;
use Baham\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;
    use Notifiable;


    protected $fillable = [
        'user_name',
        'coins'
    ];

    protected $hidden = ['remember_token'];


    public function ordersFollowed()
    {
        return $this->hasManyThrough(
            Order::class,
            Transaction::class,
            'user_id',
            'id',
            'id',
            'order_id'
        );
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }




}
