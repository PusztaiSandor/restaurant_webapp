<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
