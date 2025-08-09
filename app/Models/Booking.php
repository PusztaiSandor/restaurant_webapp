<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'bookings_id';

    protected $fillable = [
        'users_id',
        'orders_id',
        'tables_id',
        'seats',
        'booking_time',
        'status',
    ];

    protected $casts = [
        'seats' => 'integer',
        'booking_time' => 'datetime',
    ];

    // Kapcsolatok
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'tables_id');
    }
}
