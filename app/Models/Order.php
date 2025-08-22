<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'orders_id';

    protected $fillable = [
        'users_id',
        'courier_id',
        'status',
        'payment_method',
        'delivery_method',
        'is_paid',
        'payment_time',
        'delivery_fee',
        'service_fee',
        'cutlery_fee',
        'discount',
        'subtotal_net',
        'subtotal_sum',
        'total_tax',
        'total_price',
        'rating_star',
        'rating_comment',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'payment_time' => 'datetime',
        'delivery_fee' => 'float',
        'service_fee' => 'float',
        'cutlery_fee' => 'float',
        'discount' => 'float',
        'subtotal_net' => 'float',
        'subtotal_sum' => 'float',
        'total_tax' => 'float',
        'total_price' => 'float',
        'rating_star' => 'integer',
    ];

    // Kapcsolatok
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function items()
{
    return $this->hasMany(OrderItem::class, 'orders_id');
}

public function booking()
{
    return $this->hasOne(Booking::class, 'orders_id');
}
}
