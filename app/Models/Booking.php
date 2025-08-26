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
        'table_code',
        'seats',
        'booking_time',
        'status',
    ];

    protected $casts = [
        'seats' => 'integer',
        'booking_time' => 'datetime',
    ];

    // Felhasználó kapcsolata
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    // Rendelés kapcsolata
    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }

    // Asztalok lekérdezése egyedi módon
    public function tables()
    {
        // Segédfüggvény
        $codes = explode(',', $this->table_code);

        return \App\Models\Table::whereIn('table_code', $codes)->get();
    }

    // Segédfüggvény az asztalfoglalási státuszok megjelenítéséhez

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'uj' => 'Új',
            'teljesitve' => 'Teljesítve',
            'elutasitva' => 'Elutasítva',
            'torolve' => 'Törölve',
            default => ucfirst($this->status),
        };
    }
}
