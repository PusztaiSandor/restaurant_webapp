<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }

    // Asztalok lekérdezése egyedi módon
    // A 'table_code' mezőben több asztalkód is lehet vesszővel elválasztva
    public function tables()
    {
        // Segédfüggvény, Szétválasztjuk a kódokat tömbbé
        $codes = explode(',', $this->table_code);

        // Lekérdezzük az összes asztalt, amelynek kódja szerepel a tömbben
        return \App\Models\Table::whereIn('table_code', $codes)->get();
    }

    // Segédfüggvény az asztalfoglalási státuszok megjelenítéséhez

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'uj' => 'Új',
            'teljesitve' => 'Teljesítve',
            'elutasitva' => 'Elutasítva',
            'torolve' => 'Törölve',
            default => ucfirst($this->status), // Ha ismeretlen, akkor nagy kezdőbetűs változat
        };
    }
}
