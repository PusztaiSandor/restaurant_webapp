<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalCharge extends Model
{
    use HasFactory;


    protected $primaryKey = 'global_charges_id';


    protected $fillable = [
        'charge_type',
        'delivery_method',
        'is_active',
        'is_optional',
        'is_percentage',
        'value',
        'description',
    ];

    
    protected $casts = [
        'is_active' => 'boolean',
        'is_optional' => 'boolean',
        'is_percentage' => 'boolean',
        'value' => 'float',
    ];

    // Segédfüggvény az átvételi módok magyar nyelvű megjelenítéshez
    public function getChargeTypeLabelAttribute()
    {
        return match ($this->charge_type) {
            'delivery_fee' => 'Kiszállítási díj',
            'service_fee' => 'Szervízdíj',
            'order_discount' => 'Rendelési kedvezmény',
            'cutlery' => 'Evőeszköz díj',
            default => ucfirst(str_replace('_', ' ', $this->charge_type)),
        };
    }

    // Segédfüggvény szállítási módra, magyar nyelvű megjelenítéshez
    public function getDeliveryMethodLabelAttribute()
    {
        return match ($this->delivery_method) {
            'delivery' => 'Kiszállítás',
            'pickup' => 'Személyes átvétel',
            'dine-in' => 'Helyben fogyasztás',
            default => ucfirst($this->delivery_method),
        };
    }
}
